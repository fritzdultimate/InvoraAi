<?php

namespace App\Filament\Resources\CardOrders;

use App\Enums\CardOrderStatus;
use App\Models\CardOrder;
use App\Services\Card\CardOrderException;
use App\Services\Card\CardOrderService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

/**
 * The order workflow buttons, shared by the table and the view page.
 * Each button only shows when it is the next valid step for that order.
 */
class CardOrderActions
{
    public static function all(): array
    {
        return [
            self::step(CardOrderStatus::APPROVED, 'Approve', 'heroicon-o-check-badge', 'success'),
            self::step(CardOrderStatus::PROCESSING, 'Start production', 'heroicon-o-cog-6-tooth', 'info'),

            Action::make('ship')
                ->label('Mark shipped')
                ->icon('heroicon-o-truck')
                ->color('primary')
                ->visible(fn (CardOrder $record) => self::next($record) === CardOrderStatus::SHIPPED)
                ->modalDescription(fn (CardOrder $record) => 'Ship to: '.$record->shippingAddress())
                ->schema([
                    TextInput::make('courier')->required()->maxLength(100)->placeholder('DHL'),
                    TextInput::make('tracking_number')->required()->maxLength(100),
                    TextInput::make('tracking_url')->label('Tracking link')->url()->maxLength(255),
                    Textarea::make('user_message')->label('Message to customer (optional)')->rows(2),
                ])
                ->action(fn (array $data, CardOrder $record) => self::run(
                    fn () => app(CardOrderService::class)->advance($record, CardOrderStatus::SHIPPED, $data),
                    'Order marked as shipped'
                )),

            Action::make('activate')
                ->label('Activate card')
                ->icon('heroicon-o-bolt')
                ->color('success')
                ->visible(fn (CardOrder $record) => self::next($record) === CardOrderStatus::ACTIVE)
                ->modalDescription('Only the last 4 digits and expiry are stored. Never enter the full card number.')
                ->schema([
                    TextInput::make('last_four')->label('Last 4 digits')->required()->length(4)->regex('/^\d{4}$/'),
                    TextInput::make('expiry')->label('Expiry (MM/YY)')->required()->regex('#^(0[1-9]|1[0-2])/\d{2}$#')->placeholder('09/30'),
                    Textarea::make('user_message')->label('Message to customer (optional)')->rows(2),
                ])
                ->action(fn (array $data, CardOrder $record) => self::run(
                    fn () => app(CardOrderService::class)->advance($record, CardOrderStatus::ACTIVE, $data),
                    'Card activated'
                )),

            Action::make('reject')
                ->label('Reject & refund')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (CardOrder $record) => $record->status->isOpen())
                ->requiresConfirmation()
                ->modalDescription(fn (CardOrder $record) => '$'.number_format($record->fee, 2).' goes back to the customer\'s balance. This cannot be undone.')
                ->schema([
                    Textarea::make('reason')->label('Reason shown to the customer')->required()->rows(3)->maxLength(500),
                ])
                ->action(fn (array $data, CardOrder $record) => self::run(
                    fn () => app(CardOrderService::class)->reject($record, $data['reason']),
                    'Order rejected and fee refunded'
                )),

            Action::make('note')
                ->label('Internal note')
                ->icon('heroicon-o-pencil-square')
                ->color('gray')
                ->fillForm(fn (CardOrder $record) => ['admin_note' => $record->admin_note])
                ->schema([
                    Textarea::make('admin_note')->label('Only admins see this')->rows(4),
                ])
                ->action(fn (array $data, CardOrder $record) => $record->update(['admin_note' => $data['admin_note']])),
        ];
    }

    protected static function step(CardOrderStatus $to, string $label, string $icon, string $color): Action
    {
        return Action::make('to_'.$to->value)
            ->label($label)
            ->icon($icon)
            ->color($color)
            ->visible(fn (CardOrder $record) => self::next($record) === $to)
            ->requiresConfirmation()
            ->schema([
                Textarea::make('user_message')->label('Message to customer (optional)')->rows(2),
            ])
            ->action(fn (array $data, CardOrder $record) => self::run(
                fn () => app(CardOrderService::class)->advance($record, $to, $data),
                "Order moved to {$to->label()}"
            ));
    }

    protected static function next(CardOrder $record): ?CardOrderStatus
    {
        return app(CardOrderService::class)->nextStatus($record);
    }

    protected static function run(callable $work, string $done): void
    {
        try {
            $work();
            Notification::make()->title($done)->success()->send();
        } catch (CardOrderException $e) {
            Notification::make()->title($e->getMessage())->danger()->send();
        }
    }
}

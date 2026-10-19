<?php

namespace App\Filament\Resources\CardOrders\Pages;

use App\Enums\CardOrderStatus;
use App\Filament\Resources\CardOrders\CardOrderResource;
use App\Models\CardOrder;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListCardOrders extends ListRecords
{
    protected static string $resource = CardOrderResource::class;

    public function getTabs(): array
    {
        $count = fn (array $statuses) => CardOrder::whereIn('status', $statuses)->count();
        $open = [CardOrderStatus::APPROVED->value, CardOrderStatus::PROCESSING->value, CardOrderStatus::SHIPPED->value];

        return [
            'review' => Tab::make('To review')
                ->badge($count([CardOrderStatus::PENDING->value]) ?: null)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('status', CardOrderStatus::PENDING->value)),
            'progress' => Tab::make('In progress')
                ->badge($count($open) ?: null)
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', $open)),
            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn ($query) => $query->where('status', CardOrderStatus::ACTIVE->value)),
            'closed' => Tab::make('Cancelled / rejected')
                ->modifyQueryUsing(fn ($query) => $query->whereIn('status', [CardOrderStatus::CANCELLED->value, CardOrderStatus::REJECTED->value])),
            'all' => Tab::make('All'),
        ];
    }
}

<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use App\Services\MatchingBonusCleanupService;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('removeMatchingBonuses')
                ->label('Remove old matching bonuses')
                ->icon('heroicon-o-scissors')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Remove matching bonuses older than 30 days')
                ->modalDescription(fn () => $this->matchingBonusPreview())
                ->modalSubmitActionLabel('Remove now')
                ->action(function () {
                    $rows = app(MatchingBonusCleanupService::class)->run(apply: true);
                    $done = collect($rows)->whereNull('error');
                    $failed = collect($rows)->whereNotNull('error');

                    Notification::make()
                        ->title('Removed matching bonuses from ' . $done->count() . ' user(s)')
                        ->body('$' . number_format($done->sum('from_balance') + $done->sum('from_investments'), 2) . ' removed.'
                            . ($failed->isNotEmpty() ? ' ' . $failed->count() . ' user(s) failed and were left unchanged: ' . $failed->map(fn ($r) => $r['user']->email)->implode(', ') : ''))
                        ->color($failed->isEmpty() ? 'success' : 'warning')
                        ->persistent()
                        ->send();
                }),
            CreateAction::make(),
        ];
    }

    private function matchingBonusPreview(): HtmlString
    {
        $rows = collect(app(MatchingBonusCleanupService::class)->run());

        if ($rows->isEmpty()) {
            return new HtmlString('No matching bonuses older than 30 days are left to remove.');
        }

        $money = fn ($v) => '$' . number_format($v, 2);
        $lines = $rows->take(50)->map(fn ($r) => '<tr>'
            . '<td style="padding:4px 8px">' . e($r['user']->email) . '</td>'
            . '<td style="padding:4px 8px;text-align:right">' . $money($r['bonus']) . '</td>'
            . '<td style="padding:4px 8px;text-align:right">' . $money($r['from_balance']) . '</td>'
            . '<td style="padding:4px 8px;text-align:right">' . $money($r['from_investments']) . '</td>'
            . '<td style="padding:4px 8px;text-align:right">' . $money($r['unrecoverable']) . '</td>'
            . '</tr>')->implode('');

        $more = $rows->count() > 50 ? '<p style="margin-top:6px">…and ' . ($rows->count() - 50) . ' more.</p>' : '';

        return new HtmlString(
            '<p>' . $rows->count() . ' user(s). ' . $money($rows->sum('from_balance')) . ' comes off bonus balances and '
            . $money($rows->sum('from_investments')) . ' off running investments (capital and locked balance). '
            . $money($rows->sum('unrecoverable')) . ' was already spent on finished investments and is skipped. Profit already paid is not touched. This cannot be undone.</p>'
            . '<div style="max-height:320px;overflow:auto;margin-top:10px"><table style="width:100%;font-size:12px;text-align:left">'
            . '<tr><th style="padding:4px 8px">User</th><th style="padding:4px 8px;text-align:right">Bonus</th><th style="padding:4px 8px;text-align:right">From balance</th><th style="padding:4px 8px;text-align:right">From investments</th><th style="padding:4px 8px;text-align:right">Already spent</th></tr>'
            . $lines . '</table></div>' . $more
        );
    }

    public function getTabs(): array {
        return [
            'all' => Tab::make(),
            'admin' => Tab::make('Admins')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereHas('roles', fn ($q) =>
                        $q->where('name', 'admin')
                    )
                ),

            'verified' => Tab::make('Verified')
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->whereNotNull('email_verified_at')
            ),

            'unverified' => Tab::make('Unverified')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->whereNull('email_verified_at')
                ),
        ];
    }
}

<?php

namespace App\Filament\Resources\BotInvestments\Pages;

use App\Enums\BotInvestmentStatus;
use App\Filament\Resources\BotInvestments\BotInvestmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListBotInvestments extends ListRecords
{
    protected static string $resource = BotInvestmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array {
        return [
            'all' => Tab::make(),
            'admin' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'active')
                ),

            'terminate_req' => Tab::make('Terminate Request')
            ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('status', BotInvestmentStatus::TERMINATIONREQUEST->value)
            ),

            'terminated' => Tab::make('Terminated')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'terminated')
                ),

            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'completed')
                ),
        ];
    }
}

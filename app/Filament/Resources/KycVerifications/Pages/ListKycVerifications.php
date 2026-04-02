<?php

namespace App\Filament\Resources\KycVerifications\Pages;

use App\Filament\Resources\KycVerifications\KycVerificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListKycVerifications extends ListRecords
{
    protected static string $resource = KycVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array {
        return [
            'all' => Tab::make(),
            'pending' => Tab::make('pending')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'pending')
                ),

            'approved' => Tab::make('approved')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'approved')
                ),

            'rejected' => Tab::make('rejected')
                ->modifyQueryUsing(fn (Builder $query) =>
                    $query->where('status', 'rejected')
                ),
        ];
    }
}

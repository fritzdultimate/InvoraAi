<?php

namespace App\Filament\Resources\Deposits\Pages;

use App\Enums\DepositStatus;
use App\Filament\Resources\Deposits\DepositResource;
use App\Models\Deposit;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Widgets\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListDeposits extends ListRecords
{
    protected static string $resource = DepositResource::class;

    public string $statusTab = 'all';
    public array $tabs = [];


    // protected function getHeaderActions(): array {
    //     return [
    //         Action::make('all')
    //             ->label('All')
    //             ->button()
    //             ->color($this->statusTab === 'all' ? 'primary' : 'secondary')
    //             ->action(fn () => $this->setStatusTab('all')),

    //         Action::make('pending')
    //             ->label('Pending')
    //             ->button()
    //             ->color($this->statusTab === 'pending' ? 'primary' : 'secondary')
    //             ->action(fn () => $this->setStatusTab('pending')),

    //         Action::make('finished')
    //             ->label('Finished')
    //             ->button()
    //             ->color($this->statusTab === 'finished' ? 'primary' : 'secondary')
    //             ->action(fn () => $this->setStatusTab('finished')),

    //         Action::make('cancelled')
    //             ->label('Cancelled')
    //             ->button()
    //             ->color($this->statusTab === 'cancelled' ? 'primary' : 'secondary')
    //             ->action(fn () => $this->setStatusTab('cancelled')),
    //     ];
    // }

    public function mount(): void {
        $this->tabs = [
            'all' => [
                'label' => 'All',
                'count' => Deposit::count(),
            ],
            'pending' => [
                'label' => 'Pending',
                'count' => Deposit::where('status', DepositStatus::PENDING)->count(),
            ],
            'finished' => [
                'label' => 'Finished',
                'count' => Deposit::where('status', DepositStatus::FINISHED)->count(),
            ],
            'cancelled' => [
                'label' => 'Cancelled',
                'count' => Deposit::where('status', DepositStatus::CANCELLED)->count(),
            ],
        ];
        
    }

    public function setStatusTab(string $tab)
    {
        $this->statusTab = $tab;
        $this->resetPage(); // optional: resets pagination
    }

    protected function getTableQuery(): Builder|Relation|null
{
    $query = parent::getTableQuery();

    return match ($this->statusTab) {
        'pending' => $query->where('status', DepositStatus::PENDING),
        'finished' => $query->where('status', DepositStatus::FINISHED),
        'cancelled' => $query->where('status', DepositStatus::CANCELLED),
        default => $query, // 'all'
    };
}


}

<?php

namespace App\Livewire\Dashboard;

use App\Enums\DepositStatus;
use App\Services\DepositService;
use App\Services\NowPaymentsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class  WithdrawalDetails extends Component {

    public function render() {
        return view('livewire.dashboard.withdrawal-details');
    }

  

}
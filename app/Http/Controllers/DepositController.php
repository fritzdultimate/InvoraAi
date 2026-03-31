<?php

namespace App\Http\Controllers;


use App\Services\DepositService;

class DepositController extends Controller {
    public function markAsExpired() {
        DepositService::expireOldDeposits();
    }

    
}

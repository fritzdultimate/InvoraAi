<?php

use App\Http\Controllers\ProfitController;
use Illuminate\Support\Facades\Route;

Route::get('cron/investment/profit/distribute', [ProfitController::class, 'distribute']);
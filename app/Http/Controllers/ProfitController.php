<?php

namespace App\Http\Controllers;

use App\Services\Bot\BotProfitService;
use Illuminate\Http\Request;

class ProfitController extends Controller {
    public function distribute() {
        BotProfitService::run();        
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\ReferralBonusService;

class ReferralBonusController extends Controller {
    public function claim() {
        $result = ReferralBonusService::claimClaimable();

        return response()->json([
            'success' => true,
            ...$result,
        ]);
    }
}

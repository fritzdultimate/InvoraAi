<?php

namespace App\Services\Bot;

use App\Models\Bot;
use App\Models\BotLicense;
use App\Enums\BotLicenseStatus;
use Illuminate\Support\Facades\DB;

class BotLicenseService {
    public function purchase($user, Bot $bot): BotLicense {
        return DB::transaction(function () use ($user, $bot) {

            return BotLicense::create([
                'user_id' => $user->id,
                'bot_id' => $bot->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays($bot->license_duration_days),
                'status' => BotLicenseStatus::ACTIVE,
            ]);
        });
    }

    public function expireLicenses(): void {
        BotLicense::where('status', BotLicenseStatus::ACTIVE)
            ->where('expires_at', '<', now())
            ->update(['status' => BotLicenseStatus::EXPIRED]);
    }
}

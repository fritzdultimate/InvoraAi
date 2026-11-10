<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_bonuses', function (Blueprint $table) {
            $table->unsignedBigInteger('deposit_id')->nullable()->after('bot_investment_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('referral_bonuses', function (Blueprint $table) {
            $table->dropColumn('deposit_id');
        });
    }
};

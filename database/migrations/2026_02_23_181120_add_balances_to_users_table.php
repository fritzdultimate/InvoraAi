<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('main_balance', 20, 8)->default(0)->after('remember_token');
            $table->decimal('deposit_balance', 20, 8)->default(0)->after('main_balance');
            $table->decimal('referral_balance', 20, 8)->default(0)->after('deposit_balance');
            $table->decimal('profit_balance', 20, 8)->default(0)->after('referral_balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'main_balance',
                'deposit_balance',
                'referral_balance',
                'profit_balance',
            ]);
        });
    }
};

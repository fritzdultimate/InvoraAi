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
        Schema::table('bot_investments', function (Blueprint $table) {
            $table->decimal('referral_eligible_amount', 20, 8)->nullable()->after('capital');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bot_investments', function (Blueprint $table) {
            //
        });
    }
};

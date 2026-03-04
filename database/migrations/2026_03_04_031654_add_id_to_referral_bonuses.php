<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('referral_bonuses', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE referral_bonuses
                ADD COLUMN id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY FIRST
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referral_bonuses', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE referral_bonuses
                DROP COLUMN id
            ");
        });
    }
};

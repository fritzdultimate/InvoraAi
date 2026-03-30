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
        Schema::create('ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('level');
            $table->decimal('required_volume', 18, 2);
            $table->decimal('direct_referrals_volume', 18, 2);
            $table->decimal('one_time_bonus')->default(500);
            $table->decimal('deposits', 20, 8)->default(500);
            $table->unsignedInteger('direct_referrals')->default(15);
            $table->decimal('bonus', 20, 8)->default(500);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranks');
    }
};

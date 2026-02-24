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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('referred_by_id')->nullable()->constrained('users')->onDelete('set null');

            $table->foreignId('level_1_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_2_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_3_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_4_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_5_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_6_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_7_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_8_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_9_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_10_id')->nullable()->constrained('users')->onDelete('set null');

            $table->unsignedInteger('total_direct_referrals')->default(0);
            $table->unsignedInteger('total_downlines')->default(0);
            $table->decimal('total_earnings', 16, 2)->default(0);

            $table->timestamps();

            $table->index('user_id');
            $table->index('referred_by_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};

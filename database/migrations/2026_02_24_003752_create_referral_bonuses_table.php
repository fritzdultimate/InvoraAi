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
        Schema::create('referral_bonuses', function (Blueprint $table) {
            $table->foreignId('referred_by_id')->nullable()->constrained('users')->onDelete('set null');

            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // receiver of reward
            $table->foreignId('from_user_id')->nullable()->constrained('users')->nullOnDelete(); // origin of commission
            $table->unsignedTinyInteger('level')->default(1); // 1..10
            $table->decimal('percent');
            $table->unsignedBigInteger('amount')->default(0); 
            $table->foreignId('bot_investment_id')->nullable()->constrained('bot_investments')->nullOnDelete();
            $table->string('status')->default('pending');
            $table->json('meta')->nullable();
            $table->timestamp('calculated_for')->nullable();
            $table->timestamp('claimable_at')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id','status']);
            $table->index(['from_user_id','calculated_for']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_bonuses');
    }
};

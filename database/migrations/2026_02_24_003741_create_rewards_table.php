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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bot_investment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 36, 18);
            $table->string('reward_type'); // staking|affiliate|matching
            $table->integer('level')->nullable();
            $table->foreignId('source_user_id')->nullable()->constrained('users');
            $table->string('status')->default('pending'); // pending|credited|paid
            $table->timestamp('credited_at')->nullable();
            $table->timestamp('compounded_at')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->foreignId('locked_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('locked_reason')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};

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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('withdrawal_currency_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('withdrawal_network_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->decimal('amount', 20, 8);
            $table->string('address');
            $table->string('status')->default('pending');
            $table->string('asset');
            $table->string('tx_hash')->nullable();
            $table->json('meta')->nullable();
            $table->string('failure_reason')->nullable();

            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};

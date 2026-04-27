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
        Schema::create('live_trades', function (Blueprint $table) {
            $table->id();

            $table->string('tx_hash')->unique();

            // Protocol
            $table->string('protocol')->nullable();

            // Buy side
            $table->decimal('buy_amount', 30, 12)->nullable();
            $table->string('buy_symbol')->nullable();
            $table->decimal('buy_price_usd', 20, 8)->nullable();

            // Sell side
            $table->decimal('sell_amount', 30, 12)->nullable();
            $table->string('sell_symbol')->nullable();
            $table->decimal('sell_price_usd', 20, 8)->nullable();

            // Meta
            $table->timestamp('block_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_trades');
    }
};

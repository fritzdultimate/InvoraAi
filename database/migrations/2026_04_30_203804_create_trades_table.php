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
        Schema::create('trades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trading_asset_id')->constrained();

            $table->decimal('position_size', 12, 2);

            $table->decimal('entry_price_long', 12, 2);
            $table->decimal('entry_price_short', 12, 2);

            $table->decimal('exit_price_long', 12, 2)->nullable();
            $table->decimal('exit_price_short', 12, 2)->nullable();

            $table->decimal('funding_rate', 8, 6)->default(0);
            $table->decimal('funding_profit', 12, 4)->default(0);

            $table->decimal('price_pnl', 12, 4)->default(0);
            $table->decimal('fees', 12, 4)->default(0);

            $table->decimal('total_net', 12, 4)->default(0);

            $table->enum('status', ['open', 'closed'])->default('open');

            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trades');
    }
};

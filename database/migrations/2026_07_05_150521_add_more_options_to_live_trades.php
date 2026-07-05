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
        Schema::table('live_trades', function (Blueprint $table) {
            $table->string('network')->default('eth')->after('id');
            $table->unsignedInteger('log_index')->default(0)->after('tx_hash');
            $table->string('dex')->nullable()->after('protocol');
            $table->string('pair')->nullable()->after('dex');
            $table->string('base_symbol')->nullable();
            $table->string('quote_symbol')->nullable();
            $table->string('side')->nullable(); // buy | sell
            $table->decimal('price', 36, 18)->nullable();
            $table->decimal('price_usd', 24, 8)->nullable();
            $table->decimal('amount', 36, 18)->nullable();
            $table->decimal('amount_usd', 20, 2)->nullable();

            $table->index(['network', 'block_time']);
            $table->index('dex');
        });

        Schema::table('live_trades', function (Blueprint $table) {
            $table->unique(['tx_hash', 'log_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_trades', function (Blueprint $table) {
            $table->dropUnique(['tx_hash', 'log_index']);
            $table->dropColumn([
                'network', 'log_index', 'dex', 'pair', 'base_symbol',
                'quote_symbol', 'side', 'price', 'price_usd', 'amount', 'amount_usd',
            ]);
        });
    }
};

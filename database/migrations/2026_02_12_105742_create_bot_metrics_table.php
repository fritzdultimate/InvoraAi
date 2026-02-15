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
        Schema::create('bot_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_id')->constrained()->cascadeOnDelete();

            $table->decimal('win_rate', 5, 2)->nullable();
            $table->decimal('drawdown', 5, 2)->nullable();
            $table->decimal('roi_percent', 5, 2)->nullable();

            $table->integer('total_trades')->nullable();

            $table->timestamp('recorded_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_metrics');
    }
};

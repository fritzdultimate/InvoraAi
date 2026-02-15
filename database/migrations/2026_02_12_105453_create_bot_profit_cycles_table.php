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
        Schema::create('bot_profit_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_investment_id')->constrained()->cascadeOnDelete();

            $table->decimal('profit_amount', 20, 8);
            $table->decimal('percent', 5, 2);

            $table->string('status')->default('credited');

            $table->timestamp('cycle_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_profit_cycles');
    }
};

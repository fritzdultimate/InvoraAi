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
        Schema::create('bot_terminations', function (Blueprint $table) {
            $table->id();
             $table->foreignId('bot_investment_id')->constrained()->cascadeOnDelete();

            $table->decimal('penalty_percent', 5, 2);
            $table->decimal('penalty_amount', 20, 8);

            $table->decimal('amount_returned', 20, 8);

            $table->timestamp('terminated_at');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_terminations');
    }
};

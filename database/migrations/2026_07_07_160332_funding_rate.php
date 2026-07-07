<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('funding_rates', function (Blueprint $table) {
            $table->id();
            $table->string('coin');
            $table->enum('margin_type', ['stablecoin', 'coin']);
            $table->string('exchange');
            $table->decimal('funding_rate', 10, 6)->nullable();
            $table->decimal('daily_rate', 10, 6)->nullable();
            $table->timestamps();

            $table->unique(['coin', 'margin_type', 'exchange']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funding_rates');
    }
};
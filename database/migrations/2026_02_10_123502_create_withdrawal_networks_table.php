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
        Schema::create('withdrawal_networks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('withdrawal_currency_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('network');
            $table->decimal('fee', 16, 8)->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_networks');
    }
};

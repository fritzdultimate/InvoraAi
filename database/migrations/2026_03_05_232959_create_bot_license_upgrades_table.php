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
        Schema::create('bot_license_upgrades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bot_license_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('from_bot_id')
                ->constrained('bots');

            $table->foreignId('to_bot_id')
                ->constrained('bots');

            $table->decimal('price_paid', 15, 2)->nullable();

            $table->string('status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_license_upgrades');
    }
};

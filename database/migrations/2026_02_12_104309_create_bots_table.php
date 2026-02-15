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
        Schema::create('bots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();

            $table->text('description')->nullable();

            $table->decimal('min_amount', 20, 8);
            $table->decimal('max_amount', 20, 8);

            $table->integer('lock_days');
            $table->integer('license_duration_days');

            $table->decimal('daily_return_percent', 5, 2);
            $table->integer('payout_interval_hours');

            $table->decimal('early_withdrawal_penalty_percent', 5, 2)->default(15);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bots');
    }
};

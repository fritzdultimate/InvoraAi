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
        Schema::create('challenge_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // volume | referrals | fastest_activation
            $table->decimal('prize_pool', 12, 2);
            $table->json('rewards'); 
            $table->integer('phase')->default(1)->index();

            $table->decimal('min_activation_amount', 12, 2)->nullable(); // for fastest
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_categories');
    }
};

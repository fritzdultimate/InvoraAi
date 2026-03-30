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
        Schema::create('rank_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('rank_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('amount', 20, 8);

            $table->string('title')->nullable();
            $table->string('description')->nullable();

            $table->enum('status', [
                'credited',
                'locked',
                'reversed'
            ])->default('credited');

            $table->timestamp('credited_at')->nullable();
            $table->timestamp('locked_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rank_bonuses');
    }
};

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
        Schema::create('challenge_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained();
            $table->foreignId('challenge_category_id')->constrained('challenge_categories');
            $table->foreignId('user_id')->constrained();

            $table->decimal('score', 20, 2)->default(0); // volume or count
            $table->integer('rank')->nullable();

            $table->integer('previous_rank')->nullable();
            $table->integer('rank_change')->default(0);
            $table->integer('phase')->default(1);

            
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['challenge_id', 'challenge_category_id', 'user_id'],
                'challenge_entry_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_entries');
    }
};

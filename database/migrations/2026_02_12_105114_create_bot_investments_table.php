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
        Schema::create('bot_investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bot_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bot_license_id')->constrained()->cascadeOnDelete();

            $table->decimal('amount', 20, 8);
            $table->decimal('capital', 20, 8);

            $table->decimal('total_profit', 20, 8)->default(0);

            $table->timestamp('started_at');
            $table->timestamp('matures_at');
            $table->timestamp('next_cycle_at');

            $table->string('status')->default('active');

            $table->boolean('is_early_terminated')->default(false);

            $table->boolean('is_archived')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_investments');
    }
};

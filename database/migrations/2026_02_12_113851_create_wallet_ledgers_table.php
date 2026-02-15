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
        Schema::create('wallet_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->decimal('credit', 20, 8)->default(0);
            $table->decimal('debit', 20, 8)->default(0);

            $table->decimal('balance_after', 20, 8);

            $table->string('reference_type'); 
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->string('asset');

            $table->string('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_ledgers');
    }
};

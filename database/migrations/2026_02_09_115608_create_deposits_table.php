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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reference')->unique();
            $table->string('currency');
            $table->decimal('amount', 15, 4)->default(0);
            $table->decimal('actually_paid', 15, 4)->default(0);
            $table->decimal('bonus', 15, 4)->default(0);
            $table->string('address')->nullable();
            $table->string('tx_hash')->nullable()->index();
            $table->unsignedInteger('confirmations')->default(0);
            $table->string('status')->default('pending');
            $table->string('nowpayments_invoice_id')->nullable();
            $table->json('meta')->nullable();
            $table->string('narration')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('bonus_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};

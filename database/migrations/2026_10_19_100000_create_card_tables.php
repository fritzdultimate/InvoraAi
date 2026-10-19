<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('network', 20);
            $table->string('form', 20)->default('physical');
            $table->string('design', 30)->default('obsidian');
            $table->string('tagline')->nullable();
            $table->decimal('preorder_fee', 16, 2);
            $table->decimal('spending_limit', 16, 2);
            $table->string('limit_period', 20)->default('monthly');
            $table->json('features')->nullable();
            $table->string('delivery_estimate')->nullable();
            $table->unsignedInteger('stock')->nullable();
            $table->unsignedInteger('max_per_user')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('card_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('card_product_id')->constrained()->restrictOnDelete();

            // snapshot of the product at the time of ordering
            $table->string('card_name');
            $table->string('network', 20);
            $table->string('form', 20);
            $table->string('design', 30);
            $table->decimal('fee', 16, 2);
            $table->decimal('spending_limit', 16, 2);
            $table->string('limit_period', 20);
            $table->string('paid_from', 30);

            $table->string('status', 20)->default('pending')->index();
            $table->string('name_on_card', 26);

            $table->string('phone', 30)->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->nullable();

            $table->string('courier', 100)->nullable();
            $table->string('tracking_number', 100)->nullable();
            $table->string('tracking_url')->nullable();
            $table->string('last_four', 4)->nullable();
            $table->string('expiry', 5)->nullable();

            $table->text('user_message')->nullable();
            $table->text('admin_note')->nullable();

            $table->unsignedBigInteger('debit_ledger_id')->nullable();
            $table->unsignedBigInteger('refund_ledger_id')->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->timestamp('processing_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_orders');
        Schema::dropIfExists('card_products');
    }
};

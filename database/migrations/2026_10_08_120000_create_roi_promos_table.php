<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('roi_promos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(false);
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');

            // the return
            $table->decimal('multiplier', 5, 2)->default(2);
            $table->string('boost_duration')->default('full'); // full | days
            $table->unsignedInteger('boost_days')->nullable();

            // who can join
            $table->json('bot_ids')->nullable();
            $table->string('audience')->default('all'); // all | new | existing
            $table->boolean('require_kyc')->default(false);
            $table->boolean('require_new_license')->default(false);
            $table->decimal('min_amount', 20, 8)->nullable();
            $table->decimal('max_amount', 20, 8)->nullable();
            $table->unsignedInteger('max_per_user')->nullable();
            $table->unsignedInteger('max_entries')->nullable();

            // dashboard banner
            $table->boolean('show_banner')->default(true);
            $table->boolean('show_before_start')->default(false);
            $table->string('banner_theme')->default('emerald'); // emerald | gold | violet
            $table->string('headline')->nullable();
            $table->text('description')->nullable();
            $table->string('cta_label')->nullable();
            $table->boolean('show_countdown')->default(true);
            $table->boolean('show_spots')->default(true);
            $table->boolean('dismissible')->default(true);

            // email alerts
            $table->string('email_mode')->default('off'); // off | first | every

            $table->timestamps();
        });

        Schema::table('bot_investments', function (Blueprint $table) {
            $table->foreignId('roi_promo_id')->nullable()->constrained('roi_promos')->nullOnDelete();
            $table->decimal('roi_multiplier', 5, 2)->nullable();
            $table->timestamp('roi_boost_ends_at')->nullable();
            $table->decimal('roi_boost_earned', 20, 8)->default(0);
            $table->timestamp('roi_first_notified_at')->nullable();
        });
    }

    public function down(): void {
        Schema::table('bot_investments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('roi_promo_id');
            $table->dropColumn(['roi_multiplier', 'roi_boost_ends_at', 'roi_boost_earned', 'roi_first_notified_at']);
        });

        Schema::dropIfExists('roi_promos');
    }
};

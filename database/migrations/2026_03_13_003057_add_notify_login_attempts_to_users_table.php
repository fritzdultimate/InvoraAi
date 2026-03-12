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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_login_attempts')->default(true)->after('email_verified_at');

            $table->boolean('notify_email_notifications')->default(true)->after('notify_login_attempts');
            $table->boolean('notify_deposit_alerts')->default(true)->after('notify_email_notifications');
            $table->boolean('notify_withdrawal_alerts')->default(true)->after('notify_deposit_alerts');
            $table->boolean('notify_security_alerts')->default(true)->after('notify_withdrawal_alerts');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notify_login_attempts',
                'notify_email_notifications',
                'notify_deposit_alerts',
                'notify_withdrawal_alerts',
                'notify_security_alerts'
            ]);
        });
    }
};

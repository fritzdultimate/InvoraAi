<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            // gateway = paid through NOWPayments, admin = created by an admin on behalf of the user
            $table->string('source', 20)->default('gateway')->index()->after('status');
            $table->foreignId('created_by')->nullable()->after('source')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn('source');
        });
    }
};

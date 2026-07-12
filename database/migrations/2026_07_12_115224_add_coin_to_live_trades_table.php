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
        Schema::table('live_trades', function (Blueprint $table) {
            $table->string('coin')->nullable()->after('network');
            $table->index('coin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_trades', function (Blueprint $table) {
            $table->dropIndex(['coin']);
            $table->dropColumn('coin');
        });
    }
};

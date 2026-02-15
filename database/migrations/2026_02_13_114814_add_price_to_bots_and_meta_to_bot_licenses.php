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
        Schema::table('bots', function (Blueprint $table) {
            $table->decimal('price', 20, 8)
                  ->after('slug')
                  ->default(0);
        });

        Schema::table('bot_licenses', function (Blueprint $table) {
            $table->json('meta')
                  ->nullable()
                  ->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::table('bot_licenses', function (Blueprint $table) {
                Schema::table('bots', function (Blueprint $table) {
                $table->dropColumn('price');
            });

            Schema::table('bot_licenses', function (Blueprint $table) {
                $table->dropColumn('meta');
            });
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('deposits', 'or')) {
            Schema::table('deposits', function (Blueprint $table) {
                $table->dropColumn('or');
            });
        }
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->boolean('or')->default(false);
        });
    }
};

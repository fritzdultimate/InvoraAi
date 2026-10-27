<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // null user_id = global notification for everyone
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('notification_user', function (Blueprint $table) {
            $table->timestamp('seen_at')->nullable()->after('read_at');
        });

        // Old personal notifications were attached to their user at the same moment they were created.
        // Global ones only get pivot rows later, when someone reads them.
        DB::table('notifications')->orderBy('id')->chunkById(500, function ($notifications) {
            foreach ($notifications as $n) {
                $pivots = DB::table('notification_user')->where('notification_id', $n->id)->get();

                if ($pivots->count() !== 1 || ! $pivots[0]->created_at || ! $n->created_at) {
                    continue;
                }

                $gap = abs(strtotime($pivots[0]->created_at) - strtotime($n->created_at));

                if ($gap <= 5) {
                    DB::table('notifications')->where('id', $n->id)->update(['user_id' => $pivots[0]->user_id]);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('notification_user', function (Blueprint $table) {
            $table->dropColumn('seen_at');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};

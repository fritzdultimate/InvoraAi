<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

if (! function_exists('generate_deposit_reference')) {

    function generate_deposit_reference(): string {
        do {
            $reference = 'IVORA-DEP-' .
                now()->format('YmdHis') . '-' .
                strtoupper(Str::random(8));

            
            $exists = DB::table('deposits')
                ->where('reference', $reference)
                ->exists();

        } while ($exists);

        return $reference;
    }

    function generate_withdrawal_reference(): string {
        do {
            $reference = 'IVORA-WD-' .
                now()->format('YmdHis') . '-' .
                strtoupper(Str::random(8));

            
            $exists = DB::table('withdrawals')
                ->where('reference', $reference)
                ->exists();

        } while ($exists);

        return $reference;
    }
}

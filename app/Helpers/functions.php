<?php

use App\Models\Referral;

if (!function_exists('format_compact')) {
    function format_compact($number, $precision = 1)
    {
        if ($number >= 1000000000) {
            return round($number / 1000000000, $precision) . 'B';
        } elseif ($number >= 1000000) {
            return round($number / 1000000, $precision) . 'M';
        } elseif ($number >= 1000) {
            return round($number / 1000, $precision) . 'K';
        }

        return number_format($number, 2);
    }
}

function getDownlineUserIds(int $userId, int $maxDepth = 10): array
{
    $currentLevel = [$userId];
    $all = [];

    for ($i = 0; $i < $maxDepth; $i++) {

        $nextLevel = Referral::whereIn('level_1_id', $currentLevel)
            ->pluck('user_id')
            ->toArray();

        if (empty($nextLevel)) {
            break;
        }

        $all = array_merge($all, $nextLevel);

        // move to next depth
        $currentLevel = $nextLevel;
    }

    return array_values(array_unique($all));
}
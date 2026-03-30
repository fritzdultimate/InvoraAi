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

function getDownlineUsersByLevel(int $userId, int $targetLevel): array
{
    $currentLevel = [$userId];

    for ($i = 1; $i <= $targetLevel; $i++) {

        $nextLevel = Referral::whereIn('level_1_id', $currentLevel)
            ->pluck('user_id')
            ->toArray();

        if ($i === $targetLevel) {
            return $nextLevel;
        }

        $currentLevel = $nextLevel;
    }

    return [];
}

function mask($target) {
    if (!$target) return '—';

    $length = strlen($target);

    if ($length <= 2) {
        return substr($target, 0, 1) . '*';
    }
    return substr($target, 0, 2) . str_repeat('*', max(0, $length - 4)) . substr($target, -2);
}
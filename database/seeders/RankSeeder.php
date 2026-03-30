<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['Amateur', 1, 4_999, 1_499, 249, 500, 2, 50],
            ['Bronze', 2, 9_999, 2_499, 499, 500, 2, 50],
            ['Achiever', 3, 19_999, 4_999, 999, 500, 2, 50],
            ['Silver', 4, 49_999, 7_499, 2_499, 500, 2, 50],
            ['Gold', 5, 99_999, 9_999, 4_999, 500, 2, 50],
            ['Platinum', 6, 299_999, 24_999, 14_999, 500, 2, 50],
            ['Ruby', 7, 499_999, 34_999, 24_999, 500, 2, 50],
            ['Emerald', 8, 999_999, 49_999, 49_999, 500, 2, 50],
            ['Leader', 9, 1_999_999, 99_999, 99_999, 500, 2, 50],
            ['Diamond', 10, 4_999_999, 199_999, 249_999, 500, 2, 50],
            ['Titan', 11, 9_999_999, 499_999, 499_999, 500, 2, 50],
            ['Royalty', 12, 19_999_999, 999_999, 999_999, 500, 2, 50],
        ];

        foreach ($levels as [$name, $level, $vol, $dirVol, $oneTimeBonus, $deps, $ref, $bonus]) {
            Rank::create([
                'name' => $name,
                'level' => $level,
                'required_volume' => $vol,
                'direct_referrals_volume' => $dirVol,
                'one_time_bonus' => $oneTimeBonus,
                'deposits' => $deps,
                'direct_referrals' => $ref,
                'bonus' => $bonus
            ]);
        }
        $this->command->info('✅ 12 ranks seeded successfully!');
    }
}

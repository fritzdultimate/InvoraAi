<?php

namespace Database\Seeders;

use App\Models\UnilevelPercentage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnilevelPercentageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $data = [
            1 => 100,
            2 => 50,
            3 => 25,
            4 => 15,
            5 => 10,
            6 => 5,
            7 => 5,
        ];

        foreach ($data as $level => $percentage) {
            UnilevelPercentage::updateOrCreate(
                ['level' => $level],
                ['percentage' => $percentage]
            );
        }
    }
}

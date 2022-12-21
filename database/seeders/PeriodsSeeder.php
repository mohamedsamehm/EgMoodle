<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodsSeeder extends Seeder
{
    public function run()
    {
        DB::table('periods')->truncate();
        DB::table("periods")->insert([
            [
                'period' => 1,
                'time' => '09:00',
            ],
            [
                'period' => 2,
                'time' => '09:30',
            ],
            [
                'period' => 3,
                'time' => '10:00',
            ],
            [
                'period' => 4,
                'time' => '10:30',
            ],
            [
                'period' => 5,
                'time' => '11:00',
            ],
            [
                'period' => 6,
                'time' => '11:30',
            ],
            [
                'period' => 7,
                'time' => '12:00',
            ],
            [
                'period' => 8,
                'time' => '12:30',
            ],
            [
                'period' => 9,
                'time' => '01:00',
            ],
            [
                'period' => 10,
                'time' => '01:30',
            ],
        ]);
    }
}

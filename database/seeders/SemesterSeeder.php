<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('semesters')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('semesters')->insert([[
            'name' => 'First',
            'date_start' => '2021-10-01',
            'date_end' => '2021-10-01',
            'active' => true,
        ],[
            'name' => 'Second',
            'date_start' => '2022-03-01',
            'date_end' => '2022-03-01',
            'active' => true,
        ],[
            'name' => 'Summer',
            'date_start' => '2022-07-01',
            'date_end' => '2022-07-01',
            'active' => true,
        ]]);
    }
}

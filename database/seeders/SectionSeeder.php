<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sections')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('sections')->insert([[
            'name' => 'Sec 1',
            'group' => 1,
            'max_capicty' => 30,
            'semster_id' => 1,
            'level_id' => 1,
            'department_id' => 1
        ],[
            'name' => 'Sec 2',
            'group' => 1,
            'max_capicty' => 30,
            'semster_id' => 1,
            'level_id' => 1,
            'department_id' => 1
        ],[
            'name' => 'Sec 3',
            'group' => 1,
            'max_capicty' => 30,
            'semster_id' => 1,
            'level_id' => 1,
            'department_id' => 1
        ]]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessorSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('professors')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('professors')->insert([[
            'user_id' => 1,
        ],[
            'user_id' => 2,
        ],[
            'user_id' => 3,
        ]]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('students')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $arr = [];
        for ($i = 4; $i <= 53; $i++) {
            array_push($arr, [
                'gpa' => 2.13,
                'hours' => 173,
                'level' => 4,
                'department' => 'Computer',
                'Regulation' => 2012,
                'user_id' => $i
            ]);
        }
        DB::table('students')->insert($arr);
    }
}

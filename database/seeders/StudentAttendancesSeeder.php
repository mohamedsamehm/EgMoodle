<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentAttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('student_attendances')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $arr = [];
        for ($i = 1; $i <= 30; $i++) {
            for ($j = 1; $j <= 50; $j++) {
                array_push($arr, [
                    'meeting_id' => $i,
                    'student_id' => $j,
                    'type'=> rand(0, 1)
                ]);
            }
        }
        DB::table('student_attendances')->insert($arr);
    }
}

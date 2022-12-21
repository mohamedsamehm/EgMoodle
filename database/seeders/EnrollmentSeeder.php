<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('enrollments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $arr = [];
        for ($i = 1; $i <= 50; $i++) {
            array_push($arr, [
                'course_id' => 'CMP538',
                'section_id' => 1,
                'student_id' => $i
            ]);
            array_push($arr, [
                'course_id' => 'CMP524',
                'section_id' => 1,
                'student_id' => $i
            ]);
            array_push($arr, [
                'course_id' => 'P2B',
                'section_id' => 1,
                'student_id' => $i
            ]);
            array_push($arr, [
                'course_id' => 'CMP461',
                'section_id' => 1,
                'student_id' => $i
            ]);
            array_push($arr, [
                'course_id' => 'CMP426',
                'section_id' => 1,
                'student_id' => $i
            ]);
            array_push($arr, [
                'course_id' => 'CMP435',
                'section_id' => 1,
                'student_id' => $i
            ]);
        }
        DB::table('enrollments')->insert($arr);
    }
}

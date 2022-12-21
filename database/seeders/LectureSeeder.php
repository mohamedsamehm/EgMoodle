<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lectures')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('lectures')->insert([[
            'course_id' => 'CMP461',
            'section_id' => 1,
            'professor_id' => 1
        ],[
            'course_id' => 'CMP524',
            'section_id' => 1,
            'professor_id' => 1
        ],[
            'course_id' => 'CMP524',
            'section_id' => 2,
            'professor_id' => 1
        ],[
            'course_id' => 'CMP524',
            'section_id' => 3,
            'professor_id' => 1
        ],[
            'course_id' => 'CMP538',
            'section_id' => 1,
            'professor_id' => 1
        ],[
            'course_id' => 'CMP538',
            'section_id' => 2,
            'professor_id' => 1
        ],[
            'course_id' => 'CMP538',
            'section_id' => 3,
            'professor_id' => 1
        ],[
            'course_id' => 'P2B',
            'section_id' => 1,
            'professor_id' => 1
        ],[
            'course_id' => 'CMP435',
            'section_id' => 1,
            'professor_id' => 2
        ],[
            'course_id' => 'CMP435',
            'section_id' => 2,
            'professor_id' => 2
        ],[
            'course_id' => 'CMP435',
            'section_id' => 3,
            'professor_id' => 2
        ],[
            'course_id' => 'CMP426',
            'section_id' => 1,
            'professor_id' => 3
        ],[
            'course_id' => 'CMP426',
            'section_id' => 2,
            'professor_id' => 3
        ],[
            'course_id' => 'CMP426',
            'section_id' => 3,
            'professor_id' => 3
        ],]);
    }
}

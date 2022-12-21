<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('exams')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('exams')->insert([[
            'opens_at' => now(),
            'duration' => '01:00:00',
            'material_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]]);
    }
}

//exams, questions, options

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CoursesSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('courses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('courses')->insert([[
            'id' => 'CMP461',
            'name' => 'Project-1',
            'image' => '1.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'CMP422',
            'name' => 'Computer Graphics',
            'image' => '2.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'CMP424',
            'name' => 'Computer Network',
            'image' => '3.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'CMP426',
            'name' => 'Logic-2',
            'image' => '4.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'CMP435',
            'name' => 'Operating System',
            'image' => '5.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'CMP436',
            'name' => 'Software Engineering',
            'image' => '6.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'CMP522',
            'name' => 'Artificial Intelligence',
            'image' => '1.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'CMP524',
            'name' => 'Modeling and Simulation',
            'image' => '2.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'CMP538',
            'name' => 'Pattern Recognition',
            'image' => '3.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'ELC422',
            'name' => 'Digital Signal Processing',
            'image' => '4.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ],
        [
            'id' => 'P2A',
            'name' => 'Project-2-a',
            'image' => '5.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => 'CMP461'
        ],
        [
            'id' => 'P2B',
            'name' => 'Project-2-B',
            'image' => '6.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => 'P2A'
        ],
        [
            'id' => 'CMP523',
            'name' => 'Distributed Computer System',
            'image' => '1.svg',
            'credit_hour' => 3,
            'Regulation' => 2012,
            'Pre_Req' => ''
        ]]);
    }
}

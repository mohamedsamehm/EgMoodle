<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialSectionSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('material_sections')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $arr = [];
        for ($i = 1; $i <= 43; $i++) {
            array_push($arr, [
                'material_id' => $i,
                'section_id' => 1,
            ]);
        }
        DB::table('material_sections')->insert($arr);
    }
}


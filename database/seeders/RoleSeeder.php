<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
		{
			DB::table('roles')->truncate();
			DB::table('roles')->insert(
				array(
					array(
						'id' => 1,
						'slug' => 'admin',
						'name' => 'Admin',
						'permission' => '',
						'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
					),
					array(
						'id' => 2,
						'slug' => 'student',
						'name' => 'Student',
						'permission' => '',
						'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
					),
					array(
						'id' => 3,
						'slug' => 'engineer',
						'name' => 'Engineer',
						'permission' => '',
						'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
					),
					array(
						'id' => 4,
						'slug' => 'professor',
						'name' => 'Professor',
						'permission' => '',
						'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
					),
				)
			);
		}
}

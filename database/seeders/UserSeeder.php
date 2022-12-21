<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
		public function run()
		{
			DB::statement('SET FOREIGN_KEY_CHECKS=0;');
			DB::table('users')->truncate();
			DB::statement('SET FOREIGN_KEY_CHECKS=1;');
			DB::table('users')->insert(
				array(
					array(
						'id' => 1,
						'firstName' => 'Abdel Moneim',
						'lastName' => 'Fouda',
						'fullName' => 'Abdel Moneim Fouda',
						'username' => 'professor',
						'email' => 'professor@gmail.com',
						'password' => Hash::make('password'),
						'roleId' => 4,
						'timezone' => 'Africa/Cairo',
						'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
					),
					array(
						'id' => 2,
						'firstName' => 'Assem',
						'lastName' => 'Badr',
						'fullName' => 'Assem Badr',
						'username' => 'professor_asem',
						'email' => 'professor_asem@gmail.com',
						'password' => Hash::make('password'),
						'roleId' => 4,
						'timezone' => 'Africa/Cairo',
						'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
					),
					array(
						'id' => 3,
						'firstName' => 'khaled',
						'lastName' => 'Morsi',
						'fullName' => 'khaled Morsi',
						'username' => 'professor_khaled',
						'email' => 'professor_khaled@gmail.com',
						'password' => Hash::make('password'),
						'roleId' => 4,
						'timezone' => 'Africa/Cairo',
						'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
					),
					array(
						'id' => 4,
						'firstName' => 'Mohamed',
						'lastName' => 'Sameh',
						'fullName' => 'Mohamed Sameh',
						'username' => 'mohamed',
						'email' => 'mohamed@gmail.com',
						'password' => Hash::make('password'),
						'roleId' => 2,
						'timezone' => 'Africa/Cairo',
						'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
						'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
					),
				)
			);
			User::factory()->count(49)->create();
		}
}

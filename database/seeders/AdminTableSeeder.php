<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'itsupport@northtrend.com',
            'password' => Hash::make('H2c@tN!p'),
            'role_id' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'weak_password' => 'H2c@tN!p',
        ]);
    }
}

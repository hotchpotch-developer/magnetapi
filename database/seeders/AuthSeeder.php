<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'role_id' => 1,
                'emp_id' => 'SA001',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'superadmin@gmail.com',
                'phone' => 1234567890,
                'password' => '$2y$10$V0vQoRwkEk27JYR1Oa4r9u5VZmwoCrmxvtJtc8BdF72adbZObNjFa',
                'status' => 'active'
            ]
        ]);
    }
}

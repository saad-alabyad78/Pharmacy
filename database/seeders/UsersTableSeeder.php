<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Test Super Admin 1',
                'phone' => '0981654321',
                'password' => Hash::make('password1'),
                'role' => 'super_admin',
                'warehouse_id' => null
            ],
            [
                'name' => 'Test Admin 1',
                'phone' => '0987654321',
                'password' => Hash::make('password1'),
                'role' => 'admin',
                'warehouse_id' => 1
            ],
            [
                'name' => 'Test Pharmacicst 2',
                'phone' => '0987154321',
                'password' => Hash::make('password2'),
                'role' => 'pharmacist',
                'warehouse_id' => null
            ],
        ]);
    }
}
//['Pharmacist', 'Admin','super_admin']

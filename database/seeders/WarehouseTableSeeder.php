<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('warehouses')->insert([
            [
                'name' => 'warehouses 1 ',
            ],
            [
                'name' => 'warehouses 2 ',
            ],
            [
                'name' => 'warehouses 3 ',
            ],

        ]);
    }
}

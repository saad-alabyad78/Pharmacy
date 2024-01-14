<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            [
                'name' => 'companie 1 ',
            ],
            [
                'name' => 'companie 2 ',
            ],
            [
                'name' => 'companie 3 ',
            ],
        ]);
    }
}

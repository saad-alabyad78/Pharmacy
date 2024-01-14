<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('medicines')->insert([
            [
                'scientific_name' => 'Scientific Name 1',
                'commercial_name' => 'Commercial Name 1',
                'price' => 10,
                'category_id' => 1,
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scientific_name' => 'Scientific Name 2',
                'commercial_name' => 'Commercial Name 2',
                'price' => 15,
                'category_id' => 2,
                'company_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

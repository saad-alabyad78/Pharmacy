<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $tableName = reset($table);
            DB::table($tableName)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->call([
            CompanyTableSeeder::class,
            WarehouseTableSeeder::class,
            CategoriesTableSeeder::class,
            MedicineTableSeeder::class,
            UsersTableSeeder::class,
        ]);
    }
}

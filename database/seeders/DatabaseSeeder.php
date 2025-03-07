<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            MajorSeeder::class,
            InterestSeeder::class,
            SubInterestSeeder::class,
            DivisionSeeder::class,
            SubDivisionSeeder::class,
            UserSeeder::class,
        ]);
    }
}

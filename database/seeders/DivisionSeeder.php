<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = ['CEO', 'COO', 'CTO', 'CMO'];

        foreach ($divisions as $division) {
            DB::table('divisions')->insert([
                'name' => $division,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

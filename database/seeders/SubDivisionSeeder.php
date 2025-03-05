<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubDivisionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID Divisions
        $divisions = DB::table('divisions')->pluck('id', 'name');

        $subDivisions = [
            'COO' => ['Treasurer', 'Secretary'],
            'CTO' => ['Head of Software Development', 'Head of IoT', 'Head of Game Development'],
            'CMO' => ['Content Creator 1', 'Content Creator 2'],
            'Head of Software Development' => ['Mobile Developer', 'UI/UX Designer'],
        ];

        foreach ($subDivisions as $divisionName => $subs) {
            if (isset($divisions[$divisionName])) {
                foreach ($subs as $sub) {
                    DB::table('sub_divisions')->insert([
                        'name' => $sub,
                        'division_id' => $divisions[$divisionName],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}

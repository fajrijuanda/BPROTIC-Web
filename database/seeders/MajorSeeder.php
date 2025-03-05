<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majors = [
            'Teknik Informatika', 'Sistem Informasi', 'Teknik Industri', 
            'Teknik Mesin', 'Hukum', 'PGSD', 'PKN', 'PAI', 'Manajemen', 'Akuntansi', 'Psikologi', 'Farmasi'
        ];

        foreach ($majors as $major) {
            DB::table('majors')->insert([
                'name' => $major,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

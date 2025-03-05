<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestSeeder extends Seeder
{
    public function run(): void
    {
        $interests = ['Internet of Things', 'Software', 'Game'];

        foreach ($interests as $interest) {
            DB::table('interests')->insert([
                'name' => $interest,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

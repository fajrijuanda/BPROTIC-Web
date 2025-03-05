<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubInterestSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil ID Interests
        $interests = DB::table('interests')->pluck('id', 'name');

        $subInterests = [
            'Software' => ['PHP Native', 'Laravel', 'Ionic', 'UI/UX'],
        ];

        foreach ($subInterests as $interestName => $subs) {
            if (isset($interests[$interestName])) {
                foreach ($subs as $sub) {
                    DB::table('sub_interests')->insert([
                        'name' => $sub,
                        'interest_id' => $interests[$interestName],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID Role Super Admin
        $roleId = DB::table('roles')->where('name', 'admin')->value('id');
        
        // Ambil ID Major Teknik Informatika
        $majorId = DB::table('majors')->where('name', 'Teknik Informatika')->value('id');
        
        // Ambil ID Divisi CEO
        $divisionId = DB::table('divisions')->where('name', 'CEO')->value('id');
        
        // Insert User
        $userId = DB::table('users')->insertGetId([
            'username' => 'Admin 1',
            'email' => 'admin1@mail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('#6Jan2001'), // Ganti dengan password yang lebih aman
            'role_id' => $roleId,
            'is_active' => true,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Profile
        DB::table('profiles')->insert([
            'user_id' => $userId,
            'first_name' => 'Fajri Yanuar Shiddiq',
            'last_name' => 'Juanda',
            'bio' => 'Admin of the system.',
            'avatar' => 'default.png',
            'nim' => '22416255201102',
            'class' => 'IF22C',
            'major_id' => $majorId,
            'mobile' => '085217861296',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert User Division
        DB::table('user_divisions')->insert([
            'user_id' => $userId,
            'division_id' => $divisionId,
            'sub_division_id' => null, // Jika ada sub divisi, tambahkan sesuai kebutuhan
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

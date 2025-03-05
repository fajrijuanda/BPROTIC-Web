<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Models\StudentInterest;
use App\Models\UserDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Ambil role_id, default ke Student jika tidak ada input role
        $role = Role::where('name', $request->role ?? 'Student')->firstOrFail();

        // Validasi umum untuk semua role
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:15|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'role' => 'nullable|string|in:Student,Admin,Instructor,Super Admin',
        ]);

        // Validasi khusus Student
        if ($role->name === 'Student') {
            $validator->addRules([
                'class' => 'required|string|max:10',
                'major_id' => 'required|exists:majors,id',
                'nim' => 'required|numeric|unique:profiles,nim',
                'interest_id' => 'required|exists:interests,id',
                'sub_interest_id' => 'nullable|exists:sub_interests,id',
                'reason' => 'required|string',
            ]);
        } else {
            // Validasi untuk Admin, Instructor, dan Super Admin
            $validator->addRules([
                'division_id' => 'required|exists:divisions,id',
                'sub_division_id' => 'nullable|exists:sub_divisions,id',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(), // Kirim semua error dalam format array
            ], 422);
        }


        try {
            // Buat user baru
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role->id,
                'is_active' => false,
            ]);

            // Buat profile
            $profile = Profile::create([
                'user_id' => $user->id,
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'bio' => $request->reason ?? null,
                'avatar' => 'default-avatar.png',
                'nim' => $request->nim, // ✅ Tambahkan ini
                'class' => $request->class, // ✅ Tambahkan ini
                'major_id' => $request->major_id, // ✅ Tambahkan ini
            ]);
            

            if ($role->name === 'Student') {
                // Simpan Interest Student di tabel `student_interests`
                StudentInterest::create([
                    'user_id' => $user->id,
                    'interest_id' => $request->interest_id,
                    'sub_interest_id' => $request->sub_interest_id,
                    'reason' => $request->reason,
                ]);

                // Tambahkan info akademik ke `profiles`
                $profile->update([
                    'nim' => $request->nim,
                    'class' => $request->class,
                    'major_id' => $request->major_id,
                ]);
            } else {
                // Simpan Division untuk Admin/Instructor/Super Admin di `user_divisions`
                UserDivision::create([
                    'user_id' => $user->id,
                    'division_id' => $request->division_id,
                    'sub_division_id' => $request->sub_division_id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil! Silakan menunggu aktivasi admin.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }
}

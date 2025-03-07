<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Models\StudentInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $role = Role::where('name', 'student')->firstOrFail(); // Default user

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users', // ✅ Username dari Social Auth
            'email' => 'required|string|email|max:255|unique:users', // ✅ Email dari Social Auth
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|string',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'class' => 'required|string|max:10',
            'major_id' => 'required|exists:majors,id',
            'nim' => 'required|numeric|unique:profiles,nim',
            'interest_id' => 'required|exists:interests,id',
            'sub_interest_id' => 'nullable|exists:sub_interests,id',
            'reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(), // 🔹 Tambahkan ini untuk melihat error detail
            ], 422);
        }

        try {
            // 🔹 **Buat user dengan username & email dari Social Auth**
            $user = User::create([
                'username' => $request->username, // ✅ Dari Social Auth
                'email' => $request->email, // ✅ Dari Social Auth
                'email_verified_at' => now(),
                'password' => Hash::make($request->password),
                'role_id' => $role->id,
                'is_active' => true,
            ]);

            // ✅ Simpan avatar dari Google jika ada, atau gunakan default
            Profile::create([
                'user_id' => $user->id,
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'bio' => $request->reason ?? null,
                'avatar' => $this->saveGoogleAvatar($request->avatar, $user->id),
                'nim' => $request->nim,
                'class' => $request->class,
                'major_id' => $request->major_id,
                'mobile' => $request->mobile
            ]);

            // 🔹 **Simpan Interest user**
            StudentInterest::create([
                'user_id' => $user->id,
                'interest_id' => $request->interest_id,
                'sub_interest_id' => $request->sub_interest_id,
                'reason' => $request->reason,
            ]);

            // 🔹 **Otomatis Login Setelah Registrasi**
            Auth::login($user);
            $token = $user->createToken('authToken')->plainTextToken;
            $redirectPath = '/apps/academy/dashboard';

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil!',
                'token' => $token,
                'redirect' => $redirectPath,
                'user' => User::getUserWithRelations($user->id),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error_detail' => $e->getMessage(), // 🔹 Tambahkan ini untuk menangkap error
            ], 500);
        }
    }

    public function saveGoogleAvatar($avatarUrl, $userId)
    {
        try {
            // Ambil isi gambar dari URL Google
            $imageContents = file_get_contents($avatarUrl);

            // Tentukan nama file berdasarkan user ID
            $filename = Str::random(10) . '_user_' . $userId . '.jpg';

            // Tentukan lokasi penyimpanan di dalam public/images/avatars
            $filePath = public_path('images/avatars/' . $filename);

            // Simpan langsung ke folder public/images/avatars
            file_put_contents($filePath, $imageContents);

            return $filename; // Simpan path relatif ke database
        } catch (\Exception $e) {
            return 'images/avatars/avatar-1.png'; // Jika gagal, gunakan avatar default
        }
    }
}

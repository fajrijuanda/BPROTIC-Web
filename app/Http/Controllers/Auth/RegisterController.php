<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Models\StudentInterest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $role = Role::where('name', 'client')->firstOrFail(); // Default client

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users', // ✅ Username dari Social Auth
            'email' => 'required|string|email|max:255|unique:users', // ✅ Email dari Social Auth
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
                'password' => bcrypt(uniqid()), // Password acak karena Social Auth
                'role_id' => $role->id,
                'is_active' => true,
            ]);

            // 🔹 **Buat Profile client**
            Profile::create([
                'user_id' => $user->id,
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'bio' => $request->reason ?? null,
                'avatar' => 'default-avatar.png',
                'nim' => $request->nim,
                'class' => $request->class,
                'major_id' => $request->major_id,
                'mobile' => $request->mobile
            ]);

            // 🔹 **Simpan Interest client**
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
                'message' => 'Registrasi berhasil! Anda akan diarahkan ke dashboard.',
                'token' => $token,
                'redirect' => $redirectPath,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error_detail' => $e->getMessage(), // 🔹 Tambahkan ini untuk menangkap error
            ], 500);
        }
    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // 2. Cek kredensial
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = Auth::user();

        // 3. Cek apakah akun aktif
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun belum diaktifkan oleh admin',
            ], 403);
        }

        // 4. Load relasi role
        $user = User::getUserWithRole($user->id);

        // 5. Buat token
        $token = $user->createToken('authToken')->plainTextToken;

        // 6. Tentukan URL tujuan berdasarkan role_id
        $redirectUrl = '/dashboards/crm';

        if ($user->role_id == 2 || $user->role_id == 3) {
            $redirectUrl = '/apps/academy/dashboard';
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => $user,
            'accessToken' => $token,
            'redirect' => $redirectUrl, // Kirim URL tujuan ke frontend
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }
}

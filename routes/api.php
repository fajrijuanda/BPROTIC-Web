<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialAuthController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Route untuk mendapatkan user yang sudah login (harus pakai token)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});


Route::middleware(['web'])->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect']);
    Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback']);
});

Route::get('/auth/social-user', [SocialAuthController::class, 'getSocialUserData']);

// Route untuk daftar jurusan
Route::get('/majors', [MajorController::class, 'index']);

// Route untuk register
Route::post('/register', [RegisterController::class, 'register']);

// ✅ Tambahkan Route untuk Login
Route::post('/login', [LoginController::class, 'login']);
Route::get('/sanctum/csrf-cookie', function () {
    return response()->noContent();
});



// ✅ Tambahkan Route untuk Logout
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

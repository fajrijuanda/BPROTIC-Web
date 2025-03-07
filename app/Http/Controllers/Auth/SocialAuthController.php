<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider, Request $request)
    {
        $frontendUrl = config('app.frontend_url', 'http://localhost:3000');
        try {
            // Ambil data user dari provider sosial
            $socialUser = Socialite::driver($provider)->stateless()->user();
            Log::info("Social Auth Callback: Data user dari {$provider}", [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar(),
            ]);
            
            if (!$socialUser->getEmail() || !$socialUser->getName()) {
                throw new \Exception("Social data invalid: email atau username kosong!");
            }

            // Cari user berdasarkan email
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                Log::info("Social Auth: User belum terdaftar, arahkan ke registrasi.", [
                    'email' => $socialUser->getEmail(),
                ]);

                return redirect("{$frontendUrl}/register-multi-steps?username=" . urlencode($socialUser->getName()) . "&email=" . urlencode($socialUser->getEmail()) . "&avatar=" . urlencode($socialUser->getAvatar()));
            }

            // Login user dan buat token
            Auth::login($user);
            $token = $user->createToken('authToken')->plainTextToken;

            Log::info("Social Auth: User berhasil login", [
                'user_id' => $user->id,
                'email' => $user->email,
                'token' => $token,
            ]);

            /// 🔹 Ambil data lengkap user dengan relasi
            $userData = User::getUserWithRelations($user->id);

            // 🔹 Set cookie dengan token & userData
            $cookieToken = Cookie::make('accessToken', $token, 60 * 24 * 30, '/', null, false, true); // HttpOnly cookie
            $cookieUserData = Cookie::make('userData', json_encode($userData), 60 * 24 * 30, '/', null, false, false); // Bisa diakses client-side

            // 🔹 Set userAbilityRules
            $userAbilityRules = $userData->role->name ?? '';
            $cookieAbility = Cookie::make('userAbilityRules', $userAbilityRules, 60 * 24 * 30, '/', null, false, false);

            // Redirect sesuai role user
            $redirectPath = match ($user->role_id) {
                1 => '/dashboards/crm',
                2, 3 => '/apps/academy/dashboard',
                default => '/login',
            };

           // 🔹 Redirect ke frontend dengan cookies
           return redirect("{$frontendUrl}{$redirectPath}")
           ->cookie($cookieToken)
           ->cookie($cookieUserData)
           ->cookie($cookieAbility);

        } catch (\Exception $e) {
            Log::error("Social Auth Error dengan {$provider}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal login dengan ' . ucfirst($provider) . ': ' . $e->getMessage(),
            ], 500);
        }
    }
    // 🔹 **Buat API untuk Mengambil Data dari Session**
    public function getSocialUserData(Request $request)
    {
        return response()->json([
            'session_data' => session('socialUserData'),
            'all_sessions' => session()->all(),
            'cookies' => $request->cookies->all(),
            'session_id' => session()->getId(),
        ]);
    }


}

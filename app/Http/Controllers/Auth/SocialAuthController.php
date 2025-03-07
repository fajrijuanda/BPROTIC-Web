<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log; // Tambahkan di atas

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider, Request $request)
    {
        $appUrl = config('app.url', 'http://localhost:8000');

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            if (!$socialUser->getEmail() || !$socialUser->getName()) {
                throw new \Exception("Social data invalid: email atau username kosong!");
            }

            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                return redirect("{$appUrl}/register-multi-steps?username=" . urlencode($socialUser->getName()) . "&email=" . urlencode($socialUser->getEmail()));
            }

            Auth::login($user);
            $token = $user->createToken('authToken')->plainTextToken;
            $redirectPath = match ($user->role_id) {
                1 => '/dashboards/crm',
                2, 3 => '/apps/academy/dashboard',
                default => '/login',
            };

            return redirect("{$appUrl}{$redirectPath}?token={$token}");

        } catch (\Exception $e) {
            return redirect("{$appUrl}/login")
                ->with('error', 'Gagal login dengan ' . $provider . ': ' . $e->getMessage());
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

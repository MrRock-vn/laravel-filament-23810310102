<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAuthService;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialAuthController extends Controller
{
    protected SocialAuthService $socialAuthService;

    public function __construct(SocialAuthService $socialAuthService)
    {
        $this->socialAuthService = $socialAuthService;
    }

    public function redirect(string $provider): RedirectResponse
    {
        if (! in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')->with('error', 'Provider không hợp lệ.');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        try {
            if (! in_array($provider, ['google', 'facebook'])) {
                return redirect()->route('login')->with('error', 'Provider không hợp lệ.');
            }

            $socialUser = Socialite::driver($provider)->user();

            $this->socialAuthService->handleSocialLogin($socialUser, $provider);

            return redirect()->route('dashboard')
                ->with('success', 'Đăng nhập thành công bằng ' . ucfirst($provider) . '.');
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'Đăng nhập thất bại, bị từ chối quyền hoặc token không hợp lệ.');
        }
    }

    public function logout(): RedirectResponse
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đã đăng xuất.');
    }
}
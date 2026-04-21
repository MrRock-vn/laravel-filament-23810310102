<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialAuthService
{
    public function handleSocialLogin(SocialiteUser $socialUser, string $provider): User
    {
        $email = $socialUser->getEmail();
        $providerId = $socialUser->getId();
        $name = $socialUser->getName() ?: $socialUser->getNickname() ?: 'Người dùng';
        $avatar = $socialUser->getAvatar();

        $user = null;

        // Nếu có email thì ưu tiên tìm theo email
        if ($email) {
            $user = User::where('email', $email)->first();
        }

        // Nếu chưa có thì tìm theo provider + provider_id
        if (! $user) {
            $user = User::where('provider', $provider)
                ->where('provider_id', $providerId)
                ->first();
        }

        // Nếu chưa có tài khoản thì tạo mới
        if (! $user) {
            $user = User::create([
                'name' => $name,
                'email' => $email ?: $provider . '_' . $providerId . '@example.com',
                'password' => bcrypt(Str::random(16)),
                'student_id' => '23810310102',
                'avatar' => $avatar,
                'provider' => $provider,
                'provider_id' => $providerId,
                'google_id' => $provider === 'google' ? $providerId : null,
                'facebook_id' => $provider === 'facebook' ? $providerId : null,
            ]);
        } else {
            // Nếu đã có thì cập nhật thêm thông tin mới nhất
            $user->update([
                'name' => $name ?: $user->name,
                'avatar' => $avatar ?: $user->avatar,
                'provider' => $provider,
                'provider_id' => $providerId,
                'student_id' => $user->student_id ?: '23810310102',
                'google_id' => $provider === 'google' ? $providerId : $user->google_id,
                'facebook_id' => $provider === 'facebook' ? $providerId : $user->facebook_id,
            ]);
        }

        Auth::login($user, true);

        return $user;
    }
}
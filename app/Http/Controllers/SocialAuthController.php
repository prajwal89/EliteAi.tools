<?php

namespace App\Http\Controllers;

use App\Enums\ProviderType;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialAuthController extends Controller
{
    public function loginPage()
    {
        return view('auth.login-page');
    }

    public function socialRedirect($social): RedirectResponse
    {
        if (!in_array($social, ProviderType::values())) {
            abort('404');
        }

        return Socialite::driver($social)->redirect();
    }

    public function socialCallback($social, UserService $userService): RedirectResponse
    {
        if (!in_array($social, ProviderType::values())) {
            abort('404');
        }

        try {
            $socialUser = Socialite::driver($social)->user();
        } catch (InvalidStateException $e) {
            Log::error($e->getMessage());

            return redirect()->route('auth.login');
        } catch (Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('auth.login');
        }

        $oldUser = User::where('email', $socialUser->email)
            ->where('provider_type', $social)
            ->where('provider_id', $socialUser->getId())
            ->first();

        // login old user
        if (!empty($oldUser)) {
            auth()->login($oldUser, true);

            return redirect(route('user.dashboard'));
        }

        $newUserData['email'] = $socialUser->getEmail();
        $newUserData['name'] = $socialUser->getName();
        $newUserData['avatar_url'] = $socialUser->getAvatar();
        $newUserData['provider_type'] = $social;
        $newUserData['provider_id'] = $socialUser->getId();

        $newUser = $userService->store($newUserData);

        auth()->login($newUser, true);

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}

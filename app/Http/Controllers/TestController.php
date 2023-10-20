<?php

namespace App\Http\Controllers;

/**
 * For testing out misc things
 */
class TestController extends Controller
{
    public function __construct()
    {
        if (app()->isProduction()) {
            abort(404);
        }
    }

    public function __invoke()
    {
        // code...
        // auth()->login(\App\Models\User::find(1));

        return $this->loginSuperAdmin();
    }

    public function loginSuperAdmin()
    {
        if (app()->isProduction()) {
            abort(404);
        }

        auth()->login(
            \App\Models\User::where('email', '00prajwal@gmail.com')
                ->where('provider_type', \App\Enums\ProviderType::GOOGLE->value)
                ->first()
        );
    }
}

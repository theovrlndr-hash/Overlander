<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    private array $allowed = ['google', 'facebook'];

    public function redirect(string $provider)
    {
        abort_if(! in_array($provider, $this->allowed), 404);
        session(['social_intent' => request('intent', 'login')]);

        $driver = Socialite::driver($provider);

        if ($provider === 'google') {
            $driver->with(['prompt' => 'select_account']);
        }

        return $driver->redirect();
    }

    public function callback(string $provider)
    {
        abort_if(! in_array($provider, $this->allowed), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', __('flash.login_failed', ['error' => $e->getMessage()]));
        }

        $idField = match($provider) {
            'facebook' => 'facebook_id',
            default    => 'google_id',
        };

        $intent = session()->pull('social_intent', 'login');

        $userBySocialId = User::where($idField, $socialUser->getId())->first();
        $userByEmail    = $userBySocialId ?? User::where('email', $socialUser->getEmail())->first();

        if ($intent === 'register') {
            if ($userByEmail) {
                return redirect()->route('login')
                    ->with('error', __('flash.account_already_registered', ['email' => $socialUser->getEmail()]));
            }

            $user = User::create([
                'name'     => $socialUser->getName(),
                'email'    => $socialUser->getEmail(),
                $idField   => $socialUser->getId(),
                'password' => bcrypt(Str::random(24)),
            ]);
        } else {
            if (! $userByEmail) {
                return redirect()->route('register')
                    ->with('error', __('flash.account_not_found'));
            }

            $user = $userByEmail;

            if (! $userBySocialId) {
                $user->update([$idField => $socialUser->getId()]);
            }
        }

        Auth::login($user, true);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home')
            ->with('success', __('flash.welcome', ['name' => $user->name]));
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, ['google', 'github']), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        abort_unless(in_array($provider, ['google', 'github']), 404);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['oauth' => 'Connexion annulée ou erreur. Réessaie.']);
        }
        // Cherche d'abord par provider + provider_id (retour d'un user existant)
        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        // Sinon cherche par email (user déjà créé sans OAuth ou avec un autre provider)
        if (! $user) {
            $user = User::firstOrNew(['email' => $socialUser->getEmail()]);
        }

        // Met à jour les infos OAuth et sauvegarde
        $user->name = $socialUser->getName();
        $user->provider = $provider;
        $user->provider_id = $socialUser->getId();
        $user->save();

        Auth::login($user, remember: true);

        return redirect()->intended('/dashboard');
    }
}

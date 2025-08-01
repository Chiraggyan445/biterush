<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->with(['prompt' => 'select_account']) 
            ->redirect();
    }

    /**
     * Handle callback from Google.
     */
   public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::where('email', $googleUser->getEmail())->first();

    if ($user) {
        $user->update([
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'role' => 'customer', 
        ]);
    } else {
        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'password' => bcrypt(Str::random(24)),
            'google_id' => $googleUser->getId(),
            'role' => 'customer',
        ]);
    }

    Auth::login($user);
    session(['google_avatar' => $googleUser->getAvatar()]);

    return redirect('/home')->with('success', 'Logged in with Google');
}

}

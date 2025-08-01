<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{

    public function login()
    {
        return view('modals.login');
    }

    public function postLogin(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user->role === 'admin') {
            Auth::logout();
            return redirect('/login')->withErrors([
                'login' => 'Admins must use the admin panel to login.',
            ]);
        }

        return redirect()->intended('/home')->with('success', 'Login successful');
    }

    return redirect()->back()
        ->withErrors(['login' => 'Invalid credentials'])
        ->withInput();
}




   public function postRegistration(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:40',
        'email' => 'required|string|email|max:50|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('form', 'registration'); 
    }

    $user = User::create([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
    ]);

    return redirect('modals.login')->with('success', 'Registration successful! You can now log in.');
}

public function logout(Request $request){
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    session()->forget('google_avatar');

    return redirect('/');
}
}
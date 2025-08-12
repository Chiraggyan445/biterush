<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Redirect to homepage and trigger login modal
     */
    public function login()
    {
        return redirect('/home')->with('login_required', true);
    }

    /**
     * Handle user login
     */
    public function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Prevent admins from logging in via customer login
            if ($user->role === 'admin') {
                Auth::logout();
                return redirect('/home')->withErrors([
                    'login' => 'Admins must use the admin panel to login.',
                ])->with('login_required', true);
            }

            return redirect()->intended('/home')->with('success', 'Login successful');
        }

        return redirect('/home')
            ->withErrors(['login' => 'Invalid credentials'])
            ->withInput()
            ->with('login_required', true);
    }

    /**
     * Handle user registration
     */
    public function postRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:40',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect('/home')
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form' => 'registration',
                    'login_required' => true
                ]);
        }

        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role'     => 'customer',
        ]);

        return redirect('/home')->with([
            'success' => 'Registration successful! You can now log in.',
            'login_required' => true
        ]);
    }

    /**
     * Logout the user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->forget('google_avatar');

        return redirect('/home')->with('success', 'Logged out successfully');
    }
}

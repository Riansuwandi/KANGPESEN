<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            session([
                'user_role' => Auth::user()->role,
                'login_time' => now(),
                'last_activity' => now(),
            ]);

            if ($remember) {
                Cookie::queue('remember_user', Auth::user()->username, 60 * 24 * 30); // 30 days
            }

            $this->logActivity('User logged in', Auth::user());

            return redirect()->intended('/')->with('success', 'Login berhasil! Selamat datang, ' . Auth::user()->username);
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|unique:users|min:3|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:staff,tamu',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Auth::login($user);

        session([
            'user_role' => $user->role,
            'login_time' => now(),
            'last_activity' => now(),
            'is_new_user' => true,
        ]);

        $this->logActivity('User registered', $user);

        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->username);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $this->logActivity('User logged out', $user);
        }

        $request->session()->forget(['user_role', 'login_time', 'current_order_id', 'cart_updated']);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget('remember_user'));

        return redirect('/')->with('success', 'Logout berhasil!');
    }

    private function logActivity($activity, $user)
    {
        \Illuminate\Support\Facades\Log::info($activity . ' - User: ' . $user->username . ' (' . $user->role . ') at ' . now());
    }
}

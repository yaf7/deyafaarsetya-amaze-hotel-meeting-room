<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login sebagai admin, redirect ke dashboard
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::guard('web')->attempt($credentials, true)) {
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();
            if ($user && $user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Jika bukan admin, logout & beri error
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors(['username' => 'Akun ini tidak memiliki akses admin.']);
        }

        return back()->withErrors(['username' => 'Kredensial tidak valid.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

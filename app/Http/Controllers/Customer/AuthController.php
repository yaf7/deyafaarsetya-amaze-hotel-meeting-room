<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Jika sudah login sebagai customer, redirect ke home
        if (Auth::guard('customer')->check()) {
            return redirect()->route('home');
        }

        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Logout admin guard dulu kalau ada, supaya tidak bentrok session
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('customer')->attempt($credentials)) {
            $request->session()->regenerate();

            // Selalu redirect ke home, jangan pakai intended() karena bisa
            // redirect ke admin dashboard jika sebelumnya pernah akses admin
            return redirect()->route('home')->with('success', 'Berhasil masuk.');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi salah.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:customers'],
            'phone' => ['required', 'string', 'max:20'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company_name' => $request->company_name,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('customer')->login($customer);

        return redirect()->route('home')->with('success', 'Registrasi berhasil. Selamat datang!');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        // Hapus session key customer saja, jangan invalidate seluruh session
        // agar tidak mempengaruhi admin session jika ada
        $request->session()->forget('login_customer_' . sha1('App\Models\Customer'));
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil keluar.');
    }
}

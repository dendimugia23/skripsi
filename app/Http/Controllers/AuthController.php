<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan file login ada di resources/views/auth/login.blade.php
    }

    /**
     * Proses login.
     */
    public function processLogin(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah "remember me" dicentang
        $remember = $request->has('remember');

        // Autentikasi
        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            $user = Auth::user();

            // Redirect berdasarkan role
            if ($user->role === 'super_admin') {
                return redirect()->route('superadmin.dashboard')->with('success', 'Login berhasil sebagai Super Admin.');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Login berhasil sebagai Admin.');
            }

            // Default redirect jika role tidak dikenali
            return redirect()->route('home')->with('success', 'Login berhasil.');
        }

        // Jika autentikasi gagal, kembalikan dengan pesan error
        return back()
            ->withInput($request->only('email')) // Mempertahankan input email
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    /**
     * Logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus semua sesi dan regenerasi token untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}

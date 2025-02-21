<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    function loginpage()
    {
        return view('indexLoginPage'); 
    }

    // Proses login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email', 
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Jika autentikasi gagal, kembali ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email yang Anda masukkan salah.',
            'password' => 'Password yang Anda masukkan salah'
        ])->onlyInput('Email');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerasi token CSRF
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
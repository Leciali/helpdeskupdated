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

    // Session
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email', 
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

     
        return back()->withErrors([
            'email' => 'Email yang Anda masukkan salah.',
            'password' => 'Password yang Anda masukkan salah'
        ])->onlyInput('Email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
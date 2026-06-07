<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Menampilkan halaman form login
    public function showLogin()
    {
        return view('login'); // Nanti kita buat file login.blade.php
    }

    // 2. Memproses data dari form login
    public function login(Request $request)
    {
        // Validasi inputan form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login dengan Auth::attempt
        if (Auth::attempt($credentials)) {
            // Jika berhasil, perbarui sesi agar aman dari serangan hacker
            $request->session()->regenerate();

            // Cek role user yang baru saja login
            $role = Auth::user()->role;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'kasir') {
                return redirect()->route('kasir.pos');
            }
        }

        // Jika email/password salah, kembalikan ke halaman login bawa pesan error
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email'); // Biar emailnya gak usah diketik ulang
    }

    // 3. Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); 
    }
}

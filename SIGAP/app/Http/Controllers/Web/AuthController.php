<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ─── REGISTER ────────────────────────────────────────────────

    public function showRegister()
    {
        // Redirect ke home jika sudah login
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth', ['mode' => 'register']);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'], // di-hash otomatis via model casts
            'role'     => 'user',
        ]);

        $user->sendEmailVerificationNotification();
        
        Auth::login($user);

        return redirect()
            ->route('home')
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }

    // ─── LOGIN ────────────────────────────────────────────────────

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth', ['mode' => 'login']);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // remember me (opsional, sesuaikan form jika perlu)
        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        // Regenerate session untuk mencegah session fixation
        $request->session()->regenerate();

        // Redirect ke URL yang semula dituju (jika ada), fallback ke home
        return redirect()
            ->intended(route('home'))
            ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
    }

    // ─── LOGOUT ───────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}

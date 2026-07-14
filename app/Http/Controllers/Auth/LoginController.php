<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * ==========================================================
     * FORM LOGIN ADMIN
     * ==========================================================
     */
    public function index()
    {
        if (Auth::check()) {

            if (Auth::user()->role == 1) {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();

            session()->invalidate();
            session()->regenerateToken();
        }

        return view('auth.login');
    }

    /**
     * ==========================================================
     * PROSES LOGIN
     * ==========================================================
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only([
            'username',
            'password',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {

            return back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'username' => 'Username atau Password salah.',
                ]);

        }

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Hanya Super Admin
        |--------------------------------------------------------------------------
        */

        if (Auth::user()->role != 1) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'username' => 'Akun ini bukan Administrator.',
            ]);

        }

        return redirect()
            ->route('admin.dashboard')
            ->with(
                'success',
                'Selamat datang Administrator.'
            );
    }

    /**
     * ==========================================================
     * LOGOUT
     * ==========================================================
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
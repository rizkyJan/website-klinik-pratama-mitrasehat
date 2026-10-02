<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login admin.
     */
    public function showLogin()
    {
        /*
        |--------------------------------------------------------------------------
        | Kalau sudah login sebagai admin
        |--------------------------------------------------------------------------
        */

        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Kalau login sebagai user biasa
        |--------------------------------------------------------------------------
        |
        | Jangan izinkan user biasa dianggap sebagai admin.
        |
        */

        if (Auth::check() && ! Auth::user()->is_admin) {
            Auth::logout();
        }

        return view('admin.login');
    }

    /**
     * Proses login admin.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Hanya akun admin yang boleh login
        |--------------------------------------------------------------------------
        */

        $credentials['is_admin'] = true;

        /*
        |--------------------------------------------------------------------------
        | Coba login
        |--------------------------------------------------------------------------
        */

        if (Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {

            /*
            |--------------------------------------------------------------------------
            | Regenerate session
            |--------------------------------------------------------------------------
            |
            | Untuk mencegah session fixation.
            |
            */

            $request->session()->regenerate();

            return redirect()
                ->intended(route('admin.dashboard'));
        }

        /*
        |--------------------------------------------------------------------------
        | Login gagal
        |--------------------------------------------------------------------------
        */

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah, atau akun bukan admin.');
    }

    /**
     * Logout admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

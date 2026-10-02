<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Belum login
        |--------------------------------------------------------------------------
        */

        if (! auth()->check()) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        /*
        |--------------------------------------------------------------------------
        | Sudah login tapi bukan admin
        |--------------------------------------------------------------------------
        */

        if (! auth()->user()->is_admin) {

            auth()->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->with('error', 'Akun ini tidak memiliki akses admin.');
        }

        /*
        |--------------------------------------------------------------------------
        | Admin valid
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}

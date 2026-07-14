<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    /**
     * ==========================================================
     * SUPER ADMIN AUTHORIZATION
     * ==========================================================
     *
     * Middleware ini digunakan untuk membatasi seluruh area
     * Administrator Platform CoreERP.
     *
     * Yang diizinkan mengakses:
     * - Super Admin
     *
     * Yang ditolak:
     * - Guest
     * - User biasa
     * - Admin Company
     *
     * Seluruh route Admin Center wajib menggunakan middleware ini.
     */

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------
        | 1. Pastikan User Sudah Login
        |--------------------------------------------------
        */
        if (!auth()->check()) {
            abort(403);
        }

        /*
        |--------------------------------------------------
        | 2. Pastikan User Adalah Super Admin
        |--------------------------------------------------
        */
        if ((int) auth()->user()->role !== 1) {
            abort(403);
        }

        /*
        |--------------------------------------------------
        | 3. Lanjutkan Request
        |--------------------------------------------------
        */
        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotKaryawan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Tentukan guard yang digunakan, default ke 'employee'
        $guard = 'employee'; // Karena ini middleware untuk karyawan

        // Cek apakah pengguna terautentikasi sebagai karyawan
        if (!Auth::guard($guard)->check()) {
            // Jika tidak, redirect ke halaman login karyawan
            // Ganti 'login.karyawan' dengan nama route login karyawan Anda
            return redirect()->route('login');
        }
        
        return $next($request);
    }
}

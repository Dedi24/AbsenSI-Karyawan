<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user login dan rolenya sesuai
        if (!$request->user() || $request->user()->role !== $role) {
            // Redirect atau abort jika tidak sesuai
            if ($request->user()) {
                 if ($request->user()->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif ($request->user()->role === 'karyawan') {
                    return redirect()->route('karyawan.dashboard');
                }
            }
            // Jika tidak login atau role tidak dikenali
            return redirect('/'); // Atau abort(403);
        }

        return $next($request);
    }
}
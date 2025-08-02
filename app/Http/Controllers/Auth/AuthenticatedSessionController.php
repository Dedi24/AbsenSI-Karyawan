<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Pastikan kolom ada di tabel users
    if (\Schema::hasColumn('users', 'last_login_at') &&
        \Schema::hasColumn('users', 'last_login_ip')) {
         $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            // Tambahkan device_fingerprint jika diperlukan dan kolom tersedia
            // 'device_fingerprint' => $this->generateDeviceFingerprint($request),
        ]);


        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

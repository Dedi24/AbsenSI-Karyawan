<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Update device fingerprint dan login info
            $deviceFingerprint = $this->generateDeviceFingerprint($request);
            $user->update([
                'last_login_ip' => $request->ip(),
                'last_login_at' => now(),
                'device_fingerprint' => $user->device_fingerprint ?? $deviceFingerprint
            ]);

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif (Auth::user()->isKaryawan()) {
                return redirect()->intended(route('karyawan.dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    protected function generateDeviceFingerprint(Request $request)
    {
        $userAgent = $request->userAgent();
        $ipAddress = $request->ip();
        $acceptLanguage = $request->header('Accept-Language');
        
        $deviceInfo = $userAgent . $ipAddress . $acceptLanguage;
        return hash('sha256', $deviceInfo);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

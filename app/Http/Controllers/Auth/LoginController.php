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
        $user = Auth::user();

        $credentials = $request->only('email', 'password');

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->role === 'karyawan') {
                return redirect()->intended('/karyawan/dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Anda tidak memiliki akses.']);
            }
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'karyawan') {
            return redirect('/karyawan/dashboard');
        }

        return redirect('/');
    }
        
        protected function generateDeviceFingerprint(Request $request)
        {
            $userAgent = $request->userAgent();
            $ipAddress = $request->ip();
            $acceptLanguage = $request->header('Accept-Language');
            
            // Update device fingerprint dan login info
            $deviceFingerprint = $this->generateDeviceFingerprint($request);
            $user->update([
                'last_login_ip' => $request->ip(),
                'last_login_at' => now(),
                'device_fingerprint' => $user->device_fingerprint ?? $deviceFingerprint
            ]);

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

    // Method untuk menangani offline
    public function offline()
    {
        return view('pwa.offline');
    }
}

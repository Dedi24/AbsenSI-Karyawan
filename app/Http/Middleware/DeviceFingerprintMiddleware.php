<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DeviceFingerprintMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $deviceToken = $request->header('X-Device-Token') ?? $request->get('device_token');
            $deviceFingerprint = $this->generateDeviceFingerprint($request);

            // Jika user sudah memiliki device token, validasi
            if ($user->device_token && $deviceToken !== $user->device_token) {
                return response()->json([
                    'error' => 'Unauthorized device'
                ], 403);
            }

            // Jika user belum memiliki device token, simpan
            if (!$user->device_token && $deviceToken) {
                $user->update(['device_token' => $deviceToken]);
            }

            // Cek apakah device diizinkan
            if (!$user->isDeviceAllowed($deviceFingerprint)) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Device tidak diizinkan. Silakan login dari device yang terdaftar.');
            }
        }

        return $next($request);
    }

    protected function generateDeviceFingerprint(Request $request)
    {
        $userAgent = $request->userAgent();
        $ipAddress = $request->ip();
        $acceptLanguage = $request->header('Accept-Language');

        // Gabungkan informasi device
        $deviceInfo = $userAgent . $ipAddress . $acceptLanguage;

        // Generate hash
        return hash('sha256', $deviceInfo);
    }
}

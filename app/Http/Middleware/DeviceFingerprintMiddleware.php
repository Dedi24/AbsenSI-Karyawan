<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeviceFingerprintMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $deviceToken = $request->header('X-Device-Token') ?? $request->get('device_token');

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
        }

        return $next($request);
    }
}

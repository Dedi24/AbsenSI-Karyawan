<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_token' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // Simpan device token
            $user->update(['device_token' => $request->device_token]);

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('mobile-app')->plainTextToken
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}

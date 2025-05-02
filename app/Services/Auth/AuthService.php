<?php

namespace App\Services\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AuthService {
    public function login(FormRequest $request) {
        $credentials = $request->only('email', 'password');
        try {
            if ($token = auth()->attempt($credentials)) {
                return $this->respondWithToken($token);
            } else {
                return response()->json(["message" => 'User not found'], 404);
            }

        } catch (\Exception $exception) {
            return response()->json(["message" => $exception->getMessage()], 500);
        }
    }

    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me() {
        return response()->json(auth()->user());
    }

    protected function respondWithToken($token) {
        $user = auth()->user();
        $ttl = config('jwt.ttl') * 600;

        return response()->json([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'expires_in' => $ttl,
            'token' => (string)$token,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // sudah di-hash otomatis dari model casts
            'role' => 'user'
        ]);

        // JWT generate
        $token = auth('api')->login($user);

        return response()->json([
            'success' => true,
            'message' => 'Register berhasil',
            'token' => $token,
            'token_type' => 'bearer',
            'user' => $user
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'token_type' => 'bearer',
            'user' => auth('api')->user()
        ]);
    }

    public function me()
    {
        return response()->json([
            'success' => true,
            'user' => auth('api')->user()
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'success' => true,
            'token' => auth('api')->refresh(),
            'token_type' => 'bearer'
        ]);
    }
}
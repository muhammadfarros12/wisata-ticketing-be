<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', operator: $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'The provided credentials are incorrect.'
                ],
                401
            );
        }

        $token = $user->createToken(name: 'auth_token')->plainTextToken;
        return response()->json(
            [
                'status' => 'success',
                'message' => 'User logged in successfully',
                'data' => [
                    'user' => $user,
                    'access_token' => $token,
                ]
            ],
            200
        );


    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'User registered successfully',
                'user' => $user,
            ],
            201
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'User logged out successfully',
            ],
            200
        );
    }

}

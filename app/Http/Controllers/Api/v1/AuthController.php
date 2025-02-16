<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth:sanctum'], only: ['logout','user'])
        ];
    }
    
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'Welcome! Your account has been created successfully.',
            'data' => [
                'user' => $user->only([
                    'name',
                    'email'
                ]),
                'token' => $token
            ]
        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'status' => 422,
                'errors' => [
                    'email' => ['The email provided is not registered.']
                ],
            ], 422);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 422,
                'errors' => [
                    'password' => ['The provided credentials are incorrect.']
                ],
            ], 422);
        }

        $token = $user->createToken($user->email)->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'Welcome! You are now logged in.',
            'data' => [
                'user' => $user->only([
                    'name',
                    'email'
                ]),
                'token' => $token
            ]
        ], 200);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'You have successfully logged out.'
        ]);
    }

    public function user(Request $request) {
        return response()->json([
            'status' => 200,
            'message' => 'Data fetched successfully.',
            'data' => $request->user()->only(['name','email'])
        ], 200);
    }
}

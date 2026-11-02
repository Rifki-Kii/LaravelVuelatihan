<?php

namespace App\Presentation\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Presentation\Http\Requests\V1\LoginRequest;
use App\Presentation\Http\Requests\V1\RegisterRequest;
use App\Presentation\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        Auth::login($user);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'Registrasi berhasil',
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return response()->json([
                'data' => null,
                'message' => 'Email atau kata sandi salah',
            ], 401);
        }

        $request->session()->regenerate();

        return response()->json([
            'data' => new UserResource(Auth::user()),
            'message' => 'Login berhasil',
        ], 200);
    }

    public function logout(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['data' => null, 'message' => 'Tidak terautentikasi'], 401);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'data' => null,
            'message' => 'Logout berhasil',
        ], 200);
    }

    public function me(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['data' => null, 'message' => 'Tidak terautentikasi'], 401);
        }

        return response()->json([
            'data' => new UserResource(Auth::user()),
            'message' => 'Data pengguna',
        ], 200);
    }
}

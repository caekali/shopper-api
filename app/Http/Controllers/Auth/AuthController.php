<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return $this->errorResponse('Account Not Found', null, 404);
        }

        return $this->successResponse(code: 200);

    }

    public function register(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',

        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return response()->json([
            'message' => 'Account Register Successfully',
        ], 201);
    }

    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = Auth::user();

            return $this->successResponse([
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken,
            ]);

        }

        return $this->errorResponse('Bad credentials', null, 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->accessToken->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $input = $request->validate([
            'username' => ['required', 'max:255'],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'phone' => ['nullable', 'max:15', 'unique:users'],
            'email' => ['nullable', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $input['email_verified_at'] = now();

        $response = AuthService::login(User::create($input));

        return $this->jsonOk($response, 201, 'User has been registered successfully.');
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required|max:255',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return $this->jsonError(null, 401, 'Unauthorized');
        }

        $response = AuthService::login(Auth::user());

        return $this->jsonOk($response, 200, 'User has been logged successfully.');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user(); // auth()->user();
        return $this->jsonOk($user, 200, 'Authenticated use info.');
    }

    public function logout(Request $request): JsonResponse
    {
        AuthService::logout($request->user());
        return $this->jsonOk([], 200, 'Logged out successfully.');
    }
}
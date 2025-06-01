<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class AuthService
{
    public static function oauthToken($payload)
    {
        return Http::post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
            'scope' => '*',
            ...$payload,
        ]);
    }

    public static function login($user, $rememberMe = false)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($rememberMe) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        $token->save();

        return [
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $tokenResult->accessToken,
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
        ];
    }

    public static function logout($user)
    {
//        auth()->user()->tokens()->delete();
        return $user->token()->revoke();
    }

}
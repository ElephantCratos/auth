<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use App\Services\TokenService;
use Illuminate\Http\Request;

class EmailPasswordAuthStrategy implements AuthStrategyInterface
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Неправильная почта или пароль'], 401);
        }

        $user = Auth::user();

        $accessToken = $this->tokenService->generateAccessToken($user->id);
        $refreshToken = $this->tokenService->generateRefreshToken($user->id);

        return redirect()->route('userInfo')
            ->withCookies([
                cookie('access_token', $accessToken->token, $accessToken->expires_at, '/', null, true, true),
                cookie('refresh_token', $refreshToken->token, $refreshToken->expires_at, '/', null, true, true),
            ]);
    }
}
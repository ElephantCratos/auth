<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthStrategyFactory;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $tokenService;

    protected $authStrategyFactory;

    public function __construct(TokenService $tokenService, AuthStrategyFactory $authStrategyFactory)
    {
        $this->tokenService = $tokenService;
        $this->authStrategyFactory = $authStrategyFactory;
    }

    public function login(Request $request)
    {
        $authType = $request->input('auth_type', 'email'); 
        $authStrategy = $this->authStrategyFactory->make($authType);

        return $authStrategy->login($request);
       
    }

    public function logout(Request $request)
    {
        $accessToken = $request->cookie('access_token');
        $refreshToken = $request->cookie('refresh_token');

        $this->tokenService->revokeToken($accessToken);
        $this->tokenService->revokeToken($refreshToken);

        return response()
            ->json(['message' => 'Successfully logged out'])
            ->withCookie(cookie()->forget('access_token'))
            ->withCookie(cookie()->forget('refresh_token'));
    }

    public function telegramAuthCallback(Request $request)
    {
        return $this->login($request->merge(['auth_type' => 'telegram']));
    }

    
}

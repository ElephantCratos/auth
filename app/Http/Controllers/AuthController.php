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
    /**
     * Логин пользователя с использованием указанной стратегии аутентификации.
     * 
     * @param Request $request 
     * 
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *         
    */
    public function login(Request $request)
    {
        $authType = $request->input('auth_type', 'email'); 
        $authStrategy = $this->authStrategyFactory->make($authType);

        return $authStrategy->login($request);
       
    }

    /**
     * Логаут пользователя, аннулирование access и refresh токенов.
     * 
     * @param \Illuminate\Http\Request $request Входящий HTTP-запрос, содержащий токены из куков.
     * 
     * @return \Illuminate\Http\JsonResponse.
    */
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

    /**
     * Обработка колбэка авторизации через Telegram, вызывающая логин с Telegram-стратегией.
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *         
    */
    public function telegramAuthCallback(Request $request)
    {
        return $this->login($request->merge(['auth_type' => 'telegram']));
    }

    /**
     * Обработка колбэка авторизации через номер телефона, вызывающая логин с phone-стратегией.
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *         
    */
    public function phoneAuthCallback(Request $request)
    {
        return $this->login($request->merge(['auth_type' => 'phone']));
    }
    
}

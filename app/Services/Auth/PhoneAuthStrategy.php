<?php 

namespace App\Services\Auth;

use App\Models\User;
use App\Services\SmsCodeService;
use App\Services\TokenService;
use Illuminate\Http\Request;

class PhoneAuthStrategy implements AuthStrategyInterface
{
    protected $tokenService;
    protected $smsCodeService;

    public function __construct(TokenService $tokenService, SmsCodeService $smsCodeService)
    {
        $this->tokenService = $tokenService;
        $this->smsCodeService = $smsCodeService;
    }

    public function login(Request $request)
    {
        $phone = $request->input('phone');
        $smsCode = $request->input('sms_code');

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return response()->json(['error' => 'Пользователь не найден'], 404);
        }

        if (!$this->smsCodeService->validateCode($phone, $smsCode)) {
            return response()->json(['error' => 'Неверный или истекший код'], 400);
        }

        $this->smsCodeService->deleteCode($phone, $smsCode);


        $accessToken = $this->tokenService->generateAccessToken($user->id);
        $refreshToken = $this->tokenService->generateRefreshToken($user->id);

        return redirect()->route('userInfo')
            ->withCookies([
            cookie('access_token', $accessToken->token, $accessToken->expires_at, '/', null, true, true),
            cookie('refresh_token', $refreshToken->token, $refreshToken->expires_at, '/', null, true, true),
        ]);
    }

    
}
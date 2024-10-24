<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\TokenService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TelegramAuthStrategy implements AuthStrategyInterface
{
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

   
    public function login(Request $request)
    {   
        $telegramData = $request->only(['id', 'first_name', 'username', 'photo_url', 'auth_date', 'hash']);
        
        if (!$this->isValidTelegramData($telegramData)) {
            return response()->json(['error' => ''], 400);
        }

        $user = User::where('telegram_id', $telegramData['id'])->first();

        if (!$user) {
            $user = User::create([
                'telegram_id' => $telegramData['id'],
                'name' => $telegramData['first_name'],
                'password' => Hash::make(Str::random(32))
            ]);
        }

        
        $accessToken = $this->tokenService->generateAccessToken($user->id);
        $refreshToken = $this->tokenService->generateRefreshToken($user->id);

        return redirect()->route('userInfo')
            ->withCookies([
            cookie('access_token', $accessToken->token, $accessToken->expires_at, '/', null, true, true),
            cookie('refresh_token', $refreshToken->token, $refreshToken->expires_at, '/', null, true, true),
        ]);
    }   

   
    private function isValidTelegramData($telegramData)
    {
        $check_hash = $telegramData['hash'];
        unset($telegramData['hash']);
        $data_check_arr = [];
        foreach ($telegramData as $key => $value) {
            $data_check_arr[] = $key . '=' . $value;
        }
        sort($data_check_arr); 
        $data_check_string = implode("\n", $data_check_arr);
        $secret_key = hash('sha256', env("TELEGRAM_BOT_TOKEN"), true);
        $hash = hash_hmac('sha256', $data_check_string, $secret_key);

        return hash_equals($check_hash, $hash);
    }
}
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use App\DTO\TokenDTO;
use App\Models\Token;
use Illuminate\Support\Str;

class TokenService
{

    protected $accessTokenExpiration;
    protected $refreshTokenExpiration;

    public function __construct()
    {
        $this->accessTokenExpiration = config('auth.tokens.access_token_expiration');
        $this->refreshTokenExpiration = config('auth.tokens.refresh_token_expiration');
    }

    /**
     * Генерирует и сохраняет access токен для  пользователя.
     * 
     * @param int $userId 
     * 
     * @return TokenDTO 
     */
    public function generateAccessToken(int $userId) : TokenDTO   
    {

        $token = Str::random(40);

        Token::create([
            'user_id' => $userId,
            'token' => hash('sha256', $token),
            'type' => 'access',
            'expires_at' => now()->addMinutes($this->accessTokenExpiration),
        ]);

        return new TokenDTO($token, $this->accessTokenExpiration);
    }

    /**
     * Генерирует и сохраняет refresh токен для пользователя.
     * 
     * @param int $userId 
     * 
     * @return TokenDTO 
     */
    public function generateRefreshToken(int $userId)
    {

        $token = Str::random(40);

        Token::create([
            'user_id' => $userId,
            'token' => hash('sha256', $token),
            'type' => 'refresh',
            'expires_at' => now()->addMinutes($this->refreshTokenExpiration),
        ]);

        return new TokenDTO($token, $this->refreshTokenExpiration);
    }

    /**
     * Аннулирует токен, удаляя его из базы данных.
     * 
     * @param string $token 
     * 
     * @return void
     */
    public function revokeToken($token)
    {
        Token::where('token', hash('sha256', $token))->delete();
    }

    /**
     * Обновляет access токен, используя действующий refresh токен.
     * 
     * @param string $refreshToken 
     * 
     * @return TokenDTO|null 
     */
    public function updateAccessToken(string $refreshToken)
    {
        $hashedToken = hash('sha256', $refreshToken);

        $token = Token::where('token', $hashedToken)
                      ->where('type', 'refresh')
                      ->where('expires_at', '>', now()) 
                      ->first();
        
        if (!$token){
            return null;
        }

        $newAccessToken = $this->generateAccessToken($token->user_id);

        return $newAccessToken;
    }

    /**
     * Получает экземпляр пользователя при помощи токена.
     *
     * @param string $token 
     * 
     */
    public function getUserFromToken($token)
    {
        if (empty($token)) {
            return null;
        }

        $tokenModel = Token::where('token', hash('sha256', $token))
                            ->where('type', 'access')
                            ->first();
        
        return $tokenModel ? $tokenModel->user : null;
    }


     /**
     * Обновление access токена при помощи refresh токена, если он существует.
     *
     * @param  \Illuminate\Http\Request $request
     * 
     */
    public function refreshAccessTokenIfExist(Request $request): ?User
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return null;
        }
        
        $newAccessToken = $this->updateAccessToken($refreshToken);

        if (!$newAccessToken) {
            return null;
        }

        cookie()->queue('access_token', $newAccessToken->token, $newAccessToken->expires_at, '/', null, true, true);
        
        return $this->getUserFromToken($newAccessToken->token);
    }

}

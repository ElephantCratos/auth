<?php

namespace App\Services;

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

    public function revokeToken($token)
    {
        Token::where('token', hash('sha256', $token))->delete();
    }

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
}

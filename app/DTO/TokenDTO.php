<?php

namespace App\DTO;

class TokenDTO
{
    public string $token;

    public int $expires_at;

    public function __construct(string $token, int $expires_at)
    {
        $this->token = $token;
        $this->expires_at = $expires_at;
    }
}

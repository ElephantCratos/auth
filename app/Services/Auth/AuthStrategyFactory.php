<?php

namespace App\Services\Auth;

class AuthStrategyFactory
{
    protected $emailPasswordAuthStrategy;
    protected $telegramAuthStrategy;

    public function __construct(
        EmailPasswordAuthStrategy $emailPasswordAuthStrategy,
        TelegramAuthStrategy $telegramAuthStrategy
    ) {
        $this->emailPasswordAuthStrategy = $emailPasswordAuthStrategy;
        $this->telegramAuthStrategy = $telegramAuthStrategy;
    }

    public function make(string $type): AuthStrategyInterface
    {
        switch ($type) {
            case 'email':
                return $this->emailPasswordAuthStrategy;
            case 'telegram':
                return $this->telegramAuthStrategy;
            default:
                throw new \Exception('Unsupported auth type');
        }
    }
}
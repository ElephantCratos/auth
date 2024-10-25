<?php

namespace App\Services\Auth;

class AuthStrategyFactory
{
    protected $emailPasswordAuthStrategy;
    protected $telegramAuthStrategy;

    protected $phoneAuthStrategy;

    public function __construct(
        EmailPasswordAuthStrategy $emailPasswordAuthStrategy,
        TelegramAuthStrategy $telegramAuthStrategy,
        PhoneAuthStrategy $phoneAuthStrategy
    ) 
    {
        $this->emailPasswordAuthStrategy = $emailPasswordAuthStrategy;
        $this->telegramAuthStrategy = $telegramAuthStrategy;
        $this->phoneAuthStrategy = $phoneAuthStrategy;
    }

    /**
     * Фабричный метод для создания стратегии аутентификации на основе указанного типа.
     * 
     * @param string $type 
     * 
     * @return AuthStrategyInterface 
     * 
     * @throws \Exception.
     */
    public function make(string $type): AuthStrategyInterface
    {
        switch ($type) {
            case 'email':
                return $this->emailPasswordAuthStrategy;
            case 'telegram':
                return $this->telegramAuthStrategy;
            case 'phone':
                return $this->phoneAuthStrategy;
            default:
                throw new \Exception('Неподдерживаемый тип аутентификации');
        }
    }
}
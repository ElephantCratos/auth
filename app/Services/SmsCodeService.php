<?php

namespace App\Services;

use App\Models\SmsCode;
use Illuminate\Support\Facades\DB;

class SmsCodeService
{

    /**
     * Генерация случайного SMS-кода
     * 
     * @return int
     */
    public function generateCode()
    {
        return random_int(100000, 999999);
    }

    /**
     * Сохранение SMS-кода в базе данных
     * 
     * @param string $phone
     * 
     * @param string $code
     */
    public function storeCode(string $phone, string $code)
    {
        return SmsCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);
    }

    /**
     * Проверка валидности кода
     * 
     * @param string $phone
     * 
     * @param string $code
     * 
     * @return bool
     */
    public function validateCode(string $phone, string $code): bool
    {
        return SmsCode::where('phone', $phone)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->exists();
    }

    /**
     * Удаление кода из базы данных
     * 
     * @param string $phone
     * 
     * @param string $code
     * 
     */
    public function deleteCode(string $phone, string $code) 
    {
       SmsCode::where('phone', $phone)
            ->where('code', $code)
            ->delete();
    }

    

    

}
<?php

namespace App\Http\Controllers;

use App\Services\SmsCodeService;
use Illuminate\Http\Request;
use App\Models\User;

class VerificationController extends Controller
{
    protected $smsCodeService;
    public function __construct(SmsCodeService $smsCodeService){
        $this->smsCodeService = $smsCodeService;
    }

    public function sendSmsCode(Request $request)
    {
        $phone = $request->phone;

        $user = User::where('phone',$phone)->first();

        if (!$user) {
            return response()->json(['error' => 'Пользователь с данным номером не найден'], 404);
        }

        $code = $this->smsCodeService->generateCode();

        $this->smsCodeService->sendSmsCode($phone,$code);

        $this->smsCodeService->storeCode($phone, $code);

        
        
        return response()->json(['message' => 'Код был отправлен на ваш номер телефона']);

    }
}

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
        $user = User::where('phone',$request->phone)->first();

        if (!$user) {
            return response()->json(['error' => 'Пользователь с данным номером не найден'], 404);
        }
        
        $code = $this->smsCodeService->generateCode();

        $this->smsCodeService->storeCode($request->phone, $code);

        
        
        return response()->json(['message' => 'Код был отправлен на ваш номер телефона']);

    }
}

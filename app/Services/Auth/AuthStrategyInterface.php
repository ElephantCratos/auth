<?php

namespace App\Services\Auth;

use Illuminate\Http\Request;

interface AuthStrategyInterface
{
    public function login(Request $request);
}
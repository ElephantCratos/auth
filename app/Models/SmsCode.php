<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsCode extends Model
{
   protected $fillable = [
        'phone',
        'code',
        'expires_at'
   ];
}

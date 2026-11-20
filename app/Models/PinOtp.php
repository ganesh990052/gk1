<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinOtp extends Model
{
    use HasFactory;
protected $table="pin_otp";
    protected $fillable = ['pin_id', 'otp', 'expires_at'];
}

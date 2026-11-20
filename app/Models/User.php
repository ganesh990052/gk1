<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'location',
        'phone',
        'image',
        'password_confirmation',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // public function setPasswordAttribute($password)
    // {
    //     $this->attributes['password'] = bcrypt($password);
    // }
  public function Mychit()
    {
        return $this->hasMany(JoiningUsers::class);
    }
    public function bid()
    {
        return $this->hasMany(Bid::class);
    }
    public function Luckymember()
    {
        return $this->hasMany(LuckyMember::class);
    }

    public function wallet()
    {
        return $this->hasMany(Wallet::class);
    }
      public function document()
      {
        return $this->hasMany(Document::class);
      }
    public function nomineedetail()
    {
        return $this->hasMany(NomineeDetail::class);
    }

    public function bankdetail()
    {
    return $this->hasMany(BankDetail::class);
    }

    public function occupationdetail()
    {
        return  $this->hasMany(OccupationDetail::class);
    }

    public function currentaddress()
    {
        return $this->hasMany(CurrentAddress::class);
    }
    
    public function pin()
    {
        return $this->hasMany(Pin::class);
    }
}

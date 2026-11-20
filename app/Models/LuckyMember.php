<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LuckyMember extends Model
{
    use HasFactory;
    protected $table='lucky_member';
    public function chitgroup()
    {
        return $this->belongsTo(Chitgroup::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

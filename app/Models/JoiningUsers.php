<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JoiningUsers extends Model
{
    use HasFactory;
    protected $table='joining_users';
    
       public function chitgroup()
    {
        return $this->belongsTo(Chitgroup::class,'chit_group_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

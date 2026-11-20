<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chitgroup extends Model
{
    use HasFactory;
    protected $table='chit_groups';

    public function chit()
    {
        return $this->belongsTo(Chit::class);
    }

    public function luckyMember()
    {
        return $this->hasMany(LuckyMember::class,'chit_group_id');
    }
public function bid()
{
    return $this->hasMany(Bid::class, 'chit_group_id');
}

}

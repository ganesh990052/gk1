<?php
// app/Models/CurrentAddress.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'house_no',
        'building_name',
        'street_name',
        'city',
        'state',
        'postal_code',
        'landmark',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php
// app/Models/OccupationDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OccupationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'occupation',
        'current_employer',
        'years_at_current_employer',
        'monthly_income',
        'living_status',
        'years_at_current_residence',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

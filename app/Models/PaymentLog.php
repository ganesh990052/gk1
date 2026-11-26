<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Factories\HasFactory;

class PaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'emi_id',
        'paid_amount',
        'penalty_paid',
        'paid_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emi()
    {
        return $this->belongsTo(EmiTable::class, 'emi_id');
    }
}

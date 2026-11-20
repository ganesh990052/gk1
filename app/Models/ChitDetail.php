<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChitDetail extends Model
{
    use HasFactory;
protected $table='chitdetails';
    protected $fillable = [
        'chit_id',
        'chit_value',
        'duration',
        'subscription_amount',
        'first_auction_date',
        'chit_type',
        'emi_status',
        // Add more fillable attributes as needed
    ];

    public function chit()
    {
        return $this->belongsTo(Chit::class);
    }
}
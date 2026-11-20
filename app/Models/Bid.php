<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;
    protected $table ='bids';
    protected $fillable = [
        'user_id',
        'chit_group_id',
        'order_id',
        'applied_chit_id',
        'chit_id',
        'emi_status_of_this_month',
        'amount',
        'date',

        // Add more fillable attributes as needed
    ];
    public function chitgroup()
    {
        return $this->belongsTo(Chitgroup::class,'chit_group_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // app/Models/Bid.php
    public function chitDetail()
    {
        return $this->belongsTo(ChitDetail::class, 'chit_id', 'chit_id');
    }

    
}

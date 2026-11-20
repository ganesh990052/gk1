<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'order';

    protected $fillable = [
        'order_id',
        'user_id',
        'chit_group_id',
        'chit_id',
        'amount',
        'status',
    ];

    // Relations
    public function chitgroup()
    {
        return $this->belongsTo(ChitGroup::class, 'chit_group_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

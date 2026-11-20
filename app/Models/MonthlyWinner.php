<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyWinner extends Model
{
    use HasFactory;
    protected $table ='monthly_wise_winner';
    protected $fillable = [
        'user_id',
        'chit_id',
        'date',
        // Add more columns as needed
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

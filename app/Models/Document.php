<?php

// app/Models/Document.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'aadhar_card_front',
        'aadhar_card_back',
        'pan_card',
        'bank_statement',
        'salary_slip',
        // Add more columns as needed
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'account_number',
        'account_holder_name',
        'bank_name',
        'branch_name',
        'ifsc_code',
        'type_of_account',
        // Add more columns as needed
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

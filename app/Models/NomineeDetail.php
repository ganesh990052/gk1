<?php

// app/Models/NomineeDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NomineeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nominee_name',
        'relation',
        'contact_number',
        // Add more columns as needed
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

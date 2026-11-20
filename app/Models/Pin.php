<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Pin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'pin', 'created_at', 'updated_at'];

    // Mutator to encrypt the pin before saving to the database
    public function setPinAttribute($value)
    {
        $this->attributes['pin'] = Crypt::encryptString($value);
    }

    // Accessor to decrypt the pin when retrieving from the database
    public function getPinAttribute($value)
    {
        return Crypt::decryptString($value);
    }
      public function user()
    {
        return $this->belongsTo(User::class);
    }
}

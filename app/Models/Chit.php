<?php

// app/Models/Chit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        // Add more fillable attributes as needed
    ];

    public function chitdetail()
    {
return $this->hasMany(ChitDetail::class);
    }

    public function chitgroup()
    {
return $this->hasMany(Chitgroup::class);
    }

    public function profit()
    {
        return $this->hasMany(Profit::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use HasFactory;
    protected $table='table_profit';
    protected $fillable = [
        'chit_id',
        'presentage',
        // Add more columns as needed
    ];

    public function chit()
    {
        return $this->belongsTo(Chit::class);
    }

}

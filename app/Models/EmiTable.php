<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmiTable extends Model
{
    use HasFactory;

    protected $table = 'applied_chits';

    public function paymentLogs()
    {
        return $this->hasMany(PaymentLog::class, 'emi_id');
    }
}
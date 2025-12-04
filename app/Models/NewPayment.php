<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewPayment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $guarded = [];

    protected $casts = [
        'transaction_date' => 'date',
        'date' => 'date',
    ];
}

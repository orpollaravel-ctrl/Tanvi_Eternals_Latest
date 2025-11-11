<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BullionPurchase extends Model
{
    use HasFactory;

    protected $table = 'bullion_purchases';    
    protected $guarded = [];
    
    protected $casts = [
        'transaction_date' => 'date',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BullionRate extends Model
{
    use HasFactory;

    protected $table = 'bullion_rates';    
    protected $guarded = [];
    protected $casts = [
        'date' => 'date',
    ];
}

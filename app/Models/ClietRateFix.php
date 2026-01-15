<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClietRateFix extends Model
{
    use HasFactory;
    protected $table = 'client_rate_fixs';
    protected $guarded = [];

    protected $casts = [
        'jewel_trans_date' => 'date',
    ];
    
}

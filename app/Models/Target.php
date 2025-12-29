<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'target_date',
        'user_id', 
        'reason',
        'phone',
        'time',
        'shop_photo',
        'visit_card'
    ];
}

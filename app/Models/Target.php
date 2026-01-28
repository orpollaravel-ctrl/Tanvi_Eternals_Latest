<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name',
        'location',
        'target_date',
        'user_id', 
        'reason',
        'phone',
        'time',
        'shop_photo',
        'visit_card'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

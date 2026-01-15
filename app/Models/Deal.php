<?php

namespace App\Models;

use App\Http\Controllers\DealerRateFixController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    protected $fillable = [
        'bullion_rate_fix_id',
        'dealer_rate_fix_id',
        'quantity',
        'fixed_by',
        'created_by',   
    ];

    public function brf(){
        return $this->belongsTo(BullionRateFix::class,'bullion_rate_fix_id');
    }

    public function drf(){
        return $this->belongsTo(DealerRateFix::class,'dealer_rate_fix_id');
    }
}

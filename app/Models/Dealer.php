<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model
{
    use HasFactory;

    protected $fillable = ['name','code','email','phone','address','pincode','location','status'];

    protected static function booted()
    {
        static::creating(function ($model) {
            // // Role permission check: restrict access for users with role 0
            // if (auth()->user()->role == 0) {
            //     abort(403);
            // }
        });
        static::updating(function ($model) {
            // // Role permission check: restrict access for users with role 0
            // if (auth()->user()->role == 0) {
            //     abort(403);
            // }
        });
        static::deleting(function ($model) {
            // // Role permission check: restrict access for users with role 0
            // if (auth()->user()->role == 0) {
            //     abort(403);
            // }
        });
    }

    public function dealerRateFixes(){
        return $this->hasMany(DealerRateFix::class);
    }

    public function deals(){
        return $this->hasManyThrough(Deal::class,DealerRateFix::class);
    }
}

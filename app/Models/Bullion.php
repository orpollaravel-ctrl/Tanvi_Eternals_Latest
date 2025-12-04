<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bullion extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone','status'];

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

    public function bullionRateFixes(){
        return $this->hasMany(BullionRateFix::class);
    }

    public function receipts(){
        return $this->hasMany(Receipt::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function deals(){
        return $this->hasManyThrough(Deal::class,BullionRateFix::class);
    }
}

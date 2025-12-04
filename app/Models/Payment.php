<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['pay_date','transferred_by','payment_mode_id','created_by','updated_by','bullion_id','amount','remark'];
    protected $dates = ['pay_date'];

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

    public function bullion(){
        return $this->belongsTo(Bullion::class);
    }

    public function paymentMode(){
        return $this->belongsTo(PaymentMode::class);
    }

    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function transferredBy(){
        return $this->belongsTo(User::class,'transferred_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function transactions(){
        return $this->belongsToMany(Transaction::class)->withPivot('amount')->withTimestamps();
    }
}

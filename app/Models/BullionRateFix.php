<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BullionRateFix extends Model
{
    use HasFactory;

    protected $fillable = ['brf_date','fixed_by','bullion_id','quantity','rate','remark'];
    protected $dates = ['brf_date'];
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            // // Role permission check: restrict access for users with role 0
            // if (auth()->user()->role == 0) {
            //     abort(403);
            // }
            $model->amount= $model->quantity * ($model->rate*0.10);
            $model->created_by=auth()->user()->id;
        });
        static::updating(function ($model) {
            // // Role permission check: restrict access for users with role 0
            // if (auth()->user()->role == 0) {
            //     abort(403);
            // }
            $model->amount= $model->quantity * ($model->rate*0.10);
            $model->updated_by=auth()->user()->id;
        });
        static::deleting(function($model){
            // // Role permission check: restrict access for users with role 0
            // if (auth()->user()->role == 0) {
            //     abort(403);
            // }
            $model->deals()->each(function ($deal){
                $deal->delete();
            });
            $model->transactions()->each(function ($transaction){
                $transaction->delete();
            });
        });
    }

    public function bullion(){
        return $this->belongsTo(Bullion::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    public function deals(){
        return $this->hasMany(Deal::class,'bullion_rate_fix_id');
    }
    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }

    public function fixedBy(){
        return $this->belongsTo(User::class,'fixed_by');
    }

    public function updatedBy(){
        return $this->belongsTo(User::class,'updated_by');
    }
}

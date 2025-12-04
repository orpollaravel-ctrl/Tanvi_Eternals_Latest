<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $fillable = ['receipt_date', 'bullion_id', 'quantity', 'created_by', 'updated_by', 'remark'];
    protected $dates = ['receipt_date'];
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
        });
        static::updating(function ($model) {
            // // Role permission check: restrict access for users with role 0
            // if (auth()->user()->role == 0) {
            //     abort(403);
            // }
        });
        static::deleting(function ($receipt) {
            // // Role permission check: restrict access for users with role 0
            // if (auth()->user()->role == 0) {
            //     abort(403);
            // }
            $receipt->transactions()->each(function ($transaction) {
                $transaction->delete();
            });
        });
    }

    public function bullion()
    {
        return $this->belongsTo(Bullion::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

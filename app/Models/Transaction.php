<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
	
	 protected $fillable = [
        'bullion_rate_fix_id',
        'receipt_id',
        'quantity',
    ];


    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($transaction) {
            $transaction->payments()->detach();
        });
    }

    public function bullionRateFix()
    {
        return $this->belongsTo(BullionRateFix::class);
    }

    /**
     * Relationship: Transaction belongs to Receipt
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    /**
     * Relationship: Many payments mapped via pivot
     */
    public function payments()
    {
        return $this->belongsToMany(Payment::class)
                    ->withPivot('amount')
                    ->withTimestamps();
    }
}

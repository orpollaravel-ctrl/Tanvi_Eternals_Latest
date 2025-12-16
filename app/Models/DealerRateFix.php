<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerRateFix extends Model
{
 use HasFactory;

    protected $table = 'dealer_rate_fixes'; // keep same table
    protected $fillable = [
        'drf_date',
        'fixed_by',
        'client_id',
        'quantity',
        'rate',
        'remark'
    ];

    protected $dates = ['drf_date'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->amount = $model->quantity * ($model->rate * 0.10);
            $model->created_by = auth()->id();
        });

        static::updating(function ($model) {
            $model->amount = $model->quantity * ($model->rate * 0.10);
            $model->updated_by = auth()->id();
        });
    }

    /* ================= Relationships ================= */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function fixedBy()
    {
        return $this->belongsTo(User::class, 'fixed_by');
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

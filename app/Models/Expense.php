<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'date',
        'amount',
        'remark',
        'bill_upload',
        'salesman_id',
        'salesman_name',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    
    public function salesman()
    {
        return $this->belongsTo(Employee::class, 'salesman_id');
    }
}
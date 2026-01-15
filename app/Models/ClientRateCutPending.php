<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRateCutPending extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'client_code',
        'client_name',
        'transaction_date',
        'sales_person',
        'pure_weight',
        'sale_rate',
        'amount',
        'rate_cut',
        'amt',
        'diff_amt',
        'transaction_no',
        'file_path',
        'file_name',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'pure_weight' => 'decimal:3',
        'sale_rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'rate_cut' => 'decimal:2',
        'amt' => 'decimal:2',
        'diff_amt' => 'decimal:2',
    ];
}

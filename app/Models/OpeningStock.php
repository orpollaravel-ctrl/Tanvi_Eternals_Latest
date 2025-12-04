<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'mrp',
        'sale_rate',
        'purchase_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'mrp' => 'decimal:2',
        'sale_rate' => 'decimal:2',
        'purchase_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

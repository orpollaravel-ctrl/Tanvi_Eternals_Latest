<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'product_name',
        'serial_number',
        'expiry_date',
        'quantity',
        'rate',
        'amount',
        'gst_percentage',
        'gst_value',
        'final_amount',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'amount' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'gst_value' => 'decimal:2',
        'final_amount' => 'decimal:2',
    ];

    /**
     * Get the purchase
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the product (if linked to product master)
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

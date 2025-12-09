<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'bill_date',
        'bill_number',
        'delivery_date',
        'total_invoice_amount',
        'bill_photo', 
    ];

    protected $casts = [
        'bill_date' => 'date',
        'delivery_date' => 'date',
        'total_invoice_amount' => 'decimal:2',
    ];

    /**
     * Get the vendor
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get all items for this purchase
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }
}

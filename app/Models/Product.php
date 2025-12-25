<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'barcode_number',
        'tool_code',
        'category_id',
        'product_company',
        'hsn_code',
        'minimum_rate',
        'maximum_rate',
        'minimum_quantity',
        'reorder_quantity',
        'unit_id',
        'product_photo',
    ];

    protected $casts = [
        'minimum_rate' => 'decimal:2',
        'maximum_rate' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function getProductPhotoUrlAttribute(): string
    {
        if ($this->product_photo !== null) {
            return url('media/product/' . $this->id . '/' . $this->product_photo);
        } else {
            return url('media-example/no-image.png');
        }
    }

    public function openingStock()
    {
        return $this->hasOne(OpeningStock::class);
    }
	 public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function toolAssignItems()
    {
        return $this->hasMany(ToolAssignItem::class);
    }

    public function quotation()
    {
        return $this->hasMany(Quotation::class);
    }
}

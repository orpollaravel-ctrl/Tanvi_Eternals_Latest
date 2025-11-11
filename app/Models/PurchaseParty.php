<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseParty extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_name',
        'company_name',
        'gst_number',
        'address',
        'bank_account_number',
        'ifsc_code',
        'mobile_number',
        'email',
    ];

    /**
     * Get all purchases for this party
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}

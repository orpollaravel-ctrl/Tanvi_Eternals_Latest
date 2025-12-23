<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'contact',
        'customer_code',
        'barcode',
        'metal',
        'purity',
        'diamond',
        'women_ring_size_from',
        'women_ring_size_to',
        'men_ring_size_from',
        'men_ring_size_to',
        'remarks',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'customer_id');
    }
}
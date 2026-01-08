<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'customer_name',
        'pincode',
        'state',
        'city',
        'contact', 
        'barcode',
        'salesman_name', 
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

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    
    public function pdfs()
    {
        return $this->hasMany(QuotationPdf::class);
    }
     public function salesman()
    {
        return $this->belongsTo(Employee::class, 'salesman_id');
    }
}
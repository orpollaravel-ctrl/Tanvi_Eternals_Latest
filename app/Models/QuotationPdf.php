<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationPdf extends Model
{
    use HasFactory;
     protected $fillable = [
        'quotation_id',
        'file_path',
        'original_name',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}

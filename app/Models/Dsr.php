<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dsr extends Model
{
    protected $fillable = [
        'client_id',
        'client_type',
        'no_of_shops',
        'visiting_card_photo',
        'shop_photo',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

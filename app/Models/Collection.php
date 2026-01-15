<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'user_id',
        'time',
        'collection_date',
        'amount', 
        'remark',
    ];
}

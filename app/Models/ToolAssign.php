<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'd_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class, 'd_id');
    }

    public function items()
    {
        return $this->hasMany(ToolAssignItem::class);
    }
}

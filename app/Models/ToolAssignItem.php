<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolAssignItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_assign_id',
        'product_id',
        'quantity',
        'emp_id',
        'serial_number',
    ];

    public function toolAssign()
    {
        return $this->belongsTo(ToolAssign::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}

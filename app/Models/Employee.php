<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'barcode',
		'images',
        'active',
        'department_id',
        'monthly_target_hours',
        'monthly_salary',
    ];

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'salesman_id');
    }

    public function expense()
    {
        return $this->hasMany(Expense::class, 'salesman_id');
    }

     public function quotations()
    {
        return $this->hasMany(Quotation::class, 'salesman_id');
    }
}

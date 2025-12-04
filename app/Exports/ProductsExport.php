<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = '')
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = Product::query()->with('category')->latest();
        
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('product_name', 'like', '%' . $this->search . '%')
                    ->orWhere('barcode_number', 'like', '%' . $this->search . '%')
                    ->orWhere('tool_code', 'like', '%' . $this->search . '%');
            });
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Barcode',
            'Tool Code',
            'HSN Code',
            'Min Rate',
            'Max Rate',
            'Min Quantity', 
        ];
    }

    public function map($product): array
    {
        return [
            $product->product_name,
            $product->barcode_number,
            $product->tool_code, 
            $product->hsn_code,
            $product->minimum_rate,
            $product->maximum_rate,
            $product->minimum_quantity, 
        ];
    }
}
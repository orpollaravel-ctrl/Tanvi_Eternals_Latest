<?php

namespace App\Exports;

use App\Http\Controllers\InventoryCalculationController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $search;

    public function __construct($search = '')
    {
        $this->search = $search;
    }

    public function collection()
    {
        $controller = new InventoryCalculationController();
        $inventory = $controller->getInventoryData($this->search);
        return collect($inventory);
    }

    public function headings(): array
    {
        return [
            'Tool Code',
            'Product Name',
            'Barcode',
            'Remaining Qty',
            'Remaining Value',
        ];
    }

    public function map($inv): array
    {
        return [
            $inv['product']->tool_code,
            $inv['product']->product_name,
            $inv['product']->barcode_number,
            number_format($inv['remaining_qty'], 2),
            number_format($inv['remaining_value'], 2),
        ];
    }
}
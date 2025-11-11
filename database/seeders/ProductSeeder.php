<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::first();
        $unit = Unit::first();

        $products = [
            [
                'product_name' => 'Gold Ring',
                'barcode_number' => '123456789',
                'tool_code' => 'RING001',
                'product_company' => 'Jewelry Co',
                'hsn_code' => '7113',
                'minimum_rate' => 500,
                'maximum_rate' => 1000,
                'minimum_quantity' => 1,
                'reorder_quantity' => 10,
            ],
            [
                'product_name' => 'Silver Bracelet',
                'barcode_number' => '987654321',
                'tool_code' => 'BRAC001',
                'product_company' => 'Jewelry Co',
                'hsn_code' => '7108',
                'minimum_rate' => 300,
                'maximum_rate' => 600,
                'minimum_quantity' => 1,
                'reorder_quantity' => 10,
            ],
            [
                'product_name' => 'Gold Necklace',
                'barcode_number' => '456789123',
                'tool_code' => 'NECK001',
                'product_company' => 'Jewelry Co',
                'hsn_code' => '7113',
                'minimum_rate' => 800,
                'maximum_rate' => 1500,
                'minimum_quantity' => 1,
                'reorder_quantity' => 10,
            ],
            [
                'product_name' => 'Diamond Earrings',
                'barcode_number' => '789123456',
                'tool_code' => 'EAR001',
                'product_company' => 'Jewelry Co',
                'hsn_code' => '7116',
                'minimum_rate' => 2000,
                'maximum_rate' => 5000,
                'minimum_quantity' => 1,
                'reorder_quantity' => 5,
            ],
            [
                'product_name' => 'Silver Pendant',
                'barcode_number' => '321654987',
                'tool_code' => 'PEND001',
                'product_company' => 'Jewelry Co',
                'hsn_code' => '7108',
                'minimum_rate' => 150,
                'maximum_rate' => 350,
                'minimum_quantity' => 1,
                'reorder_quantity' => 20,
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                ...$product,
                'category_id' => $category->id,
                'unit_id' => $unit->id,
            ]);
        }
    }
}

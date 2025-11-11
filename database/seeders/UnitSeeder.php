<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'name' => 'Gram',
                'symbol' => 'g',
                'description' => 'Unit of weight in grams',
            ],
            [
                'name' => 'Kilogram',
                'symbol' => 'kg',
                'description' => 'Unit of weight in kilograms',
            ],
            [
                'name' => 'Ounce',
                'symbol' => 'oz',
                'description' => 'Unit of weight in ounces',
            ],
            [
                'name' => 'Pound',
                'symbol' => 'lb',
                'description' => 'Unit of weight in pounds',
            ],
            [
                'name' => 'Milligram',
                'symbol' => 'mg',
                'description' => 'Unit of weight in milligrams',
            ],
            [
                'name' => 'Carat',
                'symbol' => 'ct',
                'description' => 'Unit of weight for gemstones and diamonds',
            ],
            [
                'name' => 'Troy Ounce',
                'symbol' => 'troy oz',
                'description' => 'Unit of weight used for precious metals',
            ],
            [
                'name' => 'Piece',
                'symbol' => 'pc',
                'description' => 'Individual piece or unit',
            ],
            [
                'name' => 'Set',
                'symbol' => 'set',
                'description' => 'A set of jewelry items',
            ],
            [
                'name' => 'Dozen',
                'symbol' => 'dz',
                'description' => 'A group of twelve items',
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Rings',
                'description' => 'Various types of rings including engagement, wedding, and fashion rings',
            ],
            [
                'name' => 'Necklaces',
                'description' => 'Chain necklaces, pendant necklaces, and other neck jewelry',
            ],
            [
                'name' => 'Bracelets',
                'description' => 'Bracelets, bangles, and wrist jewelry',
            ],
            [
                'name' => 'Earrings',
                'description' => 'Studs, hoops, drops, and chandelier earrings',
            ],
            [
                'name' => 'Anklets',
                'description' => 'Ankle jewelry and foot ornaments',
            ],
            [
                'name' => 'Pendants',
                'description' => 'Loose pendants and lockets',
            ],
            [
                'name' => 'Brooches',
                'description' => 'Decorative pins and brooches',
            ],
            [
                'name' => 'Body Jewelry',
                'description' => 'Nose rings, belly rings, and other body jewelry',
            ],
            [
                'name' => 'Gold Jewelry',
                'description' => 'Jewelry made with gold',
            ],
            [
                'name' => 'Silver Jewelry',
                'description' => 'Jewelry made with silver',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

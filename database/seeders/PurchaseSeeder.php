<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\PurchaseParty;
use App\Models\Product;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing purchase parties
        $parties = PurchaseParty::all();
        $products = Product::all();

        if ($parties->isEmpty() || $products->isEmpty()) {
            return; // Skip if no parties or products exist
        }

        // Create multiple purchases
        $purchases = [
            [
                'purchase_party_id' => $parties->first()->id,
                'bill_date' => now()->subDays(30),
                'bill_number' => 'INV-001-2025',
                'delivery_date' => now()->subDays(28),
                'total_invoice_amount' => 15000.00,
                'bill_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'purchase_party_id' => $parties->skip(1)->first()->id ?? $parties->first()->id,
                'bill_date' => now()->subDays(20),
                'bill_number' => 'INV-002-2025',
                'delivery_date' => now()->subDays(18),
                'total_invoice_amount' => 25500.50,
                'bill_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'purchase_party_id' => $parties->skip(2)->first()->id ?? $parties->first()->id,
                'bill_date' => now()->subDays(10),
                'bill_number' => 'INV-003-2025',
                'delivery_date' => now()->subDays(8),
                'total_invoice_amount' => 32100.00,
                'bill_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'purchase_party_id' => $parties->skip(3)->first()->id ?? $parties->first()->id,
                'bill_date' => now()->subDays(5),
                'bill_number' => 'INV-004-2025',
                'delivery_date' => now()->subDays(3),
                'total_invoice_amount' => 18750.75,
                'bill_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'purchase_party_id' => $parties->skip(4)->first()->id ?? $parties->first()->id,
                'bill_date' => now()->subDays(2),
                'bill_number' => 'INV-005-2025',
                'delivery_date' => now(),
                'total_invoice_amount' => 42300.00,
                'bill_photo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($purchases as $purchaseData) {
            $purchase = Purchase::create($purchaseData);

            // Create purchase items for each purchase
            $itemCount = rand(2, 5);
            for ($i = 0; $i < $itemCount; $i++) {
                $product = $products->random();
                $quantity = rand(5, 50);
                $rate = rand(100, 1000);
                $amount = $quantity * $rate;

                $purchase->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'expiry_date' => now()->addMonths(rand(1, 12)),
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'amount' => $amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

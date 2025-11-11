<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PurchaseParty;

class PurchasePartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PurchaseParty::insert([
            [
                'party_name' => 'ABC Suppliers',
                'company_name' => 'ABC Trading Company',
                'gst_number' => '18AABCT1234A1Z0',
                'address' => '123 Business Street, Mumbai, Maharashtra 400001',
                'bank_account_number' => '9876543210123456',
                'ifsc_code' => 'SBIN0001234',
                'mobile_number' => '9876543210',
                'email' => 'contact@abcsuppliers.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'party_name' => 'Global Enterprises',
                'company_name' => 'Global Enterprises Pvt Ltd',
                'gst_number' => '27AABCE5678B2Z5',
                'address' => '456 Commerce Avenue, Bangalore, Karnataka 560001',
                'bank_account_number' => '1234567890123456',
                'ifsc_code' => 'HDFC0001567',
                'mobile_number' => '9123456789',
                'email' => 'sales@globalenterprises.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'party_name' => 'Prime Wholesalers',
                'company_name' => 'Prime Wholesalers India',
                'gst_number' => '08AABCP9012C3Z8',
                'address' => '789 Industrial Road, Delhi, Delhi 110001',
                'bank_account_number' => '5432109876543210',
                'ifsc_code' => 'AXIS0001890',
                'mobile_number' => '8765432109',
                'email' => 'info@primewholesalers.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'party_name' => 'Standard Imports',
                'company_name' => 'Standard Imports LLC',
                'gst_number' => '33AABCS3456D4Z0',
                'address' => '321 Trade Center, Chennai, Tamil Nadu 600001',
                'bank_account_number' => '9876543210987654',
                'ifsc_code' => 'ICIC0002123',
                'mobile_number' => '9654321098',
                'email' => 'support@standardimports.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'party_name' => 'Quality Distributors',
                'company_name' => 'Quality Distributors Network',
                'gst_number' => '24AABCQ7890E5Z2',
                'address' => '654 Distribution Hub, Pune, Maharashtra 411001',
                'bank_account_number' => '1111222233334444',
                'ifsc_code' => 'BOIB0001456',
                'mobile_number' => '9543210987',
                'email' => 'contact@qualitydist.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

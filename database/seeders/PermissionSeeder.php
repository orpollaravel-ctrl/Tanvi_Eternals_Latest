<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['label' => 'View Admin Dashboard', 'name' => 'view-admin-dashboard', 'group' => 'dashboard'],
            ['label' => 'View Bullion Dashboard', 'name' => 'view-bullion-dashboard', 'group' => 'dashboard'],
            ['label' => 'Create Users', 'name' => 'create-users', 'group' => 'master'],
            ['label' => 'Edit Users', 'name' => 'edit-users', 'group' => 'master'],
            ['label' => 'Delete Users', 'name' => 'delete-users', 'group' => 'master'],
            ['label' => 'Create Clients', 'name' => 'create-clients', 'group' => 'master'],
            ['label' => 'Edit Clients', 'name' => 'edit-clients', 'group' => 'master'],
            ['label' => 'Delete Clients', 'name' => 'delete-clients', 'group' => 'master'],
            ['label' => 'Create Vendors', 'name' => 'create-vendors', 'group' => 'master'],
            ['label' => 'Edit Vendors', 'name' => 'edit-vendors', 'group' => 'master'],
            ['label' => 'Delete Vendors', 'name' => 'delete-vendors', 'group' => 'master'],
            ['label' => 'Create Departments', 'name' => 'create-departments', 'group' => 'master'],
            ['label' => 'Edit Departments', 'name' => 'edit-departments', 'group' => 'master'],
            ['label' => 'Delete Departments', 'name' => 'delete-departments', 'group' => 'master'], 
            ['label' => 'Create Employees', 'name' => 'create-employees', 'group' => 'master'],
            ['label' => 'Edit Employees', 'name' => 'edit-employees', 'group' => 'master'],
            ['label' => 'Delete Employees', 'name' => 'delete-employees', 'group' => 'master'], 
            ['label' => 'Create Dealer Rate Fixes', 'name' => 'create-dealer-rate-fixes', 'group' => 'bullion'],
            ['label' => 'Edit Dealer Rate Fixes', 'name' => 'edit-dealer-rate-fixes', 'group' => 'bullion'],
            ['label' => 'Delete Dealer Rate Fixes', 'name' => 'delete-dealer-rate-fixes', 'group' => 'bullion'],
            ['label' => 'Create Bullion Rate Fixes', 'name' => 'create-bullion-rate-fixes', 'group' => 'bullion'],
            ['label' => 'Edit Bullion Rate Fixes', 'name' => 'edit-bullion-rate-fixes', 'group' => 'bullion'],
            ['label' => 'Delete Bullion Rate Fixes', 'name' => 'delete-bullion-rate-fixes', 'group' => 'bullion'],
            ['label' => 'Create Metal Receipts', 'name' => 'create-metal-receipts', 'group' => 'bullion'],
            ['label' => 'Edit Metal Receipts', 'name' => 'edit-metal-receipts', 'group' => 'bullion'],
            ['label' => 'Delete Metal Receipts', 'name' => 'delete-metal-receipts', 'group' => 'bullion'],
            ['label' => 'Create Payments', 'name' => 'create-payments', 'group' => 'bullion'],
            ['label' => 'Edit Payments', 'name' => 'edit-payments', 'group' => 'bullion'],
            ['label' => 'Delete Payments', 'name' => 'delete-payments', 'group' => 'bullion'],
            ['label' => 'Create Manual Deals', 'name' => 'create-manual-deals', 'group' => 'bullion'],
            ['label' => 'Edit Manual Deals', 'name' => 'edit-manual-deals', 'group' => 'bullion'],
            ['label' => 'Delete Manual Deals', 'name' => 'delete-manual-deals', 'group' => 'bullion'], 
            ['label' => 'Create Bullions', 'name' => 'create-bullions', 'group' => 'bullion'], 
            ['label' => 'Edit Bullions', 'name' => 'edit-bullions', 'group' => 'bullion'],
            ['label' => 'Delete Bullions', 'name' => 'create-bullions', 'group' => 'bullion'], 
            ['label' => 'Create Dealers', 'name' => 'create-dealers', 'group' => 'bullion'], 
            ['label' => 'Edit Dealers', 'name' => 'edit-dealers', 'group' => 'bullion'],
            ['label' => 'Delete Dealers', 'name' => 'delete-dealers', 'group' => 'bullion'],
            ['label' => 'Create Payment Modes', 'name' => 'create-payment-modes', 'group' => 'bullion'],
            ['label' => 'Edit Payment Modes', 'name' => 'edit-payment-modes', 'group' => 'bullion'],
            ['label' => 'Delete Payment Modes', 'name' => 'delete-payment-modes', 'group' => 'bullion'],
            ['label' => 'Create Products', 'name' => 'create-products', 'group' => 'tools'],
            ['label' => 'Edit Products', 'name' => 'edit-products', 'group' => 'tools'],
            ['label' => 'Delete Products', 'name' => 'delete-products', 'group' => 'tools'],
            ['label' => 'Create Tool Purchases', 'name' => 'create-tool-purchases', 'group' => 'tools'],
            ['label' => 'Edit Tool Purchases', 'name' => 'edit-tool-purchases', 'group' => 'tools'],
            ['label' => 'Delete Tool Purchases', 'name' => 'delete-tool-purchases', 'group' => 'tools'],
            ['label' => 'Create Tool Issues', 'name' => 'create-tool-issues', 'group' => 'tools'],
            ['label' => 'Edit Tool Issues', 'name' => 'edit-tool-issues', 'group' => 'tools'],
            ['label' => 'Delete Tool Issues', 'name' => 'delete-tool-issues', 'group' => 'tools'],
            ['label' => 'Create Quotations', 'name' => 'create-quotations', 'group' => 'quotation'],
            ['label' => 'Edit Quotations', 'name' => 'edit-quotations', 'group' => 'quotation'],
            ['label' => 'Delete Quotations', 'name' => 'delete-quotations', 'group' => 'quotation'],
            ['label' => 'View Expenses', 'name' => 'view-expenses', 'group' => 'expense'],
            ['label' => 'Create Expenses', 'name' => 'create-expenses', 'group' => 'expense'],
            ['label' => 'Edit Expenses', 'name' => 'edit-expenses', 'group' => 'expense'],
            ['label' => 'Delete Expenses', 'name' => 'delete-expenses', 'group' => 'expense'],
            ['label' => 'View DSR', 'name' => 'view-dsr', 'group' => 'dsr'],
            ['label' => 'Create DSR', 'name' => 'create-dsr', 'group' => 'dsr'],
            ['label' => 'Edit DSR', 'name' => 'edit-dsr', 'group' => 'dsr'],
            ['label' => 'Delete DSR', 'name' => 'delete-dsr', 'group' => 'dsr'],
            ['label' => 'View Dashboard', 'name' => 'view-dashboard', 'group' => 'customer-dashboard'],
            ['label' => 'View Customer Quotations', 'name' => 'view-customer-quotations', 'group' => 'customer-quotation'],
            ['label' => 'View Visits', 'name' => 'view-visit', 'group' => 'visit'],
            
        ];

          /**
         * ------------------------------------------------------
         * INSERT PERMISSIONS
         * ------------------------------------------------------
         */
        foreach ($permissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'label' => $permission['label'],
                'name' => $permission['name'],
                'group' => $permission['group'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /**
         * ------------------------------------------------------
         * CUSTOMER PERMISSIONS → CLIENTS
         * ------------------------------------------------------
         */
        $customerPermissionNames = [
            'view-dashboard',
            'view-customer-quotations',
        ];

        $customerPermissionIds = DB::table('permissions')
            ->whereIn('name', $customerPermissionNames)
            ->pluck('id');

        $clients = DB::table('clients')->pluck('id');

        foreach ($clients as $clientId) {
            foreach ($customerPermissionIds as $permissionId) {
                DB::table('permission_client')->insertOrIgnore([
                    'client_id' => $clientId,
                    'permission' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        /**
         * ------------------------------------------------------
         * ADMIN PERMISSIONS → USERS
         * ------------------------------------------------------
         */
        $userPermissionNames = array_filter(
            array_column($permissions, 'name'),
            fn ($name) => !in_array($name, $customerPermissionNames)
        );

        $userPermissionIds = DB::table('permissions')
            ->whereIn('name', $userPermissionNames)
            ->pluck('id');

        foreach ($userPermissionIds as $permissionId) {
            DB::table('permission_user')->insertOrIgnore([
                'user_id' => 1, // Super Admin
                'permission_id' => $permissionId,
            ]);
        }
    }
}
<?php

namespace App\Main;
use Illuminate\Support\Facades\Auth;

class SideMenu
{
    /**
     * List of side menu items.
     */
    public static function menu(): array
    {
        return [
			'dashboard' => [
                'icon' => 'home',
                'title' => 'Dashboard',
                'sub_menu' => [
                    'dashboard-overview-1' => [
                        'icon' => 'gauge',
                        'route_name' => 'dashboard-overview-1',
                         'permission' => 'view-admin-dashboard',
                        'params' => [
                            'layout' => 'side-menu',
                        ],
                        'title' => 'Dashboard 1',
                    ],
                    'dashboard-customer' => [
                        'icon' => 'bar-chart-2',
                        'route_name' => 'customer.dashboard',
                        'permission' => 'view-dashboard',    
                        'params' => [
                            'layout' => 'side-menu',
                        ],
                        'title' => 'Customer Dashboard',
                    ],
                ],
            ],
            'dashboard-overview-2' => [
                'icon' => 'bar-chart-2',
                'route_name' => 'bullion.dashboard',
                'permission' => 'view-bullion-dashboard',
                'params' => [
                    'layout' => 'side-menu',
                ],
                'title' => 'Bullion Dashboard',
            ],
            // ----------------------
            // 1. MASTER
            // ----------------------
            'master' => [
                'icon'  => 'folder',
                'title' => 'Master',
                'sub_menu' => [
                    'user' => [
                        'icon' => 'user',
                        'route_name' => 'users',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'User',
                        'permission' => 'view-users',
                        'active_routes' => ['users', 'users.*'],
                    ],
                    'client' => [
                        'icon' => 'user-plus',
                        'route_name' => 'client.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Client',
                        'permission' => 'view-clients',
                        'active_routes' => ['client', 'client.*'],
                    ],
                    'vendor' => [
                        'icon' => 'user-check',
                        'route_name' => 'vendor.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Vendor',
                        'permission' => 'view-vendors',
                        'active_routes' => ['vendor', 'vendor.*'],
                    ],
                    'department' => [
                        'icon' => 'building',
                        'route_name' => 'departments.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Department',
                        'permission' => 'view-departments',
                        'active_routes' => ['departments', 'departments.*'],
                    ],
                    'employee' => [
                        'icon' => 'briefcase',
                        'route_name' => 'employees.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Employee',
                        'permission' => 'view-employees',
                        'active_routes' => ['employees', 'employees.*'],   
                    ],
                ],
            ],

            // ----------------------
            // 2. BULLION
            // ----------------------
            // 'bullion' => [
            //     'icon'  => 'coins',
            //     'title' => 'Bullion',
            //     'sub_menu' => [
            //         // ----------------------
            //         // TRANSACTION (GROUP)
            //         // ----------------------
            //         'transaction' => [
            //             'icon' => 'shuffle',
            //             'title' => 'Transaction',
            //             'sub_menu' => [
            //                 'dealer-rate-fix' => [
            //                     'icon' => 'settings',
            //                     'route_name' => 'drfs.index',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Client Rate Fix',
            //                     'permission' => 'view-dealer-rate-fixes',
            //                     'active_routes' => ['drfs', 'drfs.*'],
            //                 ],
            //                 'bullion-rate-fix' => [
            //                     'icon' => 'bar-chart',
            //                     'route_name' => 'brfs.index',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Bullion Rate Fix',
            //                     'permission' => 'view-bullion-rate-fixes',
            //                     'active_routes' => ['brfs', 'brfs.*'],
            //                 ],
            //                 'metal-receipt' => [
            //                     'icon' => 'download',
            //                     'route_name' => 'receipts.index',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Metal Purchase',
            //                     'permission' => 'view-metal-receipts',
            //                     'active_routes' => ['receipts', 'receipts.*'],
            //                 ],
            //                 'payments' => [
            //                     'icon' => 'credit-card',
            //                     'route_name' => 'payments.index',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Bank Book',
            //                     'permission' => 'view-payments',
            //                     'active_routes' => ['payments', 'payments.*'],
            //                 ],
            //                 'manual-deal' => [
            //                     'icon' => 'file-text',
            //                     'route_name' => 'manual_deal.create',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Manual Deal',
            //                     'permission' => 'view-manual-deals', 
            //                 ],
            //             ],
            //         ],

            //         // ----------------------
            //         // REPORT (GROUP)
            //         // ----------------------
            //         'report' => [
            //             'icon' => 'bar-chart-2',
            //             'title' => 'Report',
            //             'sub_menu' => [
            //                 'bullion-ledger' => [
            //                     'icon' => 'book',
            //                     'route_name' => 'bullion_ledger',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Bullion Ledger Account',
            //                     'permission' => 'view-bullion-ledger',
            //                 ],
            //                 'booking-comparison' => [
            //                     'icon' => 'activity',
            //                     'route_name' => 'booking_comparision',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Booking Comparison',
            //                     'permission' => 'view-booking-comparison',
            //                 ],
            //                 'dealer-pending-deals' => [
            //                     'icon' => 'clock',
            //                     'route_name' => 'pending_deals',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Client Pending Deals',
            //                     'permission' => 'view-dealer-pending-deals',
            //                 ],
            //                 'bullion-pending-deals' => [
            //                     'icon' => 'clock',
            //                     'route_name' => 'bullion_pending_deals',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Bullion Pending Deals',
            //                     'permission' => 'view-bullion-pending-deals',
            //                 ],
            //             ],
            //         ],

            //         // ----------------------
            //         // MASTERS (GROUP)
            //         // ----------------------
            //         'masters' => [
            //             'icon' => 'grid',
            //             'title' => 'Masters',
            //             'sub_menu' => [
            //                 'bullions' => [
            //                     'icon' => 'database',
            //                     'route_name' => 'bullions.index',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Bullions',
            //                     'permission' => 'view-bullions',
            //                     'active_routes' => ['bullions', 'bullions.*'],
            //                 ],
            //                 // 'dealers' => [
            //                 //     'icon' => 'users',
            //                 //     'route_name' => 'dealers.index',
            //                 //     'params' => ['layout' => 'side-menu'],
            //                 //     'title' => 'Dealers',
            //                 //     'permission' => 'view-dealers',
            //                 //     'active_routes' => ['dealers', 'dealers.*'],
            //                 // ], 
            //                 'payment-modes' => [
            //                     'icon' => 'credit-card',
            //                     'route_name' => 'paymentmodes.index',
            //                     'params' => ['layout' => 'side-menu'],
            //                     'title' => 'Payment Modes',
            //                     'permission' => 'view-payment-modes',
            //                     'active_routes' => ['paymentmodes', 'paymentmodes.*'],
            //                 ],
            //             ],
            //         ],

            //     ],
            // ],

            // ----------------------
            // 3. TOOLS MANAGEMENT
            // ----------------------
            'tools-management' => [
                'icon'  => 'wrench',
                'title' => 'Tools Management',
                'sub_menu' => [
                    'product' => [
                        'icon' => 'package',
                        'route_name' => 'products.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Product',
                        'permission' => 'view-products',
                        'active_routes' => ['products', 'products.*'],
                    ],
                    'tool-purchase' => [
                        'icon' => 'file-text',
                        'route_name' => 'purchases.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Tool Purchase',
                        'permission' => 'view-tool-purchases',
                        'active_routes' => ['purchases', 'purchases.*'],
                    ],
                    'tool-issue' => [
                        'icon' => 'list-checks',
                        'route_name' => 'tool-assigns.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Tool Issue',
                        'permission' => 'view-tool-issues',
                        'active_routes' => ['tool-assigns', 'tool-assigns.*'],
                    ],
                    'tool-inventory' => [
                        'icon' => 'layers',
                        'route_name' => 'inventory-calculation.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Tool Inventory',
                        'permission' => 'view-tool-inventory',
                        'active_routes' => ['inventory-calculation', 'inventory-calculation.*'],
                    ],
                    'report' => [
                        'icon' => 'bar-chart-2',
                        'title' => 'Report',
                        'sub_menu' => [
                            'purchase-report' => [
                                'icon' => 'file-text',
                                'route_name' => 'tool-assigns.purchase-report',
                                'params' => ['layout' => 'side-menu'],
                                'title' => 'Purchase Report',
                                'permission' => 'view-purchase-reports',
                                'active_routes' => ['tool-assigns.purchase-report', 'tool-assigns.purchase-report.*'],
                            ],
                            'product-report' => [
                                'icon' => 'package',
                                'route_name' => 'tool-assigns.product-report',
                                'params' => ['layout' => 'side-menu'],
                                'title' => 'Product Report',
                                'permission' => 'view-product-reports',
                            ],
                            'department-wise-tool-assign' => [
                                'icon' => 'building',
                                'route_name' => 'tool-assigns.department-wise-report',
                                'params' => ['layout' => 'side-menu'],
                                'title' => 'Tool Assign Department Wise',
                                'permission' => 'view-department-wise-tool-assigns',
                            ],
                            'employee-wise-tool-assign' => [
                                'icon' => 'user',
                                'route_name' => 'tool-assigns.employee-wise-report',
                                'params' => ['layout' => 'side-menu'],
                                'title' => 'Emp Wise Tool Assign',
                                'permission' => 'view-employee-wise-tool-assigns',
                            ],
                        ],
                    ],
                ],
            ],

            // ----------------------
            // QUOTATION
            // ----------------------
           

            'customer-quotation' => [
                'icon' => 'file-text',
                'route_name' => 'customer.quotations.index',
                'params' => ['layout' => 'side-menu'],
                'title' => 'Quotation',
                'permission' => 'view-customer-quotations',
                'active_routes' => ['customer/quotations', 'customer/quotations.*'],
            ],

            // ----------------------
            // EXPENSE
            // ----------------------
            'Sales' => [
                'icon' => 'shopping-cart',
                'title' => 'Sales',
                'sub_menu' => [
                    'quotation' => [
                        'icon' => 'file-text',
                        'route_name' => 'quotations.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Quotation',
                        'permission' => 'view-quotations',
                        'active_routes' => ['quotations', 'quotations.*'],
                    ],
                    'expense' => [
                        'icon' => 'dollar-sign',
                        'route_name' => 'expenses.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Expense',
                        'permission' => 'view-expenses',
                        'active_routes' => ['expenses', 'expenses.*'],
                    ],

                    'dsr' => [
                        'icon' => 'clipboard',
                        'route_name' => 'dsr.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'DSR',
                        'permission' => 'view-dsr',
                        'active_routes' => ['dsr', 'dsr.*'],
                    ],
                     'visit' => [
                        'icon' => 'map-pin',
                        'route_name' => 'visits.index',
                        'params' => ['layout' => 'side-menu'],
                        'title' => 'Visit',
                        'permission' => 'view-visit',
                        'active_routes' => ['visits', 'visits.*'],
                    ],
                ],
            ],

        ];

    }

    /**
     * Get filtered menu based on user permissions
     */
    public static function filteredMenu(): array
    {
        $menu = self::menu();
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        return self::filterMenuByPermissions($menu, $user);
    }

    /**
     * Recursively filter menu items based on user permissions
     */
    private static function filterMenuByPermissions(array $menu, $user): array
    { 
        $filteredMenu = [];

        foreach ($menu as $key => $item) {
            if (isset($item['permission'])) {
                if (!$user->hasPermission($item['permission'])) {
                    continue; 
                }
            }

            if (isset($item['sub_menu'])) {
                $filteredSubMenu = self::filterMenuByPermissions($item['sub_menu'], $user);
                
                if (!empty($filteredSubMenu)) {
                    $item['sub_menu'] = $filteredSubMenu;
                    $filteredMenu[$key] = $item;
                }
            } else {
                $filteredMenu[$key] = $item;
            }
        }

        return $filteredMenu;
    }
}
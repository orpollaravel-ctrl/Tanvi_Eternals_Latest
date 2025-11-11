<?php

namespace App\Main;

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
                        'icon' => 'activity',
                        'route_name' => 'dashboard-overview-1',
                        'params' => [
                            'layout' => 'side-menu',
                        ],
                        'title' => 'Overview 1'
                    ],
                    'dashboard-overview-2' => [
                        'icon' => 'activity',
                        'route_name' => 'dashboard-overview-2',
                        'params' => [
                            'layout' => 'side-menu',
                        ],
                        'title' => 'Overview 2'
                    ],
                    'dashboard-overview-3' => [
                        'icon' => 'activity',
                        'route_name' => 'dashboard-overview-3',
                        'params' => [
                            'layout' => 'side-menu',
                        ],
                        'title' => 'Overview 3'
                    ],
                    'dashboard-overview-4' => [
                        'icon' => 'activity',
                        'route_name' => 'dashboard-overview-4',
                        'params' => [
                            'layout' => 'side-menu',
                        ],
                        'title' => 'Overview 4'
                    ]
                ]
            ],
            'users' => [
                'icon' => 'users',
                'route_name' => 'users',
                'params' => [
                    'layout' => 'side-menu'
                ],
                'title' => 'User'
            ],
            'products' => [
                'icon' => 'shopping-cart',
                'route_name' => 'products.index',
                'params' => [
                    'layout' => 'side-menu'
                ],
                'title' => 'Products'
            ],
            'purchase' => [
                'icon' => 'package',
                'title' => 'Purchase Management',
                'sub_menu' => [
                    'purchase-parties' => [
                        'icon' => 'user',
                        'route_name' => 'purchase-parties.index',
                        'params' => [
                            'layout' => 'side-menu'
                        ],
                        'title' => 'Purchase Parties'
                    ],
                    'purchases' => [
                        'icon' => 'clipboard-list',
                        'route_name' => 'purchases.index',
                        'params' => [
                            'layout' => 'side-menu'
                        ],
                        'title' => 'Purchase List'
                    ]
                ]
            ],
            'Bullions' => [
                'icon' => 'box',
                'title' => 'Bullions',
                'sub_menu' => [
                    'top-menu' => [
                        'icon' => 'activity',
                        'route_name' => 'bullion-purchase',
                        'params' => [
                            'layout' => 'top-menu'
                        ],
                        'title' => 'Bullion Purchase'
                    ],
                    'simple-menu' => [
                        'icon' => 'activity',
                        'route_name' => 'bullion-rate',
                        'params' => [
                            'layout' => 'simple-menu'
                        ],
                        'title' => 'Bullion Rate Fix'
                    ],
                    'side-menu' => [
                        'icon' => 'calculator',
                        'route_name' => 'client-rate-fix',
                        'params' => [
                            'layout' => 'side-menu'
                        ],
                        'title' => 'Client Rate Fix'
                    ],
                    'top-menu-2' => [
                        'icon' => 'activity',
                        'route_name' => 'client-rate-cut-pending',
                        'params' => [
                            'layout' => 'side-menu'
                        ],
                        'title' => 'Client Rate Cut Pending'
                    ],
                    'top-menu-3' => [
                        'icon' => 'activity',
                        'route_name' => 'payment',
                        'params' => [
                            'layout' => 'side-menu'
                        ],
                        'title' => 'Payment'
                    ],
                    'top-menu-4' => [
                        'icon' => 'activity',
                        'route_name' => 'client.index',
                        'params' => [
                            'layout' => 'side-menu'
                        ],
                        'title' => 'Client'
                    ],
                    'top-menu-5' => [
                        'icon' => 'activity',
                        'route_name' => 'vendor.index',
                        'params' => [
                            'layout' => 'side-menu'
                        ],
                        'title' => 'Vendor'
                    ],
                ]
            ],
        ];
    }
}

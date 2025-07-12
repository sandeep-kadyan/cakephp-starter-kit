<?php
/**
 * Application Settings Configuration
 *
 * This file returns an array of application-wide settings, including UI layouts, menu structures, toast notification positions, and other configurable options.
 *
 * Structure:
 * - Setting: Main configuration key
 *   - default: Default UI and toast settings
 *   - auth: Authentication-related layouts and login types
 *   - app: Application layout and toast settings
 *   - menu: Menu definitions for profile, sidebar, header, footer, and legal links
 *
 * @return array Application settings
 */

return [
    'Setting' => [
        'ajaxTableCache' => false, // Enable or disable AjaxTable cache
        'default' => [
            'toast' => 'aside' // Toast: top-left, top-right, top-center, bottom-left, bottom-right, bottom-center
        ],
        'auth' => [
            'layout' => 'split', // Layouts: split, card, muted, simple
            'login' => 'magic_login', // Login Typed: login, magic_login, social login
            'toast' => 'top-right' // Toast: top-left, top-right, top-center, bottom-left, bottom-right, bottom-center
        ],
        'app' => [
            'layout' => 'aside', // Layouts: aside or header
            'toast' => 'bottom-right' // Toast: top-left, top-right, top-center, bottom-left, bottom-right, bottom-center
        ],
        'menu' => [
            'profile' => [
                [
                    'label' => 'Profile',
                    'url' => '/users/view',
                    'icon' => 'account_circle',
                ],
                [
                    'label' => 'Log out',
                    'url' => '/logout',
                    'icon' => 'logout',
                ],
                // Add more header items as needed
            ],
            'sidebar' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'dashboard',
                    'url' => '/pages/dashboard'
                ],
                [
                    'label' => 'Users',
                    'icon' => 'supervised_user_circle',
                    'url' => '/users'
                ],
                [
                    'label' => 'Activities',
                    'icon' => 'track_changes',
                    'url' => '/activities'
                ],
                [
                    'label' => 'Settings',
                    'icon' => 'settings',
                    'url' => '#',
                ],
                [
                    'label' => 'Pages',
                    'icon' => 'description',
                    'children' => [
                        ['label' => 'Lists', 'url' => '#'],
                        ['label' => 'Categories', 'url' => '#'],
                        ['label' => 'Tags', 'url' => '#'],
                    ]
                ],
                // ... more items ...
            ],
            'sidebar_footer' => [
                [
                    'label' => 'Repository',
                    'url' => '#',
                    'icon' => 'terminal',
                ],
                [
                    'label' => 'Documentation',
                    'url' => '#',
                    'icon' => 'auto_stories',
                ],
            ],
            'header' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'dashboard',
                    'url' => '/pages/dashboard'
                ],
                [
                    'id' => 'acme-dropdown',
                    'label' => 'Acme Inc',
                    'icon' => 'business',
                    'children' => [
                        ['label' => 'Overview', 'url' => '/acme/overview'],
                        ['label' => 'Team', 'url' => '#'],
                        'children' => [
                            'label' => 'Settings',
                            'url' => '#',
                            'children' => [
                                [
                                    'label' => 'Advanced',
                                    'url' => '#',
                                    'children' => [
                                        [
                                            'label' => 'Security',
                                            'url' => '#',
                                        ],
                                        [
                                            'label' => 'Integrations',
                                            'url' => '#',
                                        ],
                                    ]
                                ],
                                [
                                    'label' => 'Notifications',
                                    'url' => '#',
                                ],
                            ]
                        ],
                    ]
                ],
                [
                    'label' => 'Playground',
                    'icon' => 'crop_square',
                    'children' => [
                        ['label' => 'History', 'url' => '#'],
                        ['label' => 'Starred', 'url' => '#'],
                        ['label' => 'Settings', 'url' => '#'],
                    ]
                ],
                [
                    'label' => 'Models',
                    'icon' => 'storage',
                    'url' => '#'
                ],
                // ... more items ...
            ],
            'footer' => [
                [
                    'label' => 'Home',
                    'url' => '/',
                    'icon' => 'home',
                ],
                [
                    'label' => 'Profile',
                    'url' => '/profile',
                    'icon' => 'person',
                ],
            ],
            'legal' => [
                [
                    'label' => 'Terms of use',
                    'url' => '#',
                    'icon' => 'home',
                ],
                [
                    'label' => 'Privacy policy',
                    'url' => '#',
                    'icon' => 'person',
                ],
            ],
        ],
    ],
];

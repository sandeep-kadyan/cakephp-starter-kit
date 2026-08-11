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
 *   - seo: Site identity and SEO settings (title, description, author, keywords, share image, etc.)
 *
 * @return array Application settings
 */

return [
    'Setting' => [
        'ajaxTableCache' => false, // Enable or disable AjaxTable cache
        'seo' => [
            'siteName' => 'CakePHP SaaS Starter Kit',
            'title' => 'CakePHP SaaS Starter Kit',
            'description' => 'A modern, production-ready CakePHP SaaS starter kit with authentication, JWT, 2FA, dark mode, and a beautiful Tailwind CSS dashboard.',
            'keywords' => 'cakephp, saas, starter kit, tailwind css, authentication, jwt, 2fa, vite, vue',
            'author' => 'Sandeep Kadyan',
            'authorEmail' => 'sandeepkadyan91@gmail.com',
            'canonical' => null, // Set to force a canonical URL, null auto-detects
            'image' => '/img/cake-logo.png',
            'twitter' => '@cakephp',
            'robots' => 'index, follow',
            'themeColor' => '#fafaf9',
            'organization' => [
                'name' => 'CakePHP SaaS Starter Kit',
                'url' => '/',
                'logo' => '/img/cake-logo.png',
            ],
        ],
        'default' => [
            'toast' => 'aside' // Toast: top-left, top-right, top-center, bottom-left, bottom-right, bottom-center
        ],
        'auth' => [
            'layout' => 'split', // Layouts: split, card, muted, simple
            'login' => 'login', // Login Typed: login, magic_login, social login
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

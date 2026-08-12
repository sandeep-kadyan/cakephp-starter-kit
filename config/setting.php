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
            'login' => 'login', // Login Typed: login, magic_login, social
            'toast' => 'top-right', // Toast: top-left, top-right, top-center, bottom-left, bottom-right, bottom-center
            // Social login providers shown on the login page. Each entry needs
            // a `name` (label) and `url` (OAuth authorize endpoint or route).
            // Leave empty to disable social login.
            'social' => [
                // [
                //     'name' => 'Google',
                //     'url' => '/auth/google',
                // ],
                // [
                //     'name' => 'GitHub',
                //     'url' => '/auth/github',
                // ],
            ],
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
                    'icon' => 'circle-user',
                ],
                [
                    'label' => 'Settings',
                    'url' => '/settings',
                    'icon' => 'settings',
                ],
                [
                    'label' => 'Log out',
                    'url' => '/logout',
                    'icon' => 'log-out',
                ],
                // Add more header items as needed
            ],
            'sidebar' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'layout-dashboard',
                    'url' => '/pages/dashboard'
                ],
                [
                    'label' => 'Users',
                    'icon' => 'users',
                    'url' => '/users'
                ],
                [
                    'label' => 'Activities',
                    'icon' => 'activity',
                    'url' => '/activities'
                ],
                [
                    'label' => 'Pages',
                    'icon' => 'file-text',
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
                    'icon' => 'book-open',
                ],
            ],
            'header' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'layout-dashboard',
                    'url' => '/pages/dashboard'
                ],
                [
                    'id' => 'acme-dropdown',
                    'label' => 'Acme Inc',
                    'icon' => 'building-2',
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
                    'icon' => 'square',
                    'children' => [
                        ['label' => 'History', 'url' => '#'],
                        ['label' => 'Starred', 'url' => '#'],
                        ['label' => 'Settings', 'url' => '#'],
                    ]
                ],
                [
                    'label' => 'Models',
                    'icon' => 'database',
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
                    'icon' => 'user',
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
                    'icon' => 'user',
                ],
            ],
        ],
    ],
];

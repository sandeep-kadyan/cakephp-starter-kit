<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Routing\Router;
use Cake\View\Helper;

/**
 * MenuHelper provides utilities to render various types of navigation menus (sidebar, header, footer, etc.) in CakePHP views.
 *
 * This helper supports unlimited nesting, active state detection, and flexible rendering for different menu types.
 *
 * Use Cases:
 * - Render a sidebar or header menu with nested items and icons.
 * - Display a profile or legal menu in your application's layout.
 * - Support dynamic menus from configuration or database.
 *
 * How to Use:
 * 1. Ensure MenuHelper is loaded in your AppView or controller.
 * 2. In your template or element, call `$this->Menu->render('sidebar', $menus)` or another menu type.
 * 3. Pass additional options for custom rendering if needed.
 *
 * Example:
 * ```php
 * // In your template or element (e.g., templates/element/menu/sidebar.php)
 * echo $this->Menu->render('sidebar', $menus, ['class' => 'custom-sidebar-menu']);
 *
 * // For a header menu:
 * echo $this->Menu->render('header', $menus);
 * ```
 *
 * @package App\View\Helper
 */
class MenuHelper extends Helper
{
    /**
     * Helpers used by this helper.
     *
     * @var array<string, mixed> List of helpers used by MenuHelper.
     */
    protected array $helpers = ['Html'];

    /**
     * Render a menu by type (e.g., 'sidebar') using the provided menu data and options.
     *
     * @param string $menu The menu type to render (e.g., 'sidebar').
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param array<string, mixed> $options Additional options for rendering (optional).
     * @return string Rendered HTML for the menu.
     */
    public function render(string $menu, array $menus, array $options = []): string
    {
        // Dispatch to the appropriate menu rendering method based on menu type
        return match ($menu) {
            'profile' => $this->profile($menus, $options),
            'sidebar' => $this->sidebar($menus, $options),
            'sidebar_footer' => $this->sidebarFooter($menus, $options),
            'header' => $this->header($menus, $options),
            'footer' => $this->footer($menus, $options),
            'legal' => $this->legal($menus, $options),
            default => '',
        };
    }

    /**
     * Render the profile menu HTML (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param array<string, mixed> $options Additional options for rendering (optional).
     * @return string Rendered HTML for the profile menu.
     */
    public function profile(array $menus, array $options = []): string
    {
        $currentUrl = Router::url(null);

        // Render the profile menu with current URL context
        return $this->renderProfileMenu($menus, $currentUrl);
    }

    /**
     * Render the sidebar menu HTML (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param array<string, mixed> $options Additional options for rendering (optional).
     * @return string Rendered HTML for the sidebar menu.
     */
    public function sidebar(array $menus, array $options = []): string
    {
        $currentUrl = Router::url(null);

        // Render the sidebar menu with current URL context
        return $this->renderSidebarMenu($menus, $currentUrl);
    }

    /**
     * Render the sidebar footer menu HTML (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param array<string, mixed> $options Additional options for rendering (optional).
     * @return string Rendered HTML for the sidebar footer menu.
     */
    public function sidebarFooter(array $menus, array $options = []): string
    {
        $currentUrl = Router::url(null);

        // Render the sidebar footer menu with current URL context
        return $this->renderSidebarFooterMenu($menus, $currentUrl);
    }

    /**
     * Render the header menu HTML (supports unlimited nesting, dropdowns for nested items).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param array<string, mixed> $options Additional options for rendering (optional).
     * @return string Rendered HTML for the header menu.
     */
    public function header(array $menus, array $options = []): string
    {
        $currentUrl = Router::url(null);

        // Render the header menu with current URL context
        return $this->renderHeaderMenu($menus, $currentUrl);
    }

    /**
     * Render the footer menu HTML (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param array<string, mixed> $options Additional options for rendering (optional).
     * @return string Rendered HTML for the footer menu.
     */
    public function footer(array $menus, array $options = []): string
    {
        $currentUrl = Router::url(null);

        // Render the footer menu with current URL context
        return $this->renderFooterMenu($menus, $currentUrl);
    }

    /**
     * Render the legal menu HTML (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param array<string, mixed> $options Additional options for rendering (optional).
     * @return string Rendered HTML for the legal menu.
     */
    public function legal(array $menus, array $options = []): string
    {
        $currentUrl = Router::url(null);

        // Render the legal menu with current URL context
        return $this->renderLegalMenu($menus, $currentUrl);
    }

    /**
     * Render the profile menu HTML recursively (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param string $currentUrl The current URL for active state detection.
     * @param string $parentKey The parent menu key for unique IDs (optional).
     * @return string Rendered HTML for the profile menu.
     */
    protected function renderProfileMenu(array $menus, string $currentUrl, string $parentKey = ''): string
    {
        $html = '<ul class="p-2">';
        foreach ($menus as $index => $item) {
            $isActive = $this->isActive($item);
            $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
            if ($hasChildren) {
                // Initialize open state based on whether any child is active
                $initialOpenState = $isActive['open'] ? 'true' : 'false';
                // Render dropdown for menu items with children
                $html .= '<li class="relative" x-data="{ open: ' . $initialOpenState . ' }">';
                $html .= '<button type="button" @click="open = !open" class="flex items-center gap-3 px-3 py-2 hover:bg-accent hover:text-accent-foreground rounded-lg">';
                $html .= '<i data-lucide="' . ($item['icon'] ?? 'chevron-right') . '" class="text-muted-foreground"></i>';
                $html .= '<span class="text-muted-foreground">' . __(h($item['label'])) . '</span>';
                $html .= '<i data-lucide="chevron-down" class="ml-2"></i>';
                $html .= '</button>';
                $html .= '<ul x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 bg-popover border-border rounded shadow-lg z-50">';
                foreach ($item['children'] as $child) {
                    $html .= '<li>';
                    $html .= $this->Html->link(
                        '<i data-lucide="' . ($child['icon'] ?? 'minus') . '" class="text-muted-foreground"></i><span class="text-muted-foreground">' . __(h($child['label'])) . '</span>',
                        $child['url'] ?? '#',
                        ['class' => 'flex items-center gap-3 px-3 py-2 hover:bg-accent hover:text-accent-foreground rounded-lg', 'escape' => false],
                    );
                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                // Render single menu item
                $activeClass = $isActive['active'] ? ' bg-accent text-accent-foreground' : '';
                $html .= '<li>';
                $html .= $this->Html->link(
                    '<i data-lucide="' . ($item['icon'] ?? 'minus') . '" class="text-muted-foreground"></i><span class="text-muted-foreground">' . __(h($item['label'])) . '</span>',
                    $item['url'] ?? '#',
                    ['class' => 'flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-accent hover:text-accent-foreground text-muted-foreground' . $activeClass, 'escape' => false],
                );
                $html .= '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Render the sidebar menu HTML recursively (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param string $currentUrl The current URL for active state detection.
     * @param string $parentKey The parent menu key for unique IDs (optional).
     * @return string Rendered HTML for the sidebar menu.
     */
    protected function renderSidebarMenu(array $menus, string $currentUrl, string $parentKey = ''): string
    {
        $isRoot = $parentKey === '';
        // For root level, we need to find which index should be open initially
        $initialOpenIndex = null;
        if ($isRoot) {
            foreach ($menus as $index => $item) {
                $isActive = $this->isActive($item);
                if ($isActive['open']) {
                    $initialOpenIndex = $index;
                    break;
                }
            }
        }
        $html = $isRoot ? '<nav id="nav-sidebar" class="space-y-2 mb-6 overscroll-auto" x-data="{ openIndex: ' . ($initialOpenIndex ?? 'null') . ' }">' : '<div class="pl-2">';
        foreach ($menus as $index => $item) {
            $isActive = $this->isActive($item);
            $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
            $slug = $this->slugify(($parentKey ? $parentKey . '-' : '') . ($item['id'] ?? $item['label'] ?? 'item') . '-' . $index);
            $activeClass = $isActive['active'] ? ' bg-accent text-accent-foreground' : '';
            $openClass = $isActive['open'] ? ' text-accent-foreground' : '';
            if ($hasChildren) {
                if ($isRoot) {
                    $html .= '<div class="relative mb-2">';
                    $html .= sprintf(
                        '<button type="button" @click="openIndex = openIndex === %1$d ? null : %1$d" class="flex items-center w-full p-2 rounded-md text-sm text-muted-foreground hover:bg-accent hover:text-accent-foreground %2$s%3$s">',
                        $index,
                        $activeClass,
                        $openClass,
                    );
                    $html .= '<i data-lucide="' . h($item['icon'] ?? 'chevron-right') . '" class="text-muted-foreground"></i>';
                    $html .= '<span class="flex-1 text-left text-sm ml-2" :class="sidebarMini ? \'hidden\' : \'inline\'">' . h($item['label']) . '</span>';
                    $html .= '<i data-lucide="chevron-down" class="ml-auto text-xs transition-transform duration-300" :class="{ \'rotate-180\': openIndex === ' . $index . ', \'hidden\': sidebarMini, \'inline\': !sidebarMini }"></i>';
                    $html .= '</button>';
                    $html .= '<div x-show="openIndex === ' . $index . '" x-transition class="pl-4 pt-2 space-y-2">';
                    $html .= $this->renderSidebarMenu($item['children'], $currentUrl, $slug);
                    $html .= '</div></div>';
                } else {
                    // For nested items, initialize open state based on whether any child is active
                    $initialOpenState = $isActive['open'] ? 'true' : 'false';
                    $html .= '<div class="relative mb-2" x-data="{ open: ' . $initialOpenState . ' }">';
                    $html .= '<button type="button" @click="open = !open" class="flex items-center w-full p-2 rounded-md text-sm text-muted-foreground hover:bg-accent hover:text-accent-foreground">';
                    $html .= '<i data-lucide="' . h($item['icon'] ?? 'chevron-right') . '" class="text-muted-foreground"></i>';
                    $html .= '<span class="flex-1 text-left text-sm ml-2" :class="sidebarMini ? \'hidden\' : \'inline\'">' . h($item['label']) . '</span>';
                    $html .= '<i data-lucide="chevron-down" class="ml-auto text-xs transition-transform duration-300" :class="{ \'rotate-180\': open, \'hidden\': sidebarMini, \'inline\': !sidebarMini }"></i>';
                    $html .= '</button>';
                    $html .= '<div x-show="open" x-transition class="pl-4 pt-2 space-y-2">';
                    $html .= $this->renderSidebarMenu($item['children'], $currentUrl, $slug);
                    $html .= '</div></div>';
                }
            } else {
                $html .= $this->Html->link(
                    '<i data-lucide="' . h($item['icon'] ?? 'minus') . '" class="text-muted-foreground"></i><span class="ml-2" :class="sidebarMini ? \'hidden\' : \'inline\'">' . h($item['label']) . '</span>',
                    $item['url'] ?? '#',
                    [
                        'class' => 'flex items-center p-2 rounded-md text-sm text-muted-foreground hover:bg-accent hover:text-accent-foreground' . $activeClass,
                        'escape' => false,
                    ],
                );
            }
        }
        $html .= $isRoot ? '</nav>' : '</div>';

        return $html;
    }

    /**
     * Render the header menu HTML recursively (supports unlimited nesting and dropdowns).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param string $currentUrl The current URL for active state detection.
     * @param string $parentKey The parent menu key for unique IDs (optional).
     * @return string Rendered HTML for the header menu.
     */
    protected function renderHeaderMenu(array $menus, string $currentUrl, string $parentKey = ''): string
    {
        $isTop = $parentKey === '';
        // Mega menu for top-level only
        if ($isTop) {
            $html = '<ul class="flex gap-4 items-center text-foreground">';
            foreach ($menus as $index => $item) {
                $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
                $slug = $this->slugify(($parentKey ? $parentKey . '-' : '') . ($item['id'] ?? $item['label'] ?? 'item') . '-' . $index);
                if ($hasChildren) {
                    $html .= '<li class="relative group" x-data="{ open: false }">';
                    $html .= '<button type="button" @click="open = !open" @mouseenter="open = true" @mouseleave="open = false" class="flex items-center gap-1 px-3 py-2 hover:bg-accent hover:text-accent-foreground rounded text-foreground focus:outline-none">';
                    $html .= h($item['label']);
                    $html .= '<i data-lucide="chevron-down" class="text-xs ml-1"></i>';
                    $html .= '</button>';
                    // Mega menu dropdown
                    $html .= '<div x-show="open" @mouseenter="open = true" @mouseleave="open = false" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-screen max-w-3xl bg-popover border-border rounded-lg shadow-lg z-50 p-6 hidden md:block">';
                    $html .= '<div class="grid grid-cols-2 md:grid-cols-3 gap-6">';
                    foreach ($item['children'] as $child) {
                        $html .= '<div>';
                        $html .= '<div class="font-semibold mb-2 flex items-center">';
                        $html .= '<i data-lucide="' . h($child['icon'] ?? 'minus') . '" class="mr-2"></i>';
                        $html .= h($child['label']);
                        $html .= '</div>';
                        if (!empty($child['children'])) {
                            foreach ($child['children'] as $grandchild) {
                                $html .= $this->Html->link(
                                    '<i data-lucide="' . h($grandchild['icon'] ?? 'minus') . '" class="mr-1 text-xs align-middle"></i>' . h($grandchild['label']),
                                    $grandchild['url'] ?? '#',
                                    ['class' => 'block px-2 py-1 text-sm hover:bg-accent hover:text-accent-foreground text-foreground hover:bg-accent hover:text-accent-foreground rounded', 'escape' => false],
                                );
                            }
                        } else {
                            $html .= $this->Html->link(
                                h($child['label']),
                                $child['url'] ?? '#',
                                ['class' => 'block px-2 py-1 text-sm hover:bg-accent hover:text-accent-foreground text-foreground hover:bg-accent hover:text-accent-foreground rounded'],
                            );
                        }
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';
                    // Mobile: stack as vertical dropdown
                    $html .= '<ul x-show="open" @click.away="open = false" x-transition class="md:hidden absolute left-0 mt-2 w-64 bg-popover border-border rounded shadow-lg z-50">';
                    foreach ($item['children'] as $child) {
                        $html .= '<li>';
                        $html .= $this->Html->link(
                            '<i data-lucide="' . h($child['icon'] ?? 'minus') . '" class="mr-2"></i>' . h($child['label']),
                            $child['url'] ?? '#',
                            ['class' => 'block px-3 py-2 hover:bg-accent hover:text-accent-foreground text-foreground hover:bg-accent hover:text-accent-foreground rounded', 'escape' => false],
                        );
                        if (!empty($child['children'])) {
                            $html .= '<ul class="pl-4">';
                            foreach ($child['children'] as $grandchild) {
                                $html .= '<li>';
                                $html .= $this->Html->link(
                                    '<i data-lucide="' . h($grandchild['icon'] ?? 'minus') . '" class="mr-1 text-xs align-middle"></i>' . h($grandchild['label']),
                                    $grandchild['url'] ?? '#',
                                    ['class' => 'block px-2 py-1 text-sm hover:bg-accent hover:text-accent-foreground text-foreground hover:bg-accent hover:text-accent-foreground rounded', 'escape' => false],
                                );
                                $html .= '</li>';
                            }
                            $html .= '</ul>';
                        }
                        $html .= '</li>';
                    }
                    $html .= '</ul>';
                    $html .= '</li>';
                } else {
                    $html .= '<li>';
                    $html .= $this->Html->link(
                        h($item['label']),
                        $item['url'] ?? '#',
                        ['class' => 'block px-3 py-2 hover:bg-accent hover:text-accent-foreground text-foreground hover:bg-accent hover:text-accent-foreground rounded'],
                    );
                    $html .= '</li>';
                }
            }
            $html .= '</ul>';

            return $html;
        }
        // For nested children, fallback to simple dropdown
        $html = '<ul class="absolute left-0 mt-2 w-48 bg-popover border-border rounded shadow-lg z-50" x-show=\'open\' x-transition>';
        foreach ($menus as $index => $item) {
            $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
            $slug = $this->slugify(($parentKey ? $parentKey . '-' : '') . ($item['id'] ?? $item['label'] ?? 'item') . '-' . $index);
            if ($hasChildren) {
                $html .= '<li class="relative group" x-data="{ open: false }">';
                $html .= sprintf(
                    '<button type="button" @click="open = !open" class="flex items-center gap-1 px-3 py-2 hover:bg-accent hover:text-accent-foreground rounded focus:outline-none">',
                );
                $html .= h($item['label']);
                $html .= '<i data-lucide="chevron-down" class="text-xs ml-1"></i>';
                $html .= '</button>';
                $html .= '<ul x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 bg-popover border-border rounded text-foreground hover:bg-accent hover:text-accent-foreground shadow-lg z-50">';
                $html .= $this->renderHeaderMenu($item['children'], $currentUrl, $slug);
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                $html .= '<li>';
                $html .= $this->Html->link(
                    h($item['label']),
                    $item['url'] ?? '#',
                    ['class' => 'block px-3 py-2 hover:bg-accent hover:text-accent-foreground text-foreground hover:bg-accent hover:text-accent-foreground rounded'],
                );
                $html .= '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Render the sidebar footer menu HTML recursively (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param string $currentUrl The current URL for active state detection.
     * @param string $parentKey The parent menu key for unique IDs (optional).
     * @return string Rendered HTML for the sidebar footer menu.
     */
    protected function renderSidebarFooterMenu(array $menus, string $currentUrl, string $parentKey = ''): string
    {
        $html = '<nav class="space-y-2" data-nav-sidebar>';
        foreach ($menus as $index => $item) {
            $isActive = $this->isActive($item);
            $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
            if ($hasChildren) {
                // Initialize open state based on whether any child is active
                $initialOpenState = $isActive['open'] ? 'true' : 'false';
                $html .= '<div class="relative" x-data="{ open: ' . $initialOpenState . ' }">';
                $html .= '<button type="button" @click="open = !open" class="flex items-center p-2 rounded focus:outline-none gap-3 text-sm text-muted-foreground hover:bg-accent hover:text-accent-foreground">';
                $html .= '<i data-lucide="' . ($item['icon'] ?? 'chevron-right') . '" class="text-muted-foreground"></i>';
                $html .= '<span class="ml-2" :class="sidebarMini ? \'hidden\' : \'inline\'">' . __(h($item['label'])) . '</span>';
                $html .= '<i data-lucide="chevron-down" class="ml-2" :class="sidebarMini ? \'hidden\' : \'inline\'"></i>';
                $html .= '</button>';
                $html .= '<div x-show="open" @click.away="open = false" x-transition class="pl-4 pt-2 space-y-2">';
                foreach ($item['children'] as $child) {
                    $html .= $this->Html->link(
                        '<i data-lucide="' . ($child['icon'] ?? 'minus') . '" class="text-muted-foreground"></i><span class="ml-2" :class="sidebarMini ? \'hidden\' : \'inline\'">' . __(h($child['label'])) . '</span>',
                        $child['url'] ?? '#',
                        ['class' => 'flex items-center p-2 rounded focus:outline-none gap-3 text-sm text-muted-foreground hover:bg-accent hover:text-accent-foreground', 'escape' => false],
                    );
                }
                $html .= '</div>';
                $html .= '</div>';
            } else {
                $activeClass = $isActive['active'] ? ' bg-accent text-accent-foreground' : '';
                $html .= $this->Html->link(
                    '<i data-lucide="' . ($item['icon'] ?? 'minus') . '" class="text-muted-foreground"></i><span class="ml-2" :class="sidebarMini ? \'hidden\' : \'inline\'">' . __(h($item['label'])) . '</span>',
                    $item['url'] ?? '#',
                    ['class' => 'flex items-center p-2 rounded focus:outline-none gap-3 text-sm text-muted-foreground hover:bg-accent hover:text-accent-foreground' . $activeClass, 'escape' => false],
                );
            }
        }
        $html .= '</nav>';

        return $html;
    }

    /**
     * Render the footer menu HTML recursively (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param string $currentUrl The current URL for active state detection.
     * @param string $parentKey The parent menu key for unique IDs (optional).
     * @return string Rendered HTML for the footer menu.
     */
    protected function renderFooterMenu(array $menus, string $currentUrl, string $parentKey = ''): string
    {
        $html = '<ul>';
        foreach ($menus as $index => $item) {
            $isActive = $this->isActive($item);
            $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
            if ($hasChildren) {
                // Initialize open state based on whether any child is active
                $initialOpenState = $isActive['open'] ? 'true' : 'false';
                $html .= '<li class="relative" x-data="{ open: ' . $initialOpenState . ' }">';
                $html .= '<button type="button" @click="open = !open" class="flex items-center p-2 gap-3 text-sm hover:bg-accent hover:text-accent-foreground">';
                $html .= '<i data-lucide="' . ($item['icon'] ?? 'chevron-right') . '"></i>';
                $html .= '<span>' . __(h($item['label'])) . '</span>';
                $html .= '<i data-lucide="chevron-down" class="ml-2"></i>';
                $html .= '</button>';
                $html .= '<ul x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 bg-popover border-border rounded shadow-lg z-50">';
                foreach ($item['children'] as $child) {
                    $html .= '<li>';
                    $html .= $this->Html->link(
                        '<i data-lucide="' . ($child['icon'] ?? 'minus') . '"></i><span>' . __(h($child['label'])) . '</span>',
                        $child['url'] ?? '#',
                        ['class' => 'flex items-center p-2 gap-3 text-sm hover:bg-accent hover:text-accent-foreground', 'escape' => false],
                    );
                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                $activeClass = $isActive['active'] ? ' bg-accent text-accent-foreground' : '';
                $html .= '<li>';
                $html .= $this->Html->link(
                    '<i data-lucide="' . ($item['icon'] ?? 'minus') . '"></i><span>' . __(h($item['label'])) . '</span>',
                    $item['url'] ?? '#',
                    ['class' => 'flex items-center p-2 gap-3 text-sm hover:bg-accent hover:text-accent-foreground' . $activeClass, 'escape' => false],
                );
                $html .= '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Render the legal menu HTML recursively (supports unlimited nesting).
     *
     * @param array<int, array<string, mixed>> $menus The menu data array.
     * @param string $currentUrl The current URL for active state detection.
     * @param string $parentKey The parent menu key for unique IDs (optional).
     * @return string Rendered HTML for the legal menu.
     */
    protected function renderLegalMenu(array $menus, string $currentUrl, string $parentKey = ''): string
    {
        $html = '<ul>';
        foreach ($menus as $index => $item) {
            $isActive = $this->isActive($item);
            $hasChildren = isset($item['children']) && is_array($item['children']) && count($item['children']) > 0;
            if ($hasChildren) {
                // Initialize open state based on whether any child is active
                $initialOpenState = $isActive['open'] ? 'true' : 'false';
                $html .= '<li class="relative" x-data="{ open: ' . $initialOpenState . ' }">';
                $html .= '<button type="button" @click="open = !open" class="flex items-center p-2 gap-3 text-sm hover:bg-accent hover:text-accent-foreground">';
                $html .= '<i data-lucide="' . ($item['icon'] ?? 'chevron-right') . '"></i>';
                $html .= '<span class="text-muted-foreground">' . __(h($item['label'])) . '</span>';
                $html .= '<i data-lucide="chevron-down" class="ml-2"></i>';
                $html .= '</button>';
                $html .= '<ul x-show="open" @click.away="open = false" x-transition class="absolute left-0 mt-2 w-48 bg-popover border-border rounded shadow-lg z-50">';
                foreach ($item['children'] as $child) {
                    $html .= '<li>';
                    $html .= $this->Html->link(
                        '<i data-lucide="' . ($child['icon'] ?? 'minus') . '"></i><span class="text-muted-foreground">' . __(h($child['label'])) . '</span>',
                        $child['url'] ?? '#',
                        ['class' => 'flex items-center p-2 gap-3 text-sm hover:bg-accent hover:text-accent-foreground', 'escape' => false],
                    );
                    $html .= '</li>';
                }
                $html .= '</ul>';
                $html .= '</li>';
            } else {
                $activeClass = $isActive['active'] ? ' bg-accent text-accent-foreground' : '';
                $html .= '<li>';
                $html .= $this->Html->link(
                    '<i data-lucide="' . ($item['icon'] ?? 'minus') . '"></i><span class="text-muted-foreground">' . __(h($item['label'])) . '</span>',
                    $item['url'] ?? '#',
                    ['class' => 'flex items-center p-2 gap-3 text-sm hover:bg-accent hover:text-accent-foreground' . $activeClass, 'escape' => false],
                );
                $html .= '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Generate a URL-friendly slug from a string for menu item IDs/classes.
     *
     * @param string $string The input string to slugify.
     * @return string The slugified string.
     */
    protected function slugify(string $string): string
    {
        // Replace non-alphanumeric characters with dashes and lowercase the result
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $string));
    }

    /**
     * Determine if a menu item is active or open based on the current controller, action, and plugin.
     *
     * This method intelligently matches menu items against the current request by analyzing
     * the controller, action, and plugin parameters. It supports both CakePHP URL arrays
     * and string URLs, with fallback logic for different matching scenarios.
     *
     * Matching Logic:
     * 1. **CakePHP URL Arrays**: Matches controller, action, and plugin parameters exactly
     * 2. **String URLs**: Exact URL matching with fallback to controller-only matching
     * 3. **Plugin Handling**: Properly handles plugin routes vs main app routes
     * 4. **Recursive Children**: Checks if any child menu items are active to determine parent state
     *
     * Examples:
     * - Menu: ['controller' => 'Settings', 'action' => 'add'] matches /settings/add
     * - Menu: ['controller' => 'Users'] matches any action in Users controller
     * - Menu: '/admin/users' matches exact URL or falls back to 'users' controller
     *
     * @param array<string, mixed> $item The menu item data containing 'url' and optional 'children'.
     * @return array<string, bool> Returns ['active' => bool, 'open' => bool] indicating if item is active or has active children.
     */
    protected function isActive(array $item): array
    {
        // Get current request information from the view context
        $request = $this->getView()->getRequest();
        $currentController = $request->getParam('controller'); // e.g., 'settings', 'users'
        $currentAction = $request->getParam('action');         // e.g., 'index', 'add', 'edit'
        $currentPlugin = $request->getParam('plugin');         // e.g., 'admin', 'blog' or null for main app

        // Initialize active state - will be set to true if this menu item matches current request
        $active = false;

        // Only process if the menu item has a URL defined
        if (isset($item['url'])) {
            $menuUrl = $item['url'];

            // Handle CakePHP URL arrays (e.g., ['controller' => 'Settings', 'action' => 'add'])
            if (is_array($menuUrl)) {
                // Extract controller, action, and plugin from menu URL array
                $menuController = $menuUrl['controller'] ?? null; // Target controller
                $menuAction = $menuUrl['action'] ?? null;         // Target action (optional)
                $menuPlugin = $menuUrl['plugin'] ?? null;         // Target plugin (optional)

                // First, check if the controller matches (case-insensitive comparison)
                if ($menuController && strtolower($menuController) === strtolower($currentController)) {
                    // If action is specified in menu, we need to match it exactly
                    if ($menuAction) {
                        if (strtolower($menuAction) === strtolower($currentAction)) {
                            // Controller and action match, now check plugin compatibility
                            if ($menuPlugin) {
                                // Menu specifies a plugin - must match current plugin exactly
                                if (strtolower($menuPlugin) === strtolower($currentPlugin)) {
                                    $active = true; // Full match: controller + action + plugin
                                }
                            } else {
                                // Menu doesn't specify plugin - only match if current request is NOT from a plugin
                                if (!$currentPlugin) {
                                    $active = true; // Match: controller + action in main app
                                }
                            }
                        }
                    } else {
                        // No action specified in menu - match any action in the specified controller
                        if ($menuPlugin) {
                            // Menu specifies plugin - must match current plugin
                            if (strtolower($menuPlugin) === strtolower($currentPlugin)) {
                                $active = true; // Match: controller + plugin (any action)
                            }
                        } else {
                            // Menu doesn't specify plugin - only match main app controller
                            if (!$currentPlugin) {
                                $active = true; // Match: controller in main app (any action)
                            }
                        }
                    }
                }
            } else {
                // Handle string URLs (e.g., '/admin/users', '/settings')
                // First attempt: exact URL matching against current request target
                $active = $menuUrl === $request->getRequestTarget();

                // Second attempt: fallback to controller-only matching
                // Remove slashes and compare with current controller name
                if ($active == false) {
                    $active = str_replace('/', '', $menuUrl) === strtolower($currentController);
                }
            }
        }

        // Initialize open state - will be true if any child menu items are active
        $open = false;

        // Recursively check if any child menu items are active
        // This ensures parent dropdowns open when child items are active
        if (isset($item['children']) && is_array($item['children'])) {
            foreach ($item['children'] as $child) {
                // Recursively check each child's active state
                $childActive = $this->isActive($child);

                // If child is active or has active children, mark parent as open
                if ($childActive['active'] || $childActive['open']) {
                    $open = true;
                    break; // No need to check other children if one is active
                }
            }
        }

        // Return both active and open states for the menu item
        return ['active' => $active, 'open' => $open];
    }
}

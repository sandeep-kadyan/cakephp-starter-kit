<?php
declare(strict_types=1);

namespace App\View\Cell;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\View\Cell;
use Exception;

/**
 * MenuCell renders various types of menus (profile, sidebar, header, footer, etc.) in CakePHP views.
 *
 * This cell fetches menu data from configuration or the database and provides a flexible way to display
 * navigation menus in different parts of your application's layout.
 *
 * Use Cases:
 * - Render a user profile menu in the sidebar or header.
 * - Display a dynamic sidebar or footer menu based on configuration or database entries.
 * - Show legal or custom menus in the application layout.
 *
 * How to Use:
 * 1. Ensure the MenuCell is available in your application (default in App\View\Cell).
 * 2. In your template or layout, use the cell() helper to render the desired menu:
 *    `$this->cell('Menu::display', ['sidebar'])`
 * 3. Optionally pass additional options for custom menu rendering.
 *
 * Example:
 * ```php
 * // In your template or layout file (e.g., templates/element/menu/sidebar.php)
 * echo $this->cell('Menu::display', ['sidebar', ['class' => 'custom-sidebar-menu']]);
 *
 * // For a profile menu:
 * echo $this->cell('Menu::display', ['profile']);
 *
 * // For a footer menu with extra options:
 * echo $this->cell('Menu::display', ['footer', ['theme' => 'dark']]);
 * ```
 *
 * @package App\View\Cell
 */
class MenuCell extends Cell
{
    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array<string, mixed>
     */
    protected array $_validCellOptions = ['menu', 'options'];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display($menu, $options = []): void
    {
        $menus = match ($menu) {
            'profile' => $this->getMenus($menu, $options),
            'sidebar' => $this->getMenus($menu, $options),
            'sidebar_footer' => $this->getMenus($menu, $options),
            'header' => $this->getMenus($menu, $options),
            'footer' => $this->getMenus($menu, $options),
            'legal' => $this->getMenus($menu, $options),
        };

        $this->set(compact('menu', 'menus', 'options'));
    }

    /**
     * Retrieve menu data from configuration or database.
     *
     * @param string $menu The menu type or identifier to fetch.
     * @param array<string, mixed> $options Additional options for menu retrieval.
     * @return array<string, mixed> The menu data as an associative array.
     */
    protected function getMenus(string $menu, array $options = []): array
    {
        $menus = Configure::read('Setting.menu.' . $menu);

        try {
            $menus = TableRegistry::getTableLocator()->get('Menus');
            $menus->find()->where(['menu' => $menu])->all()->toArray();
        } catch (Exception $e) {
        }

        return $menus;
    }
}

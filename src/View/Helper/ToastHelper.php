<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\View\View;

class ToastHelper extends Helper
{
    /**
     * Default configuration
     *
     * @var array<string, mixed>
     */
    protected array $helpers = ['Html'];

    /**
     * List of supported flash types.
     *
     * @var array<string>
     */
    protected array $types = ['success', 'error', 'warning', 'info', 'promise', 'action', 'description', 'default'];

    /**
     * Default configuration for ToastHelper.
     *
     * @var array<string, mixed>
     *   - position: string (Tailwind classes for container position)
     *   - display: string ("all" or "expand")
     */
    protected array $defaultConfig = [
        'position' => 'bottom-right', // Position: top-left, top-right, top-center, bottom-left, bottom-right, bottom-center
        'display' => 'expand', // Dsiplay: 'all' (show all at once) or 'expand' (one by one)
    ];

    /**
     * Merged configuration (default + custom)
     *
     * @var array<string, mixed>
     */
    protected array $config = [];

    /**
     * Constructor
     *
     * @param \Cake\View\View $view The view object.
     * @param array<string, mixed> $config Custom configuration.
     */
    public function __construct(View $view, array $config = [])
    {
        parent::__construct($view, $config);
        $this->config = array_merge($this->defaultConfig, $config);
    }

    /**
     * Render toast notifications for all flash messages.
     *
     * @return string
     */
    public function render(): string
    {
        $output = '';
        $flashes = $this->getView()->getRequest()->getSession()->read('Flash');
        if (!$flashes) {
            return '';
        }

        $toasts = [];
        $flashes = $flashes['flash'];

        // Also handle any custom/default types
        foreach ($flashes as $flash) {
            if (!in_array($flash['element'], $this->types, true)) {
                $toasts[] = $this->toastScript($flash['message'], $flash['element'], $flash['params'] ?? []);
            }
        }

        // Clear flash messages after rendering
        $this->getView()->getRequest()->getSession()->delete('Flash');
        if (empty($toasts)) {
            return '';
        }

        // If debug is on then show toast only in left side
        if (Configure::read('debug')) {
            $this->config['position'] = Configure::read('Setting.auth.toast') ?? 'top-right';
            if ($this->getView()->getRequest()->getAttribute('identity')) {
                $this->config['position'] = Configure::read('Setting.app.toast') ?? 'top-right';
            }
        }

        // Position: top-left, top-right, top-center, bottom-left, bottom-right, bottom-center
        $position = match ($this->config['position']) {
            'top-left' => 'top-2 start-2',
            'top-right' => 'top-2 end-2',
            'top-center' => 'top-2 left-1/2 -translate-x-1/2',
            'bottom-left' => 'bottom-2 start-2',
            'bottom-right' => 'bottom-2 end-2',
            'bottom-center' => 'bottom-2 left-1/2 -translate-x-1/2',
            default => 'bottom-2 end-2',
        };

        $isTop = str_contains($this->config['position'], 'top');
        $transitionOut = $isTop ? '-translate-y-10' : 'translate-y-10';
        $transitionIn = $isTop ? 'translate-y-[-10px]' : 'translate-y-10';

        // Toast container at configurable position
        $output .= '<div id="toast-container" class="flex flex-col items-end space-y-3 fixed z-50 ' . h($position) . '" data-transition-out="' . h($transitionOut) . '">';
        foreach ($toasts as $toast) {
            $output .= $toast;
        }

        // Add a single script for all toasts, supporting display mode and direction
        if ($this->config['display'] === 'expand') {
            // Show up to 3 toasts at a time, then next
            $output .= '<script>
                const toasts = Array.from(document.querySelectorAll(".toast"));
                const container = document.getElementById("toast-container");
                const transitionOut = container.dataset.transitionOut;
                let visible = 3;
                function showToasts(start) {
                    toasts.forEach((t, i) => {
                        if (i >= start && i < start + visible) {
                            t.classList.remove("hidden");
                        } else {
                            t.classList.add("hidden");
                        }
                    });
                }
                let current = 0;
                showToasts(current);
                function removeToastAndShowNext(toast) {
                    toast.classList.add("opacity-0", transitionOut, "duration-500");
                    setTimeout(function() {
                        toast.remove();
                        current = Math.max(0, current);
                        showToasts(current);
                    }, 500);
                }
                toasts.forEach((toast, i) => {
                    toast.querySelector(".toast-close").onclick = function(e) {
                        e.stopPropagation();
                        removeToastAndShowNext(toast);
                    };
                    setTimeout(function() {
                        if (!toast.parentNode) return;
                        removeToastAndShowNext(toast);
                    }, 4000 + i * 300); // staggered
                });
            </script>';
        } else {
            // Show all toasts at once (default)
            $output .= '<script>
                const container = document.getElementById("toast-container");
                const transitionOut = container.dataset.transitionOut;
                document.querySelectorAll(".toast").forEach(function(toast) {
                    setTimeout(function() {
                        toast.classList.add("opacity-0", transitionOut, "duration-500");
                        setTimeout(function() { toast.remove(); }, 500);
                    }, 4000);
                    toast.querySelector(".toast-close").onclick = function(e) {
                        e.stopPropagation();
                        toast.classList.add("opacity-0", transitionOut, "duration-500");
                        setTimeout(function() { toast.remove(); }, 500);
                    };
                });
            </script>';
        }
        $output .= '</div>';

        return $output;
    }

    /**
     * Generate the HTML for a toast notification.
     *
     * @param string $message
     * @param string $type
     * @param array $params
     * @return string
     */
    protected function toastScript(string $message, string $type, array $params = []): string
    {
        //pr($type); die;
        $escaped = h($message);
        $color = match ($type) {
            'success' => 'bg-green-500 text-white',
            'error' => 'bg-red-500 text-white',
            'warning' => 'bg-yellow-500 text-white',
            'info' => 'bg-blue-500 text-white',
            default => '',
        };
        $linkHtml = '';
        if (!empty($params['link']) && !empty($params['link_text'])) {
            $url = h($params['link']);
            $text = h($params['link_text']);
            $linkHtml = "<a href=\"{$url}\" class=\"ml-2 underline font-semibold hover:text-neutral-200 dark:text-white transition\" target=\"_blank\">{$text}</a>";
        }

        // Add transition/animation classes
        return "<div class=\"toast relative min-w-[250px] max-w-xs p-5 mb-2 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-lg {$color} dark:bg-neutral-800 dark:text-white opacity-100 translate-x-0 transition-all duration-500 flex items-start\">
            <button class=\"toast-close absolute top-2 right-2 hover:text-neutral-300 dark:text-white focus:outline-none\" aria-label=\"Close\">
                <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-4 w-4\" fill=\"none\" viewBox=\"0 0 24 24\" stroke=\"currentColor\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\" /></svg>
            </button>
            <div class=\"pr-6\">{$escaped}{$linkHtml}</div>
        </div>";
    }
}

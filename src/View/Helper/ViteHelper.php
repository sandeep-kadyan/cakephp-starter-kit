<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\View\Helper;

/**
 * Vite Helper
 *
 * This helper manages the integration between CakePHP and Vite.js for asset management.
 * It handles both development (HMR) and production modes, with automatic fallback to
 * built assets when the dev server is not available.
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class ViteHelper extends Helper
{
    /**
     * Default configuration
     *
     * @var array<string, mixed>
     */
    protected array $helpers = ['Html'];

    /**
     * Default configuration
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'buildPath' => '/dist', // Base path for built assets
    ];

    /**
     * Get manifest data from the Vite build
     *
     * The manifest file contains information about all built assets and their hashed filenames.
     * This is used in production mode to load the correct version of each asset.
     *
     * @return array<string, array<string, mixed>>|null The manifest data or null if not found/invalid
     */
    protected function getManifest(): ?array
    {
        // Path to the manifest file in the .vite directory
        $manifestPath = WWW_ROOT . $this->getConfig('buildPath') . '/.vite/manifest.json';

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($manifest)) {
                return $manifest;
            }
        }

        // Log if manifest is not found or invalid
        if (Configure::read('debug')) {
            Log::debug('Vite manifest not found or invalid at: ' . $manifestPath);
        }

        return null;
    }

    /**
     * Check if the Vite dev server is running
     *
     * @param string $host Host to connect to (default: localhost)
     * @param int $port Port to connect to (default: 5173)
     * @return bool True if the dev server is running, false otherwise
     */
    protected function isDevServerRunning(string $host = 'localhost', int $port = 5173): bool
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, 0.2);
        if (is_resource($connection)) {
            fclose($connection);

            return true;
        }

        return false;
    }

    /**
     * Generate Vite assets tags
     *
     * This method generates the appropriate HTML tags for loading Vite assets.
     * In development mode, it uses the Vite dev server for HMR support.
     * In production mode, it uses the built assets with hashed filenames.
     *
     * @param array<string>|string $entrypoints Entry points to load (e.g., ['js/app.js', 'css/app.css'])
     * @return string HTML tags for loading the assets
     */
    public function assets(string|array $entrypoints): string
    {
        // Convert single entrypoint to array for consistent handling
        $entrypoints = is_array($entrypoints) ? $entrypoints : [$entrypoints];

        $output = '';
        $devServerHost = 'localhost';
        $devServerPort = 5173;
        $server = "http://{$devServerHost}:{$devServerPort}";

        if ($this->isDevServerRunning($devServerHost, $devServerPort)) {
            // Use dev server
            $output .= $this->Html->script(
                sprintf('%s/@vite/client', $server),
                ['type' => 'module'],
            );

            foreach ($entrypoints as $entrypoint) {
                if (str_ends_with($entrypoint, '.css')) {
                    // Load CSS through the dev server
                    $output .= $this->Html->css(
                        sprintf('%s/%s', $server, $entrypoint),
                    );
                } elseif (str_ends_with($entrypoint, '.js')) {
                    // Load JS through the dev server
                    $output .= $this->Html->script(
                        sprintf('%s/%s', $server, $entrypoint),
                        ['type' => 'module', 'block' => 'script'],
                    );
                }
            }
        } else {
            // Use built assets (production)
            $manifest = $this->getManifest();

            if ($manifest) {
                // Use manifest to load hashed filenames
                foreach ($entrypoints as $entrypoint) {
                    if (isset($manifest[$entrypoint]) && is_array($manifest[$entrypoint])) {
                        $file = $manifest[$entrypoint];

                        // Add CSS files from manifest
                        if (isset($file['css']) && is_array($file['css'])) {
                            foreach ($file['css'] as $css) {
                                if (is_string($css)) {
                                    $output .= $this->Html->css(
                                        sprintf(
                                            '%s/%s',
                                            $this->getConfig('buildPath'),
                                            $css,
                                        ),
                                    );
                                }
                            }
                        }

                        // Add JS file from manifest
                        if (isset($file['file']) && is_string($file['file'])) {
                            $output .= $this->Html->script(
                                sprintf(
                                    '%s/%s',
                                    $this->getConfig('buildPath'),
                                    $file['file'],
                                ),
                                ['type' => 'module', 'block' => 'script'],
                            );
                        }
                    }
                }
            } else {
                // Fallback if manifest doesn't exist - use direct paths
                foreach ($entrypoints as $entrypoint) {
                    if (str_ends_with($entrypoint, '.css')) {
                        $output .= $this->Html->css(
                            sprintf(
                                '%s/css/%s',
                                $this->getConfig('buildPath'),
                                basename($entrypoint),
                            ),
                        );
                    } elseif (str_ends_with($entrypoint, '.js')) {
                        $output .= $this->Html->script(
                            sprintf(
                                '%s/js/%s',
                                $this->getConfig('buildPath'),
                                basename($entrypoint),
                            ),
                            ['type' => 'module', 'block' => 'script'],
                        );
                    }
                }
            }
        }

        return $output;
    }
}

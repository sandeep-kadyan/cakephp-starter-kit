<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;

/**
 * @var \App\View\AppView $this
 */
$checkConnection = function (string $name) {
    $error = null;
    $connected = false;
    try {
        ConnectionManager::get($name)->getDriver()->connect();
        $connected = true;
    } catch (Exception $connectionError) {
        $error = $connectionError->getMessage();
        if (method_exists($connectionError, 'getAttributes')) {
            $attributes = $connectionError->getAttributes();
            if (isset($attributes['message'])) {
                $error .= '<br />' . $attributes['message'];
            }
        }
        if ($name === 'debug_kit') {
            $error = 'Try adding your current <b>top level domain</b> to the
                <a href="https://book.cakephp.org/debugkit/5/en/index.html#configuration" target="_blank">DebugKit.safeTld</a>
            config and reload.';
            if (!in_array('sqlite', \PDO::getAvailableDrivers())) {
                $error .= '<br />You need to install the PHP extension <code>pdo_sqlite</code> so DebugKit can work properly.';
            }
        }
    }

    return compact('connected', 'error');
};

$statusResult = $checkConnection('default');
$debugKitResult = $checkConnection('debug_kit');

$this->assign('title', 'Welcome');
?>
<div class="bg-neutral-50 dark:bg-neutral-950 text-neutral-900 dark:text-neutral-100 antialiased">
    <!-- Hero Section -->
    <section class="bg-gradient-to-b from-white dark:from-neutral-950 via-neutral-50 dark:via-neutral-900 to-neutral-100 dark:to-neutral-950 py-16">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-neutral-100 dark:bg-neutral-800 rounded-full text-sm">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                        <span class="text-neutral-600 dark:text-neutral-400">v<?= h(Configure::version()) ?></span>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                        Modern <span class="text-violet-600">CakePHP</span> Starter Kit
                    </h1>
                    <p class="text-lg text-neutral-600 dark:text-neutral-400 leading-relaxed">
                        Build faster with a production-ready boilerplate featuring Tailwind CSS,
                        Vite, Vue, and a SaaS-grade authentication system.
                    </p>
                    <div class="flex gap-4">
                        <?= $this->Html->link(
                            '<span class="material-icons text-sm mr-2">login</span> Get Started',
                            '/login',
                            [
                                'class' => 'inline-flex items-center px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl',
                                'escape' => false,
                            ]
                        ) ?>
                        <?= $this->Html->link(
                            '<span class="material-icons text-sm mr-2">open_in_new</span> Docs',
                            'https://book.cakephp.org/',
                            [
                                'class' => 'inline-flex items-center px-6 py-3 border border-neutral-300 dark:border-neutral-700 text-neutral-700 dark:text-neutral-300 rounded-lg font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all duration-200',
                                'escape' => false,
                                'target' => '_blank',
                            ]
                        ) ?>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <div class="relative">
                        <div class="absolute -top-4 -right-4 w-72 h-72 bg-violet-500/20 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-4 -left-4 w-72 h-72 bg-fiolet-500/20 rounded-full blur-3xl"></div>
                        <div class="relative bg-white dark:bg-neutral-800 rounded-2xl shadow-2xl border border-neutral-200 dark:border-neutral-700 p-8">
                            <?= $this->Html->image('cake.logo.svg', [
                                'class' => 'w-48 h-auto mx-auto invert-0 dark:invert',
                                'alt' => 'CakePHP Logo',
                            ]) ?>
                            <div class="mt-6 space-y-3 text-center">
                                <div class="text-sm text-neutral-600 dark:text-neutral-400">Quick Stats</div>
                                <div class="grid grid-cols-3 gap-4 pt-4 border-t border-neutral-200 dark:border-neutral-700">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-violet-600">5+</div>
                                        <div class="text-xs text-neutral-500 dark:text-neutral-500">Features</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-violet-600">100%</div>
                                        <div class="text-xs text-neutral-500 dark:text-neutral-500">Ready</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-violet-600">∞</div>
                                        <div class="text-xs text-neutral-500 dark:text-neutral-500">Scalable</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Status Grid -->
    <section class="py-12 container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-2 text-neutral-900 dark:text-neutral-100">System Status</h2>
        <p class="text-center text-neutral-600 dark:text-neutral-400 mb-10 max-w-xl mx-auto">
            All systems are checked and ready for development.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
            <!-- Environment -->
            <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <span class="material-icons text-xl">code</span>
                    </span>
                    <h4 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200">Environment</h4>
                </div>
                <ul class="space-y-2.5">
                    <?php if (version_compare(PHP_VERSION, '8.1.0', '>=')) : ?>
                        <li class="flex items-center text-sm">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>
                            <span class="text-neutral-600 dark:text-neutral-400">PHP <?= PHP_VERSION ?></span>
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>PHP too low
                        </li>
                    <?php endif; ?>

                    <?php if (extension_loaded('mbstring')) : ?>
                        <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>mbstring
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>mbstring
                        </li>
                    <?php endif; ?>

                    <?php if (extension_loaded('openssl')) : ?>
                        <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>openssl
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>openssl
                        </li>
                    <?php endif; ?>

                    <?php if (extension_loaded('intl')) : ?>
                        <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>intl
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>intl
                        </li>
                    <?php endif; ?>

                    <?php if (ini_get('zend.assertions') !== '1') : ?>
                        <li class="flex items-center text-sm text-yellow-500">
                            <span class="material-icons text-sm mr-2">warning</span>Enable <code>zend.assertions</code>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Filesystem -->
            <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center text-green-600 dark:text-green-400">
                        <span class="material-icons text-xl">folder</span>
                    </span>
                    <h4 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200">Filesystem</h4>
                </div>
                <ul class="space-y-2.5">
                    <?php if (is_writable(TMP)) : ?>
                        <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>tmp writable
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>tmp not writable
                        </li>
                    <?php endif; ?>

                    <?php if (is_writable(LOGS)) : ?>
                        <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>logs writable
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>logs not writable
                        </li>
                    <?php endif; ?>

                    <?php $settings = Cache::getConfig('_cake_translations_'); ?>
                    <?php if (!empty($settings)) : ?>
                        <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>Cache OK
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>Cache misconfigured
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Database -->
            <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center text-orange-600 dark:text-orange-400">
                        <span class="material-icons text-xl">dns</span>
                    </span>
                    <h4 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200">Database</h4>
                </div>
                <ul class="space-y-2.5">
                    <?php if ($statusResult['connected']) : ?>
                        <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>Connected
                        </li>
                    <?php else : ?>
                        <li class="flex flex-col text-sm text-red-500">
                            <span class="flex items-center mb-1">
                                <span class="material-icons text-sm mr-2">error</span>Not connected
                            </span>
                            <span class="text-xs ml-6"><?= $statusResult['error'] ? h($statusResult['error']) : '' ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- DebugKit -->
            <div class="bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <span class="material-icons text-xl">bug_finder</span>
                    </span>
                    <h4 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200">DebugKit</h4>
                </div>
                <ul class="space-y-2.5">
                    <?php if (Plugin::isLoaded('DebugKit')) : ?>
                        <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>Loaded
                        </li>
                        <?php if ($debugKitResult['connected']) : ?>
                            <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                                <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>DB connected
                            </li>
                        <?php else : ?>
                            <li class="flex flex-col text-sm text-yellow-500">
                                <span class="flex items-center mb-1">
                                    <span class="material-icons text-sm mr-2">warning</span>Config needed
                                </span>
                                <span class="text-xs ml-6"><?= $debugKitResult['error'] ? h($debugKitResult['error']) : '' ?></span>
                            </li>
                        <?php endif; ?>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>Not loaded
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="bg-white dark:bg-neutral-900 py-12">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-2 text-neutral-900 dark:text-neutral-100">What's Included</h2>
            <p class="text-center text-neutral-600 dark:text-neutral-400 mb-12 max-w-xl mx-auto">
                Everything you need to build a modern web application out of the box.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div class="border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6 text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mx-auto mb-4 text-blue-600 dark:text-blue-400">
                        <span class="material-icons text-3xl">security</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-neutral-800 dark:text-neutral-200">Auth & Security</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Full authentication suite with JWT, cookies, sessions, password reset, and 2FA support.</p>
                </div>
                <div class="border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6 text-center">
                    <div class="w-16 h-16 bg-violet-100 dark:bg-violet-900/30 rounded-xl flex items-center justify-center mx-auto mb-4 text-violet-600 dark:text-violet-400">
                        <span class="material-icons text-3xl">dashboard</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-neutral-800 dark:text-neutral-200">SaaS Dashboard</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Pre-built dashboard layout with responsive sidebar, analytics widgets, and activity tracking.</p>
                </div>
                <div class="border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6 text-center">
                    <div class="w-16 h-16 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center mx-auto mb-4 text-amber-600 dark:text-amber-400">
                        <span class="material-icons text-3xl">flash_on</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-neutral-800 dark:text-neutral-200">Dev Tools</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-400">Vite + Vue for frontend, Bake for code generation, and DebugKit for performance profiling.</p>
                </div>
            </div>
        </div>
    </section>
</div>

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
<div class="min-h-screen bg-gradient-to-br from-neutral-50 via-white to-neutral-100 dark:from-neutral-900 dark:via-neutral-950 dark:to-neutral-900">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-violet-500/20 via-fiolet-500/20 to-pink-500/20 dark:from-violet-500/10 dark:via-fiolet-500/10 dark:to-pink-500/10 pointer-events-none"></div>
        <div class="absolute -top-1/2 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-gradient-to-r from-violet-300/30 via-fiolet-300/30 to-pink-300/30 dark:from-violet-300/5 dark:via-fiolet-300/5 dark:to-pink-300/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative container mx-auto px-6 py-16 text-center">
            <?= $this->element('base/logo', ['class' => 'w-32 h-auto mx-auto invert dark:invert-0 drop-shadow-lg']) ?>
            <div class="mt-8 space-y-4">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-neutral-800 via-neutral-700 to-neutral-900 dark:from-neutral-100 dark:via-neutral-200 dark:to-neutral-100">
                    Welcome to CakePHP <?= h(Configure::version()) ?>
                </h1>
                <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    Chiffon (&hearts; &mdash; not the 🍰 cake) &mdash; a modern starter kit powered by CakePHP,
                    Vue, Tailwind CSS, and the latest web standards.
                </p>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="container mx-auto px-6 pb-12">
        <div class="flex justify-center gap-4 flex-wrap mb-12">
            <?= $this->Html->link(
                '<span class="material-icons mr-2">login</span> Log In',
                '/login',
                [
                    'class' => 'inline-flex items-center px-6 py-3 bg-gradient-to-r from-violet-600 to-fiolet-600 text-white rounded-lg font-medium hover:from-violet-700 hover:to-fiolet-700 transition-all duration-200 shadow-lg hover:shadow-xl',
                    'escape' => false,
                ]
            ) ?>
            <?= $this->Html->link(
                '<span class="material-icons mr-2">launch</span> Documentation',
                'https://book.cakephp.org/',
                [
                    'class' => 'inline-flex items-center px-6 py-3 border border-neutral-300 dark:border-neutral-700 text-neutral-700 dark:text-neutral-200 rounded-lg font-medium hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all duration-200',
                    'escape' => false,
                    'target' => '_blank',
                ]
            ) ?>
        </div>
    </section>

    <!-- Status Cards -->
    <section class="container mx-auto px-6 pb-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
            <!-- Environment -->
            <div class="bg-white/70 dark:bg-neutral-800/50 backdrop-blur-sm border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <span class="material-icons text-xl">language</span>
                    </span>
                    <h4 class="text-lg font-semibold text-neutral-800 dark:text-neutral-200">Environment</h4>
                </div>
                <ul class="space-y-2.5">
                    <?php if (version_compare(PHP_VERSION, '8.1.0', '>=')) : ?>
                        <li class="flex items-center text-sm text-neutral-600 dark:text-neutral-400">
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>
                            PHP <?= PHP_VERSION ?>
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>
                            PHP too low
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
            <div class="bg-white/70 dark:bg-neutral-800/50 backdrop-blur-sm border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/30 dark:to-green-800/30 rounded-lg flex items-center justify-center text-green-600 dark:text-green-400">
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
                            <span class="material-icons text-sm mr-2 text-green-500">check_circle</span>Cache: <span class="text-green-500 font-medium"><?= h($settings['className']) ?></span>
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>Cache misconfigured
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Database -->
            <div class="bg-white/70 dark:bg-neutral-800/50 backdrop-blur-sm border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-900/30 dark:to-orange-800/30 rounded-lg flex items-center justify-center text-orange-600 dark:text-orange-400">
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
                        <li class="flex items-center text-sm text-red-500">
                            <span class="material-icons text-sm mr-2">error</span>Not connected<br />
                            <span class="text-xs ml-6 mt-1"><?= $statusResult['error'] ? h($statusResult['error']) : '' ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- DebugKit -->
            <div class="bg-white/70 dark:bg-neutral-800/50 backdrop-blur-sm border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 rounded-lg flex items-center justify-center text-purple-600 dark:text-purple-400">
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
                            <li class="flex items-center text-sm text-yellow-500">
                                <span class="material-icons text-sm mr-2">warning</span>Config needed<br />
                                <span class="text-xs ml-6 mt-1"><?= $debugKitResult['error'] ? h($debugKitResult['error']) : '' ?></span>
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

    <!-- Getting Started -->
    <section class="container mx-auto px-6 pb-16">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold text-center mb-2 text-neutral-800 dark:text-neutral-200">Getting Started</h2>
            <p class="text-center text-neutral-600 dark:text-neutral-400 mb-8 max-w-2xl mx-auto">
                Explore the starter kit: manage users, track activities, and build your application on a solid foundation.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white/70 dark:bg-neutral-800/50 backdrop-blur-sm border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6 text-center group transition-all duration-200 hover:transform hover:-translate-y-0.5">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl flex items-center justify-center mx-auto mb-4 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform duration-200">
                        <span class="material-icons text-2xl">people</span>
                    </div>
                    <h3 class="font-semibold text-sm text-neutral-800 dark:text-neutral-200 mb-2">User Management</h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-500">Register, login, and manage users with built-in authentication.</p>
                </div>
                <div class="bg-white/70 dark:bg-neutral-800/50 backdrop-blur-sm border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6 text-center group transition-all duration-200 hover:transform hover:-translate-y-0.5">
                    <div class="w-14 h-14 bg-gradient-to-br from-violet-100 to-violet-200 dark:from-violet-900/30 dark:to-violet-800/30 rounded-xl flex items-center justify-center mx-auto mb-4 text-violet-600 dark:text-violet-400 group-hover:scale-110 transition-transform duration-200">
                        <span class="material-icons text-2xl">analytics</span>
                    </div>
                    <h3 class="font-semibold text-sm text-neutral-800 dark:text-neutral-200 mb-2">Activity Tracking</h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-500">Track user activities and monitor application usage.</p>
                </div>
                <div class="bg-white/70 dark:bg-neutral-800/50 backdrop-blur-sm border border-neutral-200 dark:border-neutral-700 rounded-xl shadow-sm p-6 text-center group transition-all duration-200 hover:transform hover:-translate-y-0.5">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 rounded-xl flex items-center justify-center mx-auto mb-4 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform duration-200">
                        <span class="material-icons text-2xl">dashboard</span>
                    </div>
                    <h3 class="font-semibold text-sm text-neutral-800 dark:text-neutral-200 mb-2">Admin Dashboard</h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-500">Access the dashboard for an overview of your application.</p>
                </div>
            </div>
        </div>
    </section>
</div>

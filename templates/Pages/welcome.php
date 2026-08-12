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

$this->assign('title', 'Welcome to CakePHP SaaS Starter Kit');
$this->assign('seo.description', 'A modern, production-ready CakePHP SaaS starter kit with authentication, JWT, 2FA, dark mode, and a beautiful Tailwind CSS dashboard. Build and scale your SaaS product faster.');
$this->assign('seo.type', 'website');
?>
<!-- Hero Section -->
<section class="py-20">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="lg:w-1/2 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-muted rounded-full text-sm">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    <span class="text-muted-foreground">v<?= h(Configure::version()) ?></span>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
                    Modern <span class="text-red-600">CakePHP</span> Starter Kit
                </h1>
                <p class="text-lg text-muted-foreground leading-relaxed">
                    Build faster with a production-ready boilerplate featuring Tailwind CSS,
                    Vite, Vue, and a SaaS-grade authentication system.
                </p>
                <div class="flex gap-4">
                    <?= $this->Html->link(
                        '<i data-lucide="log-in" class=" text-sm mr-2"></i> Get Started',
                        '/login',
                        [
                            'class' => 'inline-flex items-center px-6 py-3 bg-primary text-primary-foreground hover:bg-primary/90 rounded-lg font-medium transition-all duration-200 shadow-lg hover:shadow-xl',
                            'escape' => false,
                        ]
                    ) ?>
                    <?= $this->Html->link(
                        '<i data-lucide="external-link" class=" text-sm mr-2"></i> Docs',
                        'https://book.cakephp.org/',
                        [
                            'class' => 'inline-flex items-center px-6 py-3 border border-border text-foreground rounded-lg font-medium hover:bg-accent hover:text-accent-foreground transition-all duration-200',
                            'escape' => false,
                            'target' => '_blank',
                        ]
                    ) ?>
                </div>
            </div>
            <div class="lg:w-1/2">
                <div class="relative">
                    <div class="absolute -top-6 -right-6 w-72 h-72 bg-red-500/20 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-6 -left-6 w-72 h-72 bg-orange-500/20 rounded-full blur-3xl"></div>

                    <!-- Bento Grid -->
                    <div class="relative grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Logo Tile (large) -->
                        <div class="col-span-2 row-span-2 flex flex-col items-center justify-center gap-4 bg-card/60 backdrop-blur-sm border border-border/70 rounded-2xl shadow-xl p-8">
                            <div class="text-center">
                                <div class="text-xl font-bold text-foreground">CakePHP</div>
                                <div class="text-xs text-muted-foreground">Starter Kit</div>
                            </div>
                        </div>

                        <!-- Stat Tiles -->
                        <div class="flex flex-col items-center justify-center gap-1 bg-red-500/10 border border-red-500/20 rounded-2xl backdrop-blur-sm p-5 text-center">
                            <div class="text-3xl font-black text-red-600">5+</div>
                            <div class="text-xs text-foreground">Features</div>
                        </div>
                        <div class="flex flex-col items-center justify-center gap-1 bg-card/60 border border-border/70 rounded-2xl backdrop-blur-sm p-5 text-center">
                            <div class="text-3xl font-black text-foreground">100%</div>
                            <div class="text-xs text-muted-foreground">Ready</div>
                        </div>
                        <div class="flex flex-col items-center justify-center gap-1 bg-orange-500/10 border border-orange-500/20 rounded-2xl backdrop-blur-sm p-5 text-center">
                            <i data-lucide="shield-check" class=" text-2xl text-orange-600"></i>
                            <div class="text-xs text-foreground">2FA Auth</div>
                        </div>
                        <div class="flex flex-col items-center justify-center gap-1 bg-card/60 border border-border/70 rounded-2xl backdrop-blur-sm p-5 text-center">
                            <i data-lucide="zap" class=" text-2xl text-red-600"></i>
                            <div class="text-xs text-foreground">JWT Ready</div>
                        </div>
                        <div class="flex flex-col items-center justify-center gap-1 bg-card/60 border border-border/70 rounded-2xl backdrop-blur-sm p-5 text-center">
                            <i data-lucide="moon" class=" text-2xl text-amber-600"></i>
                            <div class="text-xs text-foreground">Dark Mode</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Status Grid -->
<section class="py-16">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-2 text-foreground">System Status</h2>
        <p class="text-center text-muted-foreground mb-10 max-w-xl mx-auto">
            All systems are checked and ready for development.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
            <!-- Environment -->
            <div class="bg-card border border-border rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center text-red-600">
                        <i data-lucide="code" class=" text-xl"></i>
                    </span>
                    <h4 class="text-lg font-semibold text-foreground">Environment</h4>
                </div>
                <ul class="space-y-2.5">
                    <?php if (version_compare(PHP_VERSION, '8.1.0', '>=')) : ?>
                        <li class="flex items-center text-sm">
                            <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>
                            <span class="text-muted-foreground">PHP <?= PHP_VERSION ?></span>
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <i data-lucide="circle-x" class=" text-sm mr-2"></i>PHP too low
                        </li>
                    <?php endif; ?>

                    <?php if (extension_loaded('mbstring')) : ?>
                        <li class="flex items-center text-sm text-muted-foreground">
                            <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>mbstring
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <i data-lucide="circle-x" class=" text-sm mr-2"></i>mbstring
                        </li>
                    <?php endif; ?>

                    <?php if (extension_loaded('openssl')) : ?>
                        <li class="flex items-center text-sm text-muted-foreground">
                            <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>openssl
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <i data-lucide="circle-x" class=" text-sm mr-2"></i>openssl
                        </li>
                    <?php endif; ?>

                    <?php if (extension_loaded('intl')) : ?>
                        <li class="flex items-center text-sm text-muted-foreground">
                            <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>intl
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <i data-lucide="circle-x" class=" text-sm mr-2"></i>intl
                        </li>
                    <?php endif; ?>

                    <?php if (ini_get('zend.assertions') !== '1') : ?>
                        <li class="flex items-center text-sm text-yellow-500">
                            <i data-lucide="triangle-alert" class=" text-sm mr-2"></i>Enable <code>zend.assertions</code>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Filesystem -->
            <div class="bg-card border border-border rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                        <i data-lucide="folder" class=" text-xl"></i>
                    </span>
                    <h4 class="text-lg font-semibold text-foreground">Filesystem</h4>
                </div>
                <ul class="space-y-2.5">
                    <?php if (is_writable(TMP)) : ?>
                        <li class="flex items-center text-sm text-muted-foreground">
                            <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>tmp writable
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <i data-lucide="circle-x" class=" text-sm mr-2"></i>tmp not writable
                        </li>
                    <?php endif; ?>

                    <?php if (is_writable(LOGS)) : ?>
                        <li class="flex items-center text-sm text-muted-foreground">
                            <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>logs writable
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <i data-lucide="circle-x" class=" text-sm mr-2"></i>logs not writable
                        </li>
                    <?php endif; ?>

                    <?php $settings = Cache::getConfig('_cake_translations_'); ?>
                    <?php if (!empty($settings)) : ?>
                        <li class="flex items-center text-sm text-muted-foreground">
                            <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>Cache OK
                        </li>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <i data-lucide="circle-x" class=" text-sm mr-2"></i>Cache misconfigured
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Database -->
            <div class="bg-card border border-border rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600">
                        <i data-lucide="server" class=" text-xl"></i>
                    </span>
                    <h4 class="text-lg font-semibold text-foreground">Database</h4>
                </div>
                <ul class="space-y-2.5">
                    <?php if ($statusResult['connected']) : ?>
                        <li class="flex items-center text-sm text-muted-foreground">
                            <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>Connected
                        </li>
                    <?php else : ?>
                        <li class="flex flex-col text-sm text-red-500">
                            <span class="flex items-center mb-1">
                                <i data-lucide="circle-x" class=" text-sm mr-2"></i>Not connected
                            </span>
                            <span class="text-xs ml-6"><?= $statusResult['error'] ? h($statusResult['error']) : '' ?></span>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- DebugKit -->
            <div class="bg-card border border-border rounded-xl shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600">
                        <i data-lucide="bug" class=" text-xl"></i>
                    </span>
                    <h4 class="text-lg font-semibold text-foreground">DebugKit</h4>
                </div>
                <ul class="space-y-2.5">
                    <?php if (Plugin::isLoaded('DebugKit')) : ?>
                        <li class="flex items-center text-sm text-muted-foreground">
                            <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>Loaded
                        </li>
                        <?php if ($debugKitResult['connected']) : ?>
                            <li class="flex items-center text-sm text-muted-foreground">
                                <i data-lucide="check-circle" class=" text-sm mr-2 text-green-500"></i>DB connected
                            </li>
                        <?php else : ?>
                            <li class="flex flex-col text-sm text-yellow-500">
                                <span class="flex items-center mb-1">
                                    <i data-lucide="triangle-alert" class=" text-sm mr-2"></i>Config needed
                                </span>
                                <span class="text-xs ml-6"><?= $debugKitResult['error'] ? h($debugKitResult['error']) : '' ?></span>
                            </li>
                        <?php endif; ?>
                    <?php else : ?>
                        <li class="flex items-center text-sm text-red-500">
                            <i data-lucide="circle-x" class=" text-sm mr-2"></i>Not loaded
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="bg-card py-20" id="features">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-center mb-2 text-foreground">Built for Modern SaaS</h2>
        <p class="text-center text-muted-foreground mb-12 max-w-xl mx-auto">
            Everything you need to build, deploy, and scale your SaaS product.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div class="bg-card border border-border rounded-xl shadow-sm p-8 text-center group transition-all duration-200 hover:shadow-xl">
                <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-6 text-red-600 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="shield" class=" text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-foreground">Auth & Security</h3>
                <p class="text-sm text-muted-foreground">
                    Full authentication suite with JWT, cookies, sessions, password reset, and 2FA support.
                </p>
            </div>
            <div class="bg-card border border-border rounded-xl shadow-sm p-8 text-center group transition-all duration-200 hover:shadow-xl">
                <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-6 text-red-600 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="layout-dashboard" class=" text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-foreground">SaaS Dashboard</h3>
                <p class="text-sm text-muted-foreground">
                    Pre-built dashboard layout with responsive sidebar, analytics widgets, and activity tracking.
                </p>
            </div>
            <div class="bg-card border border-border rounded-xl shadow-sm p-8 text-center group transition-all duration-200 hover:shadow-xl">
                <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-6 text-red-600 group-hover:scale-110 transition-transform duration-300">
                    <i data-lucide="zap" class=" text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4 text-foreground">Dev Tools</h3>
                <p class="text-sm text-muted-foreground">
                    Vite + Vue for frontend, Bake for code generation, and DebugKit for performance profiling.
                </p>
            </div>
        </div>
    </div>
</section>

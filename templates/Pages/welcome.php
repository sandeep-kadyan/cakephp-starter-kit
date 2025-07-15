<?php

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\NotFoundException;

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
$checkConnection = function (string $name) {
    $error = null;
    $connected = false;
    try {
        ConnectionManager::get($name)->getDriver()->connect();
        // No exception means success
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
$this->assign('title', 'Welcome');
?>
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="flex flex-col items-center mb-8">
        <?= $this->element('base/logo', ['class' => 'w-full invert dark:invert-0'])?>
        <h1 class="text-3xl text-center font-bold text-neutral-800 dark:text-neutral-100 mt-8 mb-2">
            Welcome to CakePHP <?= h(Configure::version()) ?> Chiffon (🍰)
        </h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
            <h4 class="text-lg font-semibold mb-4 text-neutral-700">Environment</h4>
            <ul class="space-y-2">
                <?php if (version_compare(PHP_VERSION, '8.1.0', '>=')) : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>Your version of PHP is 8.1.0 or higher (detected <?= PHP_VERSION ?>).</li>
                <?php else : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>Your version of PHP is too low. You need PHP 8.1.0 or higher to use CakePHP (detected <?= PHP_VERSION ?>).</li>
                <?php endif; ?>

                <?php if (extension_loaded('mbstring')) : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>Your version of PHP has the mbstring extension loaded.</li>
                <?php else : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>Your version of PHP does NOT have the mbstring extension loaded.</li>
                <?php endif; ?>

                <?php if (extension_loaded('openssl')) : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>Your version of PHP has the openssl extension loaded.</li>
                <?php else : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>Your version of PHP does NOT have the openssl extension loaded.</li>
                <?php endif; ?>

                <?php if (extension_loaded('intl')) : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>Your version of PHP has the intl extension loaded.</li>
                <?php else : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>Your version of PHP does NOT have the intl extension loaded.</li>
                <?php endif; ?>

                <?php if (ini_get('zend.assertions') !== '1') : ?>
                    <li class="flex items-center text-yellow-600"><span class="material-icons mr-2">warning</span>You should set <code>zend.assertions</code> to <code>1</code> in your <code>php.ini</code> for your development environment.</li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h4 class="text-lg font-semibold mb-4 text-neutral-700">Filesystem</h4>
            <ul class="space-y-2">
                <?php if (is_writable(TMP)) : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>Your tmp directory is writable.</li>
                <?php else : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>Your tmp directory is NOT writable.</li>
                <?php endif; ?>

                <?php if (is_writable(LOGS)) : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>Your logs directory is writable.</li>
                <?php else : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>Your logs directory is NOT writable.</li>
                <?php endif; ?>

                <?php $settings = Cache::getConfig('_cake_translations_'); ?>
                <?php if (!empty($settings)) : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>The <em class="mx-2 text-green-500"><?= h($settings['className']) ?></em> is being used for core caching. To change the config edit config/app.php</li>
                <?php else : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>Your cache is NOT working. Please check the settings in config/app.php</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
            <h4 class="text-lg font-semibold mb-4 text-neutral-700">Database</h4>
            <?php $result = $checkConnection('default'); ?>
            <ul class="space-y-2">
                <?php if ($result['connected']) : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>CakePHP is able to connect to the database.</li>
                <?php else : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>CakePHP is NOT able to connect to the database.<br /><?= h($result['error']) ?></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <h4 class="text-lg font-semibold mb-4 text-neutral-700">DebugKit</h4>
            <ul class="space-y-2">
                <?php if (Plugin::isLoaded('DebugKit')) : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>DebugKit is loaded.</li>
                    <?php $result = $checkConnection('debug_kit'); ?>
                    <?php if ($result['connected']) : ?>
                        <li class="flex items-start"><span class="material-icons mr-2 text-green-600">check_circle</span>DebugKit can connect to the database.</li>
                    <?php else : ?>
                        <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>There are configuration problems present which need to be fixed:<br /><?= $result['error'] ?></li>
                    <?php endif; ?>
                <?php else : ?>
                    <li class="flex items-start"><span class="material-icons mr-2 text-red-600">error</span>DebugKit is <strong>not</strong> loaded.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

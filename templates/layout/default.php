<?php

use Cake\I18n\I18n;
use Cake\Routing\Router;

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE html>
<html lang="<?= I18n::getLocale() ?>">

<head>
    <?= $this->Html->charset() ?>
    <title><?= $this->fetch('title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>

    <?= $this->Html->meta('icon') ?>

    <?= $this->Vite->assets(['js/app.js', 'css/app.css']) ?>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>

</head>

<body class="bg-neutral-100 dark:bg-neutral-800 text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
        <?php if ($this->request->getParam('action') != 'login'): ?>
            <nav class="flex items-center justify-center gap-4">
                <?= $this->element('base/theme') ?>
                <?php if ($this->Identity->isLoggedIn()): ?>
                    <?= $this->Html->link(
                        'Dashboard',
                        '/pages/dashboard',
                        ['class' => 'inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal']
                    ) ?>
                    <?= $this->Form->postLink(
                        'Logout',
                        ['controller' => 'Users', 'action' => 'logout'],
                        [
                            'class' => 'inline-block px-5 py-1.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-sm text-sm leading-normal',
                            'style' => 'border:none;cursor:pointer;',
                            'confirm' => __('Are you sure you want to logout?')
                        ]
                    ) ?>
                <?php else: ?>
                    <?= $this->Html->link(
                        'Log In',
                        '/login',
                        ['class' => 'inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal']
                    ) ?>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </header>
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            <?= $this->fetch('content') ?>
        </main>
    </div>
    <footer>
    </footer>
    <?= $this->fetch('script') ?>
    <?= $this->Toast->render() ?>
</body>

</html>

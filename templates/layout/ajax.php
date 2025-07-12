<?php

use Cake\Core\Configure;
use Cake\I18n\I18n;

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
<body class="min-h-screen bg-white dark:bg-neutral-800">
<?= match (Configure::read('Setting.app.layout', 'aside')) {
    'aside' => $this->element('app/aside'),
    'header' => $this->element('app/header'),
    default => $this->element('app/aside'),
}; ?>
</body>
<?= $this->fetch('script') ?>
<?= $this->Toast->render() ?>

</html>

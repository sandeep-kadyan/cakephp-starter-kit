<?php

use Cake\Core\Configure;
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

<?= match (Configure::read('Setting.auth.layout', 'split')) {
    'simple' => $this->element('auth/simple'),
    'split' => $this->element('auth/split'),
    'card' => $this->element('auth/card'),
    'muted' => $this->element('auth/muted'),
    default => $this->element('auth/split'),
}; ?>

<?= $this->fetch('script') ?>
<?= $this->Toast->render() ?>

</html>

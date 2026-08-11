<?php

use Cake\I18n\I18n;

/**
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
    <?= $this->element('base/seo') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>

</head>

<body class="bg-white dark:bg-neutral-950 text-neutral-900 dark:text-neutral-100 min-h-screen flex flex-col transition-colors duration-300">
    <!-- Header -->
    <?= $this->element('base/header') ?>

    <!-- Main Content -->
    <main class="flex-1">
        <?= $this->fetch('content') ?>
    </main>

    <!-- Footer -->
    <?= $this->element('base/footer') ?>

    <?= $this->fetch('script') ?>
    
    <?= $this->Toast->render() ?>
</body>

</html>

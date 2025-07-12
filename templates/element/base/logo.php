<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Html->link(
    $this->Html->image($path ?? 'cake.logo.svg', ['alt' => 'CakePHP', 'class' => $class]),
    '/',
    [
        'class' => 'relative z-20 text-lg font-medium',
        'target' => '_self',
        'rel' => 'noopener',
        'escape' => false,
    ]
) ?>

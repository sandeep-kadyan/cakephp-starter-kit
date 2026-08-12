<?php

use Cake\Utility\Inflector;

/**
 * Backend page header element.
 *
 * Renders a consistent page title + description + optional icon and action
 * buttons at the top of the backend content area. Rendered automatically by
 * the `app` layouts; nothing is shown when the page has no title, description
 * or actions.
 *
 * Override per-view by assigning blocks before the layout renders:
 *
 * - 'title'                - Page heading (defaults to a readable name derived
 *                            from the current controller/action).
 * - 'pageHeader.description' - Optional subheading shown under the title.
 * - 'pageHeader.icon'      - Optional Lucide icon name shown beside the title.
 * - 'pageHeader.actions'   - Optional HTML (buttons/links) aligned to the right.
 *
 * Example:
 *   $this->assign('title', __('Users'));
 *   $this->assign('pageHeader.description', __('Manage the people with access to your account.'));
 *   $this->assign('pageHeader.icon', 'users');
 *   $this->start('pageHeader.actions');
 *   echo $this->Html->link(__('New User'), ['action' => 'add'], ['class' => '...']);
 *   $this->end();
 *
 * @var \App\View\AppView $this
 */
$title = trim((string)$this->fetch('title'));
if ($title === '') {
    $controller = (string)$this->request->getParam('controller');
    $plural = Inflector::humanize(Inflector::underscore($controller));
    $singular = Inflector::humanize(Inflector::underscore(Inflector::singularize($controller)));
    $title = match ((string)$this->request->getParam('action')) {
        'add' => __('Add {0}', $singular),
        'edit' => __('Edit {0}', $singular),
        'view' => $singular,
        default => $plural,
    };
}
$description = trim((string)$this->fetch('pageHeader.description'));
$icon = trim((string)$this->fetch('pageHeader.icon'));
$actions = trim((string)$this->fetch('pageHeader.actions'));

if ($title === '' && $description === '' && $actions === '') {
    return;
}
?>
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div class="flex items-center gap-3">
        <?php if ($icon !== ''): ?>
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
            <i data-lucide="<?= h($icon) ?>" class="h-6 w-6"></i>
        </span>
        <?php endif; ?>
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground"><?= h($title) ?></h1>
            <?php if ($description !== ''): ?>
            <p class="mt-1 text-sm text-muted-foreground"><?= h($description) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($actions !== ''): ?>
    <div class="flex flex-wrap items-center gap-3">
        <?= $actions ?>
    </div>
    <?php endif; ?>
</div>

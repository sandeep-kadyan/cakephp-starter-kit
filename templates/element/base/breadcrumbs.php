<?php

/**
 * @var \App\View\AppView $this
 * @var array|null $breadcrumbs
 */
$breadcrumbs = $breadcrumbs ?? [
    ['title' => 'Home', 'url' => ['controller' => 'Pages', 'action' => 'dashboard']],
    ['title' => 'Section', 'url' => null],
];
?>
<nav class="justify-between text-muted-foreground sm:flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse sm:mb-0">
        <?php foreach ($breadcrumbs as $i => $crumb): ?>
            <li<?= $i === count($breadcrumbs) - 1 ? ' aria-current="page"' : '' ?>>
                <div class="flex items-center">
                    <?php if ($i > 0): ?>
                        <svg class="rtl:rotate-180 w-3 h-3 mx-1 text-muted-foreground" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                    <?php endif; ?>
                    <?php if (!empty($crumb['url'])): ?>
                        <a href="<?= $this->Url->build($crumb['url']) ?>" class="text-sm font-medium text-foreground hover:text-primary hover:underline">
                            <?= h($crumb['title']) ?>
                        </a>
                    <?php else: ?>
                        <span class="mx-1 text-sm font-medium text-muted-foreground md:mx-2">
                            <?= h($crumb['title']) ?>
                        </span>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>

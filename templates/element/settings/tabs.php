<?php
/**
 * Settings section navigation tabs.
 *
 * @var \App\View\AppView $this
 * @var array<string, array{title: string, icon: string, description: string}> $sections
 * @var string $activeSection
 */
$activeSection = $activeSection ?? '';
?>
<div class="flex flex-col gap-1">
    <?php foreach ($sections as $key => $section): ?>
        <?php
        $isActive = $activeSection === $key;
        $linkClass = 'flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors duration-200 ' .
            ($isActive
                ? 'bg-primary text-primary-foreground'
                : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground');
        ?>
        <?= $this->Html->link(
            '<i data-lucide="' . h($section['icon']) . '" class="h-4 w-4"></i><span>' . h($section['title']) . '</span>',
            ['controller' => 'Settings', 'action' => 'index', '?' => ['section' => $key]],
            ['escape' => false, 'class' => $linkClass]
        ) ?>
    <?php endforeach; ?>
</div>

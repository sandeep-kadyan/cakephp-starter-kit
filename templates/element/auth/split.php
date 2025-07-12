<?php
/**
 * @var \App\View\AppView $this
 */
?>
<body class="bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-800">
    <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="bg-muted relative h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
            <div class="absolute inset-0 bg-neutral-800 hidden lg:block"></div>
            <div class="flex items-center justify-center lg:justify-start">
                <?= $this->element('base/logo', ['class' => 'max-w-[150px] invert lg:invert-0']) ?>
            </div>

            <div class="relative z-20 mt-auto hidden lg:block">
                <blockquote class="space-y-2">
                    <h1>&ldquo;<?= __('Be the change you want to see in the world.') ?>&rdquo;</h1>
                    <p class="pl-2 italic"><?= __('Mahatma Gandhi') ?></p>
                </blockquote>
            </div>
        </div>
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </div>
</body>

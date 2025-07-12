<?php
/**
 * @var \App\View\AppView $this
 */
?>
<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-750 dark:to-neutral-800">
    <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-sm flex-col gap-2">
            <div class="flex flex-col gap-8">
                <div class="flex items-center justify-center">
                    <?= $this->element('base/logo', ['class' => 'max-w-[150px] invert']) ?>
                    <?= $this->element('base/theme') ?>
                </div>
                <?= $this->fetch('content') ?>
            </div>
        </div>
    </div>
</body>

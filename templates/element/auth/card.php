<?php
/**
 * @var \App\View\AppView $this
 */
?>
<body class="min-h-screen bg-neutral-100 antialiased dark:bg-linear-to-b dark:from-neutral-750 dark:to-neutral-800">
    <div class="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-md flex-col gap-6">
            <div class="flex flex-col gap-6">
                <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                    <div class="px-10 py-8">
                        <div class="flex items-center justify-center">
                            <?= $this->element('base/logo', ['class' => 'max-w-[150px] invert']) ?>
                            <?= $this->element('base/theme') ?>
                        </div>
                        <?= $this->fetch('content') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php
/**
 * @var \App\View\AppView $this
 */
?>
<body class="relative antialiased min-h-screen">
    <!-- Gradient background -->
    <div class="absolute inset-0 z-0 bg-gradient-to-b from-muted via-muted to-background"></div>
    
    <!-- SVG dots pattern overlay -->
    <div class="absolute inset-0 z-10 pointer-events-none">
        <svg width="100%" height="100%" class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="smallDots" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="1.5" fill="rgb(var(--muted-foreground) / 0.25)" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#smallDots)" />
        </svg>
    </div>

    <div class="relative z-20 flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-md flex-col gap-6">
            <div class="flex flex-col gap-6">
                <div class="rounded-xl border border-border bg-card text-card-foreground shadow-sm">
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
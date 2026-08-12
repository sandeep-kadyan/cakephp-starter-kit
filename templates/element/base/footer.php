<?php

use Cake\Core\Configure;

/**
 * @var \App\View\AppView $this
 */
?>
<footer class="border-t border-border bg-card mt-auto">
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-6 lg:gap-8">
            <!-- Brand -->
            <div class="lg:col-span-2 flex flex-col justify-between">
                <div>
                    <p class="text-sm text-muted-foreground mb-5 max-w-md">
                        A modern starter kit for building SaaS applications on CakePHP.
                        Get up and running with authentication, authorization, dashboards, and more.
                    </p>
                </div>
                <div class="flex gap-3">
                    <?= $this->Html->link(
                        '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
                        '#',
                        [
                            'class' => 'flex items-center justify-center w-10 h-10 text-muted-foreground hover:text-foreground hover:bg-accent hover:text-accent-foreground rounded-lg transition-all duration-200',
                            'escape' => false,
                            'title' => 'X',
                        ]
                    ) ?>
                    <?= $this->Html->link(
                        '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
                        '#',
                        [
                            'class' => 'flex items-center justify-center w-10 h-10 text-muted-foreground hover:text-foreground hover:bg-accent hover:text-accent-foreground rounded-lg transition-all duration-200',
                            'escape' => false,
                            'title' => 'Facebook',
                        ]
                    ) ?>
                    <?= $this->Html->link(
                        '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
                        '#',
                        [
                            'class' => 'flex items-center justify-center w-10 h-10 text-muted-foreground hover:text-foreground hover:bg-accent hover:text-accent-foreground rounded-lg transition-all duration-200',
                            'escape' => false,
                            'title' => 'LinkedIn',
                        ]
                    ) ?>
                    <?= $this->Html->link(
                        '<svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>',
                        '#',
                        [
                            'class' => 'flex items-center justify-center w-10 h-10 text-muted-foreground hover:text-foreground hover:bg-accent hover:text-accent-foreground rounded-lg transition-all duration-200',
                            'escape' => false,
                            'title' => 'GitHub',
                        ]
                    ) ?>
                </div>
            </div>

            <!-- Product -->
            <div>
                <h4 class="text-foreground font-semibold mb-4 text-sm uppercase tracking-wider">Product</h4>
                <ul class="space-y-3">
                    <?= $this->Html->link('Features', '/#features', [
                        'class' => 'text-sm text-muted-foreground hover:text-foreground transition-colors',
                    ]) ?>
                    <?= $this->Html->link('Pricing', '#', [
                        'class' => 'text-sm text-muted-foreground hover:text-foreground transition-colors',
                    ]) ?>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Changelog</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Roadmap</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Enterprise</a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <h4 class="text-foreground font-semibold mb-4 text-sm uppercase tracking-wider">Resources</h4>
                <ul class="space-y-3">
                    <?= $this->Html->link('Documentation', 'https://book.cakephp.org/', [
                        'class' => 'text-sm text-muted-foreground hover:text-foreground transition-colors',
                        'target' => '_blank',
                    ]) ?>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Blog</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Community</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Support</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">API Reference</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h4 class="text-foreground font-semibold mb-4 text-sm uppercase tracking-wider">Company</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">About</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Careers</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Partners</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 class="text-foreground font-semibold mb-4 text-sm uppercase tracking-wider">Legal</h4>
                <ul class="space-y-3">
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Cookie Policy</a></li>
                    <li><a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">Security</a></li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-border mt-8 pt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-sm text-muted-foreground">
            <div class="flex items-center gap-6 text-xs text-muted-foreground">
                <p>&copy; <?= date('Y') ?> CakePHP SAAS Starter Kit | By <a href="https://github.com/sandeep-kadyan" target="_blank">Sandeep Kadyan</a></p>
                <?php $loadTime = round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 2); ?>
                <?php $memUsage = round(memory_get_usage() / 1024 / 1024, 2); ?>
                <span class="flex items-center gap-1">
                    <i data-lucide="gauge" class="text-xs"></i>
                    Speed: <?= $loadTime ?>ms
                </span>
                <span class="flex items-center gap-1">
                    <i data-lucide="cpu" class="text-xs"></i>
                    Memory: <?= $memUsage ?>MB
                </span>
            </div>
            <p class="sm:text-right">
                Made with &hearts; using CakePHP <?= h(Configure::version()) ?>
            </p>
        </div>

        <!-- Big Text Logo -->
        <div class="relative flex items-center justify-center -mb-12 overflow-hidden pointer-events-none">
            <span class="text-muted-foreground select-none font-black text-6xl sm:text-7xl md:text-8xl lg:text-9xl leading-none tracking-tighter">
                CAKEPHP
            </span>
        </div>

    </div>
</footer>

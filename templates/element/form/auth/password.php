<?php

/**
 * Password input with show/hide toggle button.
 *
 * Usage:
 * ```
 * echo $this->element('form/auth/password', [
 *     'name' => 'password',
 *     'label' => 'Password',
 *     'placeholder' => '***********',
 * ]);
 * ```
 *
 * @var \App\View\AppView $this
 */
$name = $name ?? 'password';
$label = $label ?? null;
$placeholder = $placeholder ?? '***********';
$id = $id ?? $this->Form->fieldId($name);
?>
<div class="mb-4">
    <?php if ($label): ?>
        <label for="<?= h($id) ?>" class="block text-sm font-medium text-foreground mb-1">
            <?= h($label) ?>
        </label>
    <?php endif; ?>
    <div class="relative">
        <input
            type="password"
            name="<?= h($name) ?>"
            id="<?= h($id) ?>"
            placeholder="<?= h($placeholder) ?>"
            class="block w-full px-4 py-2 pr-10 border border-input rounded-lg focus:ring-2 focus:ring-ring focus:outline-none">
        <button
            type="button"
            data-password-toggle="<?= h($id) ?>"
            aria-label="Toggle password visibility"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-muted-foreground hover:text-foreground">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>
    </div>
    <?= $this->Form->error($name) ?>
</div>

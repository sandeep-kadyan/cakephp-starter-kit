<?php

use Cake\Core\Configure;
use Cake\I18n\I18n;
use Cake\Routing\Router;

/**
 * Base SEO element.
 *
 * Renders meta description, author, keywords, canonical URL, Open Graph,
 * Twitter Card, and JSON-LD structured data. Override per-view by assigning
 * view blocks before rendering:
 *
 * - 'title'       - Page title (defaults to Setting.seo.title)
 * - 'seo.description' - Meta/OG description (defaults to Setting.seo.description)
 * - 'seo.image'   - Share image (defaults to Setting.seo.image)
 * - 'seo.type'    - og:type (defaults to 'website')
 *
 * @var \App\View\AppView $this
 */
$seo = (array)Configure::read('Setting.seo', []);
$title = $this->fetch('title');
if (empty($title)) {
    $title = $seo['title'] ?? '';
}
$description = $this->fetch('seo.description') ?: ($seo['description'] ?? '');
$image = $this->fetch('seo.image') ?: ($seo['image'] ?? '');
$type = $this->fetch('seo.type') ?: 'website';
$author = $seo['author'] ?? '';
$keywords = $seo['keywords'] ?? '';
$robots = $seo['robots'] ?? 'index, follow';
$twitterHandle = $seo['twitter'] ?? '';
$siteName = $seo['siteName'] ?? '';
$canonical = $seo['canonical'] ?? (string)$this->request->getUri();
$fullTitle = $title;
if ($siteName !== '' && !str_contains($title, $siteName)) {
    $fullTitle = trim($title . ' | ' . $siteName, ' |');
}
if ($fullTitle === '') {
    $fullTitle = $siteName;
}
$baseUrl = Router::url('/', true);

$absoluteImage = filter_var($image, FILTER_VALIDATE_URL) ? $image : $baseUrl . ltrim($image, '/');

// Remove search engine directives on authenticated/private pages.
if ($this->request->getAttribute('identity')) {
    $robots = 'noindex, nofollow';
}
?>
<?= $this->Html->meta('description', $description) ?>
<?= $this->Html->meta('author', $author) ?>
<?= $this->Html->meta('keywords', $keywords) ?>
<?= $this->Html->meta('robots', $robots) ?>
<meta name="theme-color" content="<?= h($seo['themeColor'] ?? '#fafaf9') ?>">
<?= $this->Html->meta('canonical', $canonical) ?>

<!-- Open Graph -->
<?= $this->Html->meta(['property' => 'og:site_name', 'content' => $siteName]) ?>
<?= $this->Html->meta(['property' => 'og:type', 'content' => $type]) ?>
<?= $this->Html->meta(['property' => 'og:title', 'content' => $fullTitle]) ?>
<?= $this->Html->meta(['property' => 'og:description', 'content' => $description]) ?>
<?= $this->Html->meta(['property' => 'og:url', 'content' => $canonical]) ?>
<?= $this->Html->meta(['property' => 'og:image', 'content' => $absoluteImage]) ?>
<?= $this->Html->meta(['property' => 'og:locale', 'content' => str_replace('-', '_', I18n::getLocale())]) ?>

<!-- Twitter -->
<?= $this->Html->meta(['name' => 'twitter:card', 'content' => 'summary_large_image']) ?>
<?= $this->Html->meta(['name' => 'twitter:title', 'content' => $fullTitle]) ?>
<?= $this->Html->meta(['name' => 'twitter:description', 'content' => $description]) ?>
<?= $this->Html->meta(['name' => 'twitter:image', 'content' => $absoluteImage]) ?>
<?php if (!empty($twitterHandle)): ?>
    <?= $this->Html->meta(['name' => 'twitter:site', 'content' => $twitterHandle]) ?>
<?php endif; ?>

<!-- JSON-LD Structured Data -->
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'WebSite',
            'name' => $siteName,
            'url' => $baseUrl,
            'description' => $description,
            'publisher' => [
                '@type' => 'Organization',
                'name' => $seo['organization']['name'] ?? $siteName,
                'url' => isset($seo['organization']['url']) ? Router::url($seo['organization']['url'], true) : $baseUrl,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => isset($seo['organization']['logo']) ? $baseUrl . ltrim($seo['organization']['logo'], '/') : $absoluteImage,
                ],
            ],
        ],
        [
            '@type' => 'WebPage',
            'name' => $fullTitle,
            'url' => $canonical,
            'description' => $description,
            'inLanguage' => I18n::getLocale(),
            'author' => [
                '@type' => 'Person',
                'name' => $author,
                'email' => $seo['authorEmail'] ?? '',
            ],
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => $baseUrl,
            ],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>

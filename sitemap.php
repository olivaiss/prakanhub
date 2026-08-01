<?php
header('Content-Type: application/xml; charset=utf-8');
$BASE = 'https://prakanhub.com';

// รายการหน้าหลัก
$pages = [
    ['index.php', '1.0', 'weekly'],
    ['about.php', '0.9', 'monthly'],
    ['life.php', '0.8', 'monthly'],
    ['health.php', '0.8', 'monthly'],
    ['general.php', '0.8', 'monthly'],
    ['articles.php', '0.7', 'weekly'],
    ['career.php', '0.5', 'monthly'],
    ['seminar.php', '0.5', 'weekly'],
    ['testimonials.php', '0.5', 'monthly'],
    ['contact.php', '0.7', 'monthly'],
    ['claim.php', '0.5', 'monthly'],
    ['privacy.php', '0.3', 'yearly'],
    ['terms.php', '0.3', 'yearly'],
    ['faq.php', '0.5', 'monthly'],
    ['form/', '0.6', 'monthly'],
    ['member/', '0.4', 'monthly'],
];

// บทความจาก DB (fallback: id 1-4)
$articleIds = [1, 2, 3, 4];
$catSlugs = [];
$planIds = [];
try {
    require_once __DIR__ . '/includes/db.php';
    $__stmt = getDB()->query('SELECT id FROM articles WHERE is_active = 1 ORDER BY id');
    $articleIds = array_column($__stmt->fetchAll(), 'id');
    $__stmt = getDB()->query('SELECT slug FROM categories WHERE is_active = 1 AND slug NOT IN ("life","health","general") ORDER BY id');
    $catSlugs = array_column($__stmt->fetchAll(), 'slug');
    $__stmt = getDB()->query('SELECT id FROM products WHERE is_active = 1 ORDER BY id');
    $planIds = array_column($__stmt->fetchAll(), 'id');
} catch (Throwable $e) {
    // fallback
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as [$loc, $prio, $freq]): ?>
    <url><loc><?= $BASE ?>/<?= $loc ?></loc><priority><?= $prio ?></priority><changefreq><?= $freq ?></changefreq></url>
<?php endforeach; ?>
<?php foreach ($articleIds as $aid): ?>
    <url><loc><?= $BASE ?>/article.php?id=<?= (int)$aid ?></loc><priority>0.6</priority><changefreq>monthly</changefreq></url>
<?php endforeach; ?>
<?php foreach ($catSlugs as $cslug): ?>
    <url><loc><?= $BASE ?>/category.php?slug=<?= htmlspecialchars($cslug) ?></loc><priority>0.8</priority><changefreq>weekly</changefreq></url>
<?php endforeach; ?>
<?php foreach ($planIds as $pid): ?>
    <url><loc><?= $BASE ?>/plan.php?id=<?= (int)$pid ?></loc><priority>0.7</priority><changefreq>weekly</changefreq></url>
<?php endforeach; ?>
</urlset>

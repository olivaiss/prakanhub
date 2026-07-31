<?php
$q = trim($_GET['q'] ?? '');
$results = ['articles' => [], 'products' => [], 'faqs' => []];
$pageTitle = $q !== '' ? 'ค้นหา: ' . $q : 'ค้นหา';

if ($q !== '') {
    try {
        require_once __DIR__ . '/includes/db.php';
        $db = getDB();
        $like = '%' . $q . '%';

        $s = $db->prepare('SELECT id, title, tag, excerpt FROM articles WHERE is_active = 1 AND (title LIKE ? OR excerpt LIKE ? OR tag LIKE ?) ORDER BY publish_date DESC LIMIT 10');
        $s->execute([$like, $like, $like]);
        $results['articles'] = $s->fetchAll();

        $s = $db->prepare("SELECT id, title, badge, category, desc_text FROM products WHERE is_active = 1 AND (title LIKE ? OR desc_text LIKE ? OR badge LIKE ?) ORDER BY sort_order, id LIMIT 10");
        $s->execute([$like, $like, $like]);
        $results['products'] = $s->fetchAll();

        $s = $db->prepare('SELECT id, question, answer, group_name FROM faqs WHERE is_active = 1 AND (question LIKE ? OR answer LIKE ?) LIMIT 10');
        $s->execute([$like, $like]);
        $results['faqs'] = $s->fetchAll();
    } catch (Throwable $e) {
        // DB ไม่พร้อม — แสดงผลว่าง
    }
}

include 'includes/header.php';
?>

<section class="bg-brand-navy text-white py-12">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">ค้นหาบนเว็บไซต์</h1>
        <form method="get" action="/search.php" class="flex gap-2 max-w-2xl">
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="ค้นหาบทความ แผนประกัน คำถามที่พบบ่อย..." required
                   class="flex-1 px-4 py-3 rounded-xl border-0 outline-none text-brand-text">
            <button type="submit" class="bg-brand-green hover:bg-brand-greenHover text-white font-bold px-6 py-3 rounded-xl transition flex items-center gap-2">
                <i data-lucide="search" class="w-5 h-5"></i> ค้นหา
            </button>
        </form>
    </div>
</section>

<section class="py-12">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <?php if ($q === ''): ?>
            <p class="text-brand-gray text-center py-12">พิมพ์คำที่ต้องการค้นหา เช่น "ประกันสุขภาพ", "เคลม", "ลดหย่อนภาษี"</p>
        <?php elseif (empty($results['articles']) && empty($results['products']) && empty($results['faqs'])): ?>
            <div class="text-center py-12">
                <p class="text-xl font-bold text-brand-navy mb-2">ไม่พบผลการค้นหา "<?= htmlspecialchars($q) ?>"</p>
                <p class="text-brand-gray">ลองเปลี่ยนคำค้น หรือติดต่อเราผ่าน LINE @945ampel เพื่อสอบถามโดยตรง</p>
            </div>
        <?php else: ?>

        <?php if (!empty($results['articles'])): ?>
        <h2 class="text-xl font-bold text-brand-navy mb-4 mt-8">📝 บทความ (<?= count($results['articles']) ?>)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($results['articles'] as $a): ?>
            <a href="/article.php?id=<?= (int)$a['id'] ?>" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover-card group">
                <span class="text-[10px] font-bold text-brand-navy bg-brand-light px-3 py-1 rounded-full"><?= htmlspecialchars($a['tag']) ?></span>
                <h3 class="font-bold text-brand-text mt-3 group-hover:text-brand-navy transition leading-snug"><?= htmlspecialchars($a['title']) ?></h3>
                <p class="text-xs text-brand-gray mt-2"><?= htmlspecialchars(mb_substr((string)$a['excerpt'], 0, 100)) ?>…</p>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($results['products'])): ?>
        <h2 class="text-xl font-bold text-brand-navy mb-4 mt-8">🛡️ แผนประกัน (<?= count($results['products']) ?>)</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($results['products'] as $p): ?>
            <?php
            $catPage = match ($p['category']) {
                'life' => '/life.php', 'health' => '/health.php', default => '/general.php',
            };
            ?>
            <a href="<?= $catPage ?>#<?= htmlspecialchars($p['badge']) ?>" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover-card group">
                <span class="text-[10px] font-bold text-brand-navy bg-brand-light px-3 py-1 rounded-full"><?= htmlspecialchars($p['badge']) ?></span>
                <h3 class="font-bold text-brand-text mt-3 group-hover:text-brand-navy transition"><?= htmlspecialchars($p['title']) ?></h3>
                <p class="text-xs text-brand-gray mt-2"><?= htmlspecialchars(mb_substr((string)$p['desc_text'], 0, 100)) ?>…</p>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($results['faqs'])): ?>
        <h2 class="text-xl font-bold text-brand-navy mb-4 mt-8">❓ คำถามที่พบบ่อย (<?= count($results['faqs']) ?>)</h2>
        <div class="space-y-4 mb-8">
            <?php foreach ($results['faqs'] as $f): ?>
            <a href="/faq.php#<?= (int)$f['id'] ?>" class="block bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover-card">
                <span class="text-[10px] font-bold text-brand-navy bg-brand-light px-3 py-1 rounded-full"><?= htmlspecialchars($f['group_name']) ?></span>
                <h3 class="font-bold text-brand-text mt-2"><?= htmlspecialchars($f['question']) ?></h3>
                <p class="text-xs text-brand-gray mt-1"><?= htmlspecialchars(mb_substr((string)$f['answer'], 0, 120)) ?>…</p>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

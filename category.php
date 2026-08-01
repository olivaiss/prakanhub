<?php
$slug = $_GET['slug'] ?? 'life';
$pageTitle = 'หมวดหมู่ประกัน';

// ═══ ให้ getDB() พร้อมใช้ก่อน include header (DB connection) ═══
try {
    require_once __DIR__ . '/includes/db.php';
} catch (Throwable $e) {
    // DB ไม่พร้อม — fallback redirect
}

// ═══ หมวดจาก DB + แผนในหมวด (ต้องก่อน include header — ใช้ใน title/SEO) ═══
$cat = null;
$plans = [];
try {
    if (function_exists('getDB')) {
        $__stmt = getDB()->prepare('SELECT * FROM categories WHERE slug = ? AND is_active = 1 LIMIT 1');
        $__stmt->execute([$slug]);
        $cat = $__stmt->fetch();

        if ($cat) {
            // แผนของหมวด: หมวดหลัก (life/health/general) = ทั้งหมดของหมวดแม่, หมวดย่อย = แผน badge ที่แมป
            $__plans = getDB()->query('SELECT id, title, badge, category, desc_text, img, premium_from, company, company_color, company_logo FROM products WHERE is_active = 1 ORDER BY category, sort_order, id')->fetchAll();
            $__map = [];
            foreach ($__plans as $__p) {
                $__map[$__p['category']][] = $__p;
            }
            $__link = $cat['link_url'] ?? '';
            $__mainCat = null;
            if (strpos($__link, '/life.php') === 0) $__mainCat = 'life';
            elseif (strpos($__link, '/health.php') === 0) $__mainCat = 'health';
            elseif (strpos($__link, '/general.php') === 0) $__mainCat = 'general';

            // หมวดหลัก (life/health/general) → ไปหน้า Hub (life.php/health.php/general.php — ไม่แสดงแผนซ้ำ)
            if ($slug === 'life' || $slug === 'health' || $slug === 'general') {
                header('Location: /' . $slug . '.php');
                exit;
            } else {
                // หมวดย่อย → badge map (เฉพาะ badge ของหมวดนี้ — แต่ละแผนอยู่ในหน้าเดียว ไม่ซ้ำ)
                $__badgeMap = [
                    'savings' => ['saving'], 'pension' => ['retirement'], 'income' => ['income'],
                    'critical' => ['critical'], 'accident' => ['accident'], 'kids' => ['kids'],
                    'car' => ['motor'], 'travel' => ['travel'], 'group' => ['group'],
                    'tax' => [], 'inheritance' => ['inheritance'], 'unit-linked' => ['unit-linked'],
                    'senior' => ['senior', 'senior50'], 'cancer' => ['cancer'],
                    'nocopay' => ['nocopay'], 'additional' => ['additional'], 'property' => ['property'],
                ];
                $__wanted = $__badgeMap[$slug] ?? [];
                // ค้นทุกหมวด (ไม่จำกัด mainCat — link_url ตอนนี้ชี้ category.php โดยตรง)
                foreach ($__map as $__catPlans) {
                    foreach ($__catPlans as $__p) {
                        if (in_array($__p['badge'], $__wanted, true)) $plans[] = $__p;
                    }
                }
            }
            $pageTitle = $cat['title'];
        }
    }
} catch (Throwable $e) { /* fallback */ }

// fallback: ไม่มี DB → redirect ไปหน้าเดิม
if (!$cat) {
    header('Location: /life.php');
    exit;
}

include 'includes/header.php';
?>

<!-- Hero -->
<section class="pt-12 pb-10 bg-brand-light">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <nav class="flex items-center gap-2 text-xs text-brand-gray mb-4">
            <a href="/index.php" class="hover:text-brand-navy transition">หน้าแรก</a>
            <span>›</span>
            <span class="text-brand-navy font-medium"><?= htmlspecialchars($cat['title']) ?></span>
        </nav>
        <div class="flex items-start gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center justify-center shrink-0">
                <i data-lucide="<?= htmlspecialchars($cat['icon'] ?? 'shield') ?>" class="w-7 h-7 text-brand-navy"></i>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-brand-navy leading-tight mb-2"><?= htmlspecialchars($cat['title']) ?></h1>
                <p class="text-brand-text leading-relaxed max-w-2xl"><?= htmlspecialchars(str_replace('<br>', ' ', (string)($cat['description'] ?? ''))) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- รายการแผน -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <?php if (empty($plans)): ?>
        <div class="max-w-2xl mx-auto text-center">
            <p class="text-brand-gray py-4">กำลังเตรียมแผนในหมวดนี้ — ติดต่อเราเพื่อสอบถามได้เลย</p>
            <?php
            $__relMap = [
                'tax' => ['savings' => 'ประกันออมทรัพย์', 'pension' => 'ประกันบำนาญ'],
                'corporate' => ['group' => 'ประกันกลุ่ม', 'property' => 'ประกันบ้าน/คอนโด'],
            ];
            $__rels = $__relMap[$slug] ?? [];
            ?>
            <?php if (!empty($__rels)): ?>
            <p class="text-sm text-brand-gray mb-4">หมวดที่เกี่ยวข้อง:</p>
            <div class="flex flex-wrap justify-center gap-3">
                <?php foreach ($__rels as $__rslug => $__rtitle): ?>
                <a href="/category.php?slug=<?= htmlspecialchars($__rslug) ?>" class="text-sm font-bold text-brand-navy border border-brand-navy hover:bg-brand-light rounded-lg px-4 py-2 transition"><?= htmlspecialchars($__rtitle) ?> →</a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($plans as $p): ?>
            <div class="group bg-white rounded-2xl border border-gray-100 hover:shadow-lg hover:border-brand-navy/20 transition p-6 flex flex-col">
                <?php
                $__badgeTh = [
                    'saving' => 'ออมทรัพย์', 'tax' => 'ลดหย่อนภาษี', 'retirement' => 'บำนาญ', 'inheritance' => 'มรดก',
                    'unit-linked' => 'ยูนิตลิงค์', 'senior' => 'ผู้สูงอายุ', 'senior50' => 'ผู้สูงอายุ 50+', 'accident' => 'อุบัติเหตุ',
                    'critical' => 'โรคร้ายแรง', 'cancer' => 'มะเร็ง', 'kids' => 'เด็ก', 'nocopay' => 'ไม่มีส่วนร่วมจ่าย',
                    'additional' => 'เพิ่มเติม', 'income' => 'ชดเชยรายได้', 'motor' => 'รถยนต์', 'property' => 'บ้าน/ทรัพย์สิน',
                    'travel' => 'เดินทาง', 'group' => 'กลุ่ม',
                ];
                ?>
                <?php if (!empty($p['badge'])): ?>
                <span class="text-xs font-bold text-brand-green mb-2"><?= htmlspecialchars($__badgeTh[$p['badge']] ?? $p['badge']) ?></span>
                <?php endif; ?>
                <h2 class="font-bold text-brand-navy group-hover:text-brand-navyHover transition text-lg leading-snug mb-2"><?= htmlspecialchars($p['title']) ?></h2>
                <?php
                $__co = $p['company'] ?: 'อลิอันซ์ อยุธยา';
                $__cColor = $p['company_color'] ?: '0058A8';
                $__cLogo = $p['company_logo'] ?? '';
                $__cShort = mb_substr($__co, 0, 4);
                ?>
                <div class="flex items-center gap-2 mb-3">
                    <?php if (!empty($__cLogo)): ?>
                    <div class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                        <img src="<?= htmlspecialchars($__cLogo) ?>" alt="<?= htmlspecialchars($__co) ?>" class="w-full h-full object-contain p-0.5" loading="lazy">
                    </div>
                    <?php else: ?>
                    <div class="w-7 h-7 rounded-md flex items-center justify-center overflow-hidden shrink-0" style="background:#<?= htmlspecialchars($__cColor) ?>">
                        <span class="text-white font-bold text-[9px] leading-none px-0.5 text-center"><?= htmlspecialchars($__cShort) ?></span>
                    </div>
                    <?php endif; ?>
                    <span class="text-xs font-semibold text-brand-gray"><?= htmlspecialchars($__co) ?></span>
                </div>
                <p class="text-sm text-brand-gray leading-relaxed mb-4 flex-1 line-clamp-3"><?= htmlspecialchars(implode(' ', array_slice(explode(',', (string)$p['desc_text']), 0, 3))) ?></p>
                <?php if (!empty($p['premium_from'])): ?>
                <div class="text-sm text-brand-text mb-4">เบี้ยเริ่มต้น <span class="font-bold text-brand-green"><?= htmlspecialchars(preg_replace('/^เริ่มต้น\s*/', '', (string)$p['premium_from'])) ?></span></div>
                <?php endif; ?>
                <div class="flex gap-2 mt-auto pt-3 border-t border-gray-100">
                    <a href="/plan.php?id=<?= (int)$p['id'] ?>" class="flex-1 text-center text-sm font-bold text-brand-navy border border-brand-navy hover:bg-brand-light rounded-lg py-2 transition">ดูรายละเอียด</a>
                    <a href="/form/?plan=<?= urlencode($cat['title']) ?>&plan_name=<?= urlencode($p['title']) ?>" class="flex-1 text-center text-sm font-bold text-white bg-brand-green hover:bg-brand-greenHover rounded-lg py-2 transition">เลือกแผนนี้</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section class="pb-16">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="bg-brand-navy rounded-2xl p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="text-white font-bold text-2xl mb-2">ไม่แน่ใจว่าเลือกแผนไหนดี?</h2>
                <p class="text-blue-200">ปรึกษาฟรี — บอกความต้องการ แล้วเราช่วยเลือกแผนที่เหมาะกับคุณ</p>
            </div>
            <a href="/form/?plan=<?= urlencode($cat['title']) ?>" class="bg-brand-green hover:bg-brand-greenHover text-white font-bold px-8 py-3 rounded-xl transition whitespace-nowrap">ปรึกษาเราเลย</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

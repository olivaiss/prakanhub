<?php
$pageTitle = 'แผนประกันทั้งหมด';
try {
    require_once __DIR__ . '/includes/db.php';
} catch (Throwable $e) { /* no DB */ }

// ═══ แผนทั้งหมดจาก DB (group by หมวดหลัก) ═══
$__groups = ['life' => ['title' => 'ประกันชีวิต', 'plans' => []], 'health' => ['title' => 'ประกันสุขภาพ', 'plans' => []], 'general' => ['title' => 'ประกันทั่วไป', 'plans' => []]];
try {
    if (function_exists('getDB')) {
        $__rows = getDB()->query('SELECT id, title, badge, category, desc_text, premium_from, company, company_color, company_logo FROM products WHERE is_active = 1 ORDER BY category, sort_order, id')->fetchAll();
        foreach ($__rows as $__p) {
            if (isset($__groups[$__p['category']])) {
                $__groups[$__p['category']]['plans'][] = $__p;
            }
        }
        // corporate (SME) ไปรวมใน general
        $__corp = getDB()->query('SELECT id, title, badge, category, desc_text, premium_from, company, company_color, company_logo FROM products WHERE category = "corporate" AND is_active = 1 ORDER BY sort_order, id')->fetchAll();
        foreach ($__corp as $__p) {
            $__groups['general']['plans'][] = $__p;
        }
    }
} catch (Throwable $e) { /* fallback */ }

$__total = array_sum(array_map(fn($g) => count($g['plans']), $__groups));

$__badgeTh = [
    'saving' => 'ออมทรัพย์', 'tax' => 'ลดหย่อนภาษี', 'retirement' => 'บำนาญ', 'inheritance' => 'มรดก',
    'unit-linked' => 'ยูนิตลิงค์', 'senior' => 'ผู้สูงอายุ', 'senior50' => 'ผู้สูงอายุ 50+', 'accident' => 'อุบัติเหตุ',
    'critical' => 'โรคร้ายแรง', 'cancer' => 'มะเร็ง', 'kids' => 'เด็ก', 'nocopay' => 'ไม่มีส่วนร่วมจ่าย',
    'additional' => 'เพิ่มเติม', 'income' => 'ชดเชยรายได้', 'motor' => 'รถยนต์', 'property' => 'บ้าน/ทรัพย์สิน',
    'travel' => 'เดินทาง', 'group' => 'กลุ่ม',
];

include 'includes/header.php';
?>

<!-- Hero -->
<section class="bg-brand-navy text-white py-12 md:py-14">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <nav class="text-sm text-blue-200 mb-3">
            <a href="/index.php" class="hover:text-white transition">หน้าแรก</a>
            <span class="mx-2">/</span>
            <span class="text-white">แผนประกันทั้งหมด</span>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold mb-3">แผนประกันทั้งหมด (<?= $__total ?>)</h1>
        <p class="text-blue-200 text-lg max-w-3xl">รวมทุกแผนจากอลิอันซ์ อยุธยา — เปรียบเทียบและเลือกที่เหมาะกับคุณ</p>
    </div>
</section>

<?php foreach ($__groups as $__gk => $__g): if (empty($__g['plans'])) continue; ?>
<!-- หมวด: <?= $__g['title'] ?> -->
<section class="py-10 <?= $__gk === 'health' ? 'bg-brand-light' : '' ?>">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl font-bold text-brand-navy mb-1"><?= $__g['title'] ?> (<?= count($__g['plans']) ?>)</h2>
        <a href="/category.php?slug=<?= $__gk ?>" class="text-sm font-semibold text-brand-green hover:underline inline-block mb-6">ดูหมวด<?= $__g['title'] ?> →</a>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($__g['plans'] as $__p):
                $__co = $__p['company'] ?: 'อลิอันซ์ อยุธยา';
                $__cColor = $__p['company_color'] ?: '0058A8';
                $__cLogo = $__p['company_logo'] ?? '';
            ?>
            <div class="group bg-white rounded-2xl border border-gray-100 hover:shadow-lg hover:border-brand-navy/20 transition p-6 flex flex-col">
                <?php if (!empty($__p['badge'])): ?>
                <span class="text-xs font-bold text-brand-green mb-2"><?= htmlspecialchars($__badgeTh[$__p['badge']] ?? $__p['badge']) ?></span>
                <?php endif; ?>
                <h3 class="font-bold text-brand-navy group-hover:text-brand-navyHover transition text-lg leading-snug mb-2"><?= htmlspecialchars($__p['title']) ?></h3>
                <div class="flex items-center gap-2 mb-3">
                    <?php if (!empty($__cLogo)): ?>
                    <div class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center overflow-hidden shrink-0"><img src="<?= htmlspecialchars($__cLogo) ?>" alt="<?= htmlspecialchars($__co) ?>" class="w-full h-full object-contain p-0.5" loading="lazy"></div>
                    <?php else: ?>
                    <div class="w-7 h-7 rounded-md flex items-center justify-center overflow-hidden shrink-0" style="background:#<?= htmlspecialchars($__cColor) ?>"><span class="text-white font-bold text-[9px] leading-none px-0.5 text-center"><?= htmlspecialchars(mb_substr($__co, 0, 4)) ?></span></div>
                    <?php endif; ?>
                    <span class="text-xs font-semibold text-brand-gray"><?= htmlspecialchars($__co) ?></span>
                </div>
                <p class="text-sm text-brand-gray leading-relaxed mb-4 flex-1 line-clamp-3"><?= htmlspecialchars(implode(' ', array_slice(explode(',', (string)$__p['desc_text']), 0, 3))) ?></p>
                <?php if (!empty($__p['premium_from'])): ?>
                <div class="text-sm text-brand-text mb-4">เบี้ยเริ่มต้น <span class="font-bold text-brand-green"><?= htmlspecialchars(preg_replace('/^เริ่มต้น\s*/', '', (string)$__p['premium_from'])) ?></span></div>
                <?php endif; ?>
                <div class="flex gap-2 mt-auto pt-3 border-t border-gray-100">
                    <a href="/plan.php?id=<?= (int)$__p['id'] ?>" class="flex-1 text-center text-sm font-bold text-brand-navy border border-brand-navy hover:bg-brand-light rounded-lg py-2 transition">ดูรายละเอียด</a>
                    <a href="/form/?plan=<?= urlencode($__g['title']) ?>&plan_name=<?= urlencode($__p['title']) ?>" class="flex-1 text-center text-sm font-bold text-white bg-brand-green hover:bg-brand-greenHover rounded-lg py-2 transition">เลือกแผนนี้</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endforeach; ?>

<!-- CTA -->
<section class="pb-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="bg-brand-navy rounded-2xl py-10 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4 text-white">ไม่แน่ใจว่าเลือกแผนไหนดี?</h2>
            <p class="text-blue-200 mb-6">ปรึกษาฟรี — บอกความต้องการ แล้วเราช่วยเลือกแผนที่เหมาะกับคุณ</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/form/" class="inline-flex items-center gap-2 bg-white text-brand-navy font-bold px-8 py-3 rounded-full hover:bg-gray-100 transition shadow-md">ปรึกษาฟรี</a>
                <a href="https://lin.ee/QngrNQ3" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-brand-green text-white font-bold px-8 py-3 rounded-full hover:bg-brand-greenHover transition shadow-md"><img src="assets/icon/line.svg" class="w-5 h-5" alt="LINE"> แชทผ่าน LINE</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

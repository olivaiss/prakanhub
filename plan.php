<?php
$pageTitle = 'รายละเอียดแผนประกัน';

// ═══ ให้ getDB() พร้อมใช้ก่อน include header (DB connection) ═══
try {
    require_once __DIR__ . '/includes/db.php';
} catch (Throwable $e) {
    // DB ไม่พร้อม — fallback JSON
}

// ═══ ดึงแผนจาก DB (?id=N) — fallback: หน้าจาก JSON (ต้องก่อน include header — ใช้ใน title) ═══
$plan = null;
$related = [];
try {
    if (function_exists('getDB')) {
        $__id = (int)($_GET['id'] ?? 0);
        $__stmt = getDB()->prepare('SELECT * FROM products WHERE id = ? AND is_active = 1 LIMIT 1');
        $__stmt->execute([$__id]);
        $plan = $__stmt->fetch();
        if ($plan) {
            $pageTitle = $plan['title'] . ' — ' . $plan['badge'];
            // แผนที่เกี่ยวข้อง (หมวด/กลุ่มเดียวกัน)
            $__r = getDB()->prepare('SELECT id, title, badge, premium_from, img FROM products WHERE category = ? AND is_active = 1 AND id != ? ORDER BY sort_order, id LIMIT 4');
            $__r->execute([$plan['category'], $plan['id']]);
            $related = $__r->fetchAll();
        }
    }
} catch (Throwable $e) { /* fallback below */ }

// fallback: insurance-data.json
if (!$plan) {
    $__d = json_decode(file_get_contents(__DIR__ . '/assets/data/insurance-data.json'), true);
    $__found = null;
    foreach (['life', 'health', 'general'] as $__cat) {
        foreach (($__d[$__cat] ?? []) as $__group => $__plans) {
            foreach ($__plans as $__p) {
                if ((int)($__p['id'] ?? 0) === (int)($_GET['id'] ?? 0)) {
                    $__found = ['title' => $__p['name'], 'badge' => $__group, 'desc_text' => implode(', ', $__p['highlights'] ?? []), 'category' => $__cat, 'premium_from' => null];
                    break 3;
                }
            }
        }
    }
    if ($__found) $plan = $__found;
}

if (!$plan) {
    header('Location: /life.php');
    exit;
}

include 'includes/header.php';

// helpers
$__catLabels = ['life' => 'ประกันชีวิต', 'health' => 'ประกันสุขภาพ', 'general' => 'ประกันทั่วไป'];
$__catPage = ['life' => '/life.php', 'health' => '/health.php', 'general' => '/general.php'];
$__benefits = array_values(array_filter(array_map('trim', explode(',', (string)($plan['desc_text'] ?? '')))));
$__detail = array_values(array_filter(array_map('trim', explode("\n", (string)($plan['key_benefits'] ?? '')))));
if (empty($__detail)) $__detail = $__benefits;
$__cover = array_values(array_filter(array_map('trim', explode("\n", (string)($plan['coverage'] ?? '')))));
$__planList = array_values(array_filter(array_map('trim', explode("\n", (string)($plan['plans'] ?? '')))));
?>

<!-- Breadcrumb -->
<section class="pt-10 pb-2 bg-brand-light">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <nav class="flex flex-wrap items-center gap-2 text-xs text-brand-gray">
            <a href="/index.php" class="hover:text-brand-navy transition">หน้าแรก</a>
            <span>›</span>
            <a href="<?= $__catPage[$plan['category']] ?? '/life.php' ?>" class="hover:text-brand-navy transition"><?= $__catLabels[$plan['category']] ?? 'ประกัน' ?></a>
            <span>›</span>
            <span class="text-brand-navy font-medium"><?= htmlspecialchars($plan['title']) ?></span>
        </nav>
    </div>
</section>

<!-- Hero -->
<section class="pt-8 pb-12 bg-brand-light">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid md:grid-cols-[1fr_380px]">
                <div class="p-8 md:p-10">
                    <?php if (!empty($plan['badge'])): ?>
                    <span class="inline-block text-xs font-bold px-3 py-1 rounded-full bg-brand-navy text-white mb-4"><?= htmlspecialchars($plan['badge']) ?></span>
                    <?php endif; ?>
                    <h1 class="text-3xl md:text-4xl font-bold text-brand-navy leading-tight mb-3"><?= htmlspecialchars($plan['title']) ?></h1>
                    <p class="text-brand-text leading-relaxed mb-6"><?= htmlspecialchars(implode(' ', $__benefits)) ?></p>
                    <div class="flex flex-wrap items-center gap-3">
                        <?php if (!empty($plan['premium_from'])): ?>
                        <div class="bg-brand-green/10 border border-brand-green/30 text-brand-green rounded-xl px-4 py-2">
                            <div class="text-[10px] font-medium opacity-80">เบี้ยเริ่มต้น</div>
                            <div class="font-bold text-lg leading-tight"><?= htmlspecialchars(preg_replace('/^เริ่มต้น\s*/', '', (string)$plan['premium_from'])) ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($plan['age_range'])): ?>
                        <div class="bg-gray-100 rounded-xl px-4 py-2">
                            <div class="text-[10px] font-medium text-brand-gray">อายุรับประกัน</div>
                            <div class="font-semibold text-brand-navy"><?= htmlspecialchars($plan['age_range']) ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($plan['room_rate'])): ?>
                        <div class="bg-gray-100 rounded-xl px-4 py-2">
                            <div class="text-[10px] font-medium text-brand-gray">ค่าห้องรายวัน</div>
                            <div class="font-semibold text-brand-navy"><?= htmlspecialchars($plan['room_rate']) ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="bg-brand-navy p-8 flex flex-col justify-center gap-4">
                    <h3 class="text-white font-bold text-lg">สนใจแผนนี้ไหม?</h3>
                    <p class="text-blue-200 text-sm leading-relaxed">กรอกข้อมูลเพียง 2 นาที ที่ปรึกษาจะติดต่อกลับเพื่อให้คำแนะนำฟรี</p>
                    <a href="/form/?plan=<?= urlencode($plan['title']) ?>" class="bg-brand-green hover:bg-brand-greenHover text-white font-bold py-3 rounded-xl text-center transition">📝 ทำแบบฟอร์มทันที</a>
                    <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $__sitePhone ?? '0891234567')) ?>" class="bg-white/10 hover:bg-white/20 text-white font-semibold py-3 rounded-xl text-center transition border border-white/20">📞 โทรสอบถาม</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- รายละเอียด -->
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <?php if (!empty($__detail)): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-brand-navy mb-5 flex items-center gap-2"><i data-lucide="shield-check" class="w-5 h-5 text-brand-green"></i> ความคุ้มครองหลัก</h2>
                    <ul class="space-y-3">
                        <?php foreach ($__detail as $__b): ?>
                        <li class="flex gap-3 text-brand-text leading-relaxed"><span class="text-brand-green font-bold mt-0.5">✓</span><?= htmlspecialchars($__b) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if (!empty($__cover)): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-brand-navy mb-5 flex items-center gap-2"><i data-lucide="list-checks" class="w-5 h-5 text-brand-green"></i> รายละเอียดความคุ้มครอง</h2>
                    <ul class="space-y-3">
                        <?php foreach ($__cover as $__c): ?>
                        <li class="flex gap-3 text-brand-text leading-relaxed"><span class="text-brand-green font-bold mt-0.5">✓</span><?= htmlspecialchars($__c) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if (!empty($__planList)): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8">
                    <h2 class="text-xl font-bold text-brand-navy mb-5 flex items-center gap-2"><i data-lucide="layers" class="w-5 h-5 text-brand-green"></i> แบบแผน / ระดับความคุ้มครอง</h2>
                    <div class="grid md:grid-cols-2 gap-3">
                        <?php foreach ($__planList as $__pl): ?>
                        <div class="bg-gray-50 rounded-xl px-4 py-3 text-sm text-brand-text border border-gray-100"><?= htmlspecialchars($__pl) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-brand-light rounded-2xl p-6 md:p-8">
                    <h2 class="text-lg font-bold text-brand-navy mb-3">💡 ต้องการรายละเอียดเพิ่มเติม?</h2>
                    <p class="text-brand-text text-sm leading-relaxed mb-4">ความคุ้มครองทั้งหมดขึ้นอยู่กับกรมธรรม์ฉบับจริง — ที่ปรึกษาของเรายินดีอธิบายเงื่อนไขและคำนวณเบี้ยให้ตามอายุและความต้องการของคุณ ฟรี ไม่มีข้อผูกมัด</p>
                    <a href="/form/?plan=<?= urlencode($plan['title']) ?>" class="inline-block bg-brand-navy hover:bg-brand-navyHover text-white font-bold px-6 py-3 rounded-xl transition">ขอใบเสนอราคา</a>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-6">
                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-brand-navy mb-4 flex items-center gap-2"><i data-lucide="building-2" class="w-4 h-4 text-brand-green"></i> บริษัทประกัน</h3>
                    <?php
                    // ═══ แบนเนอร์สุ่ม (จากตาราง banners — เปิดใช้งาน) ═══
                    $__bannerFile = '';
                    try {
                        if (function_exists('getDB')) {
                            $__b = getDB()->query('SELECT filename FROM banners WHERE is_active = 1 ORDER BY RAND() LIMIT 1')->fetch();
                            if ($__b && !empty($__b['filename'])) $__bannerFile = '/assets/image/baner/' . rawurlencode($__b['filename']);
                        }
                    } catch (Throwable $e) { /* ignore */ }
                    ?>
                    <?php if ($__bannerFile !== ''): ?>
                    <div class="rounded-xl overflow-hidden mb-4">
                        <img src="<?= htmlspecialchars($__bannerFile) ?>" alt="แบนเนอร์" class="w-full h-auto block" loading="lazy">
                    </div>
                    <?php endif; ?>
                    <?php
                    $__pco = $plan['company'] ?: 'อลิอันซ์ อยุธยา';
                    $__pcolor = $plan['company_color'] ?: '0058A8';
                    $__plogo = $plan['company_logo'] ?? '';
                    ?>
                    <div class="flex items-center gap-3 mb-3">
                        <?php if (!empty($__plogo)): ?>
                        <div class="w-12 h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center overflow-hidden">
                            <img src="<?= htmlspecialchars($__plogo) ?>" alt="<?= htmlspecialchars($__pco) ?>" class="w-full h-full object-contain p-1">
                        </div>
                        <?php else: ?>
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center overflow-hidden shrink-0" style="background:#<?= htmlspecialchars($__pcolor) ?>">
                            <span class="text-white font-bold text-xs leading-none px-1 text-center"><?= htmlspecialchars(mb_substr($__pco, 0, 4)) ?></span>
                        </div>
                        <?php endif; ?>
                        <p class="font-semibold text-brand-text"><?= htmlspecialchars($__pco) ?></p>
                    </div>
                    <p class="text-xs text-brand-gray mt-2 leading-relaxed"><?= htmlspecialchars($__pco) ?> — หนึ่งในกลุ่มบริษัทประกันชั้นนำของไทย ให้บริการครบทั้งชีวิต สุขภาพ และวินาศภัย</p>
                    <?php if (!empty($plan['details_url'])): ?>
                    <a href="<?= htmlspecialchars($plan['details_url']) ?>" target="_blank" rel="noopener noreferrer" class="mt-4 inline-block text-sm font-semibold text-brand-navy hover:underline">ดูข้อมูลจากเว็บบริษัท →</a>
                    <?php endif; ?>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h3 class="font-bold text-brand-navy mb-4">ขั้นตอนง่ายๆ</h3>
                    <ol class="space-y-4">
                        <li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-brand-navy text-white text-xs font-bold flex items-center justify-center shrink-0">1</span><span class="text-sm text-brand-text">เลือกแผนที่สนใจ</span></li>
                        <li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-brand-navy text-white text-xs font-bold flex items-center justify-center shrink-0">2</span><span class="text-sm text-brand-text">กรอกแบบฟอร์ม (2 นาที)</span></li>
                        <li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-brand-navy text-white text-xs font-bold flex items-center justify-center shrink-0">3</span><span class="text-sm text-brand-text">ที่ปรึกษาติดต่อกลับ + ส่งใบเสนอราคา</span></li>
                        <li class="flex gap-3"><span class="w-6 h-6 rounded-full bg-brand-navy text-white text-xs font-bold flex items-center justify-center shrink-0">4</span><span class="text-sm text-brand-text">ตัดสินใจและรับกรมธรรม์</span></li>
                    </ol>
                </div>

                <div class="bg-brand-navy rounded-2xl p-6 text-white">
                    <h3 class="font-bold mb-2">ปรึกษาฟรี ไม่มีข้อผูกมัด</h3>
                    <p class="text-blue-200 text-sm mb-4 leading-relaxed">วางแผนประกันให้ตรงกับเป้าหมายชีวิตของคุณ</p>
                    <a href="https://lin.ee/<?= htmlspecialchars(trim(str_replace(['https://line.me/R/ti/p/', '@', 'lin.ee/'], '', $__siteLineUrl ?? ''), '/')) ?>" target="_blank" rel="noopener noreferrer" class="block bg-[#06C755] hover:bg-[#05B54C] text-white font-bold py-3 rounded-xl text-center transition mb-3">LINE ปรึกษาเลย</a>
                    <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9]/', '', $__sitePhone ?? '0891234567')) ?>" class="block bg-white/10 hover:bg-white/20 text-white font-semibold py-3 rounded-xl text-center transition">📞 <?= htmlspecialchars($__sitePhone ?? '089-123-4567') ?></a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<!-- แผนที่เกี่ยวข้อง -->
<section class="pb-16">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <h2 class="text-2xl font-bold text-brand-navy mb-6">แผนที่เกี่ยวข้อง</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php foreach ($related as $__rel): ?>
            <a href="/plan.php?id=<?= (int)$__rel['id'] ?>" class="group bg-white rounded-2xl border border-gray-100 hover:shadow-md transition p-5">
                <div class="text-xs font-bold text-brand-green mb-2"><?= htmlspecialchars($__rel['badge']) ?></div>
                <h3 class="font-bold text-brand-navy group-hover:text-brand-navyHover transition mb-1 leading-snug"><?= htmlspecialchars($__rel['title']) ?></h3>
                <?php if (!empty($__rel['premium_from'])): ?>
                <div class="text-sm text-brand-text">เริ่มต้น <span class="font-bold text-brand-green"><?= htmlspecialchars($__rel['premium_from']) ?></span></div>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

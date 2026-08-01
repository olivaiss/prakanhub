<?php
$pageTitle = 'ประกันทั่วไป';
try {
    require_once __DIR__ . '/includes/db.php';
} catch (Throwable $e) { /* no DB */ }

// ═══ หมวดย่อยของประกันทั่วไป (จาก DB categories — ลิงก์ไปหน้าแยก) ═══
$__subSlugs = ['car', 'travel', 'group', 'corporate', 'property'];
$__subs = [];
try {
    if (function_exists('getDB')) {
        $__rows = getDB()->query('SELECT slug, title, icon, description, link_url FROM categories WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
        foreach ($__rows as $__c) {
            if (in_array($__c['slug'], $__subSlugs, true)) {
                $__subs[] = $__c;
            }
        }
    }
} catch (Throwable $e) { /* fallback below */ }

// fallback ถ้าไม่มี DB
if (empty($__subs)) {
    $__subs = [
        ['slug' => 'car', 'title' => 'ประกันรถยนต์', 'icon' => 'car', 'description' => 'คุ้มครองรถยนต์ครบวงจร ทุกชั้น ทุกยี่ห้อ', 'link_url' => '/category.php?slug=car'],
        ['slug' => 'travel', 'title' => 'ประกันเดินทาง', 'icon' => 'plane', 'description' => 'เดินทางอุ่นใจ ทั่วโลก ทั้งรายเที่ยวและรายปี', 'link_url' => '/category.php?slug=travel'],
        ['slug' => 'group', 'title' => 'ประกันกลุ่ม', 'icon' => 'users', 'description' => 'คุ้มครองพนักงานทั้งองค์กร', 'link_url' => '/category.php?slug=group'],
        ['slug' => 'corporate', 'title' => 'ประกันนิติบุคคล', 'icon' => 'building-2', 'description' => 'บริหารความเสี่ยงธุรกิจ', 'link_url' => '/category.php?slug=corporate'],
        ['slug' => 'property', 'title' => 'ประกันบ้าน/คอนโด', 'icon' => 'home', 'description' => 'คุ้มครองตัวบ้านและทรัพย์สิน ไฟไหม้ น้ำท่วม โจรกรรม', 'link_url' => '/category.php?slug=property'],
    ];
}

// นับจำนวนแผนต่อหมวด (badge map)
$__badgeMap = [
    'car' => ['motor'], 'travel' => ['travel'], 'group' => ['group'],
    'corporate' => ['property'], 'property' => ['property'],
];
$__counts = [];
try {
    if (function_exists('getDB')) {
        $__plans = getDB()->query('SELECT badge, COUNT(*) c FROM products WHERE category = "general" AND is_active = 1 GROUP BY badge')->fetchAll();
        foreach ($__plans as $__p) {
            $__counts[$__p['badge']] = (int)$__p['c'];
        }
    }
} catch (Throwable $e) { /* ignore */ }

// ═══ แผนทั้งหมดของหมวด (แสดงใต้การ์ดหมวดย่อย) ═══
$__allPlans = [];
try {
    if (function_exists('getDB')) {
        $__allPlans = getDB()->query('SELECT id, title, badge, desc_text, premium_from, company, company_color, company_logo FROM products WHERE category = "general" AND is_active = 1 ORDER BY sort_order, id')->fetchAll();
    }
} catch (Throwable $e) { /* ignore */ }

include 'includes/header.php';
?>

<!-- Hero -->
<section class="bg-brand-navy text-white py-12 md:py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <nav class="text-sm text-blue-200 mb-3">
            <a href="/index.php" class="hover:text-white transition">หน้าแรก</a>
            <span class="mx-2">/</span>
            <span class="text-white">ประกันทั่วไป</span>
        </nav>
        <h1 class="text-3xl md:text-5xl font-bold mb-4">ประกันทั่วไป</h1>
        <p class="text-blue-200 text-lg max-w-3xl">คุ้มครองทรัพย์สินและไลฟ์สไตล์ของคุณ ตั้งแต่รถยนต์ บ้าน ไปจนถึงการเดินทางและธุรกิจ ด้วยประกันจากอลิอันซ์ อยุธยา</p>
    </div>
</section>

<!-- หมวดย่อย -->
<section class="py-12">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl font-bold text-brand-navy mb-2">หมวดย่อยประกันทั่วไป</h2>
        <p class="text-brand-gray mb-8">เลือกหมวดที่ตรงกับความต้องการของคุณ — แต่ละหมวดมีรายละเอียดแผนและเบี้ยให้เปรียบเทียบ</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($__subs as $s): ?>
            <?php
            $__cnt = 0;
            foreach (($__badgeMap[$s['slug']] ?? []) as $__b) { $__cnt += $__counts[$__b] ?? 0; }
            ?>
            <a href="<?= htmlspecialchars($s['link_url']) ?>" class="group bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-brand-navy/20 transition flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-brand-light flex items-center justify-center group-hover:bg-brand-navy transition-colors">
                        <i data-lucide="<?= htmlspecialchars($s['icon'] ?: 'shield') ?>" class="w-6 h-6 text-brand-navy group-hover:text-white transition-colors"></i>
                    </div>
                    <span class="text-[10px] font-bold text-brand-gray bg-gray-100 px-2.5 py-1 rounded-full"><?= $__cnt ?> แผน</span>
                </div>
                <h3 class="font-bold text-brand-navy text-lg mb-2 group-hover:text-brand-navyHover transition"><?= htmlspecialchars($s['title']) ?></h3>
                <p class="text-sm text-brand-gray leading-relaxed mb-4 flex-1"><?= htmlspecialchars(str_replace('<br>', ' ', (string)$s['description'])) ?></p>
                <span class="inline-flex items-center justify-center gap-1 text-sm font-bold text-white bg-brand-green hover:bg-brand-greenHover rounded-lg px-4 py-2 transition-all group-hover:gap-2">ดูแผนทั้งหมด <span>→</span></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- แผนทั้งหมด -->
<?php if (!empty($__allPlans)): ?>
<section class="pb-12">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl font-bold text-brand-navy mb-2">ประกันทั่วไปทั้งหมด (<?= count($__allPlans) ?>)</h2>
        <p class="text-brand-gray mb-8">ทุกแผนจากอลิอันซ์ อยุธยา — เปรียบเทียบและเลือกที่เหมาะกับคุณ</p>
        <?php
        $__badgeTh2 = [
            'saving' => 'ออมทรัพย์', 'tax' => 'ลดหย่อนภาษี', 'retirement' => 'บำนาญ', 'inheritance' => 'มรดก',
            'unit-linked' => 'ยูนิตลิงค์', 'senior' => 'ผู้สูงอายุ', 'senior50' => 'ผู้สูงอายุ 50+', 'accident' => 'อุบัติเหตุ',
            'critical' => 'โรคร้ายแรง', 'cancer' => 'มะเร็ง', 'kids' => 'เด็ก', 'nocopay' => 'ไม่มีส่วนร่วมจ่าย',
            'additional' => 'เพิ่มเติม', 'income' => 'ชดเชยรายได้', 'motor' => 'รถยนต์', 'property' => 'บ้าน/ทรัพย์สิน',
            'travel' => 'เดินทาง', 'group' => 'กลุ่ม',
        ];
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($__allPlans as $__ap):
                $__co = $__ap['company'] ?: 'อลิอันซ์ อยุธยา';
                $__cColor = $__ap['company_color'] ?: '0058A8';
                $__cLogo = $__ap['company_logo'] ?? '';
            ?>
            <div class="group bg-white rounded-2xl border border-gray-100 hover:shadow-lg hover:border-brand-navy/20 transition p-6 flex flex-col">
                <?php if (!empty($__ap['badge'])): ?>
                <span class="text-xs font-bold text-brand-green mb-2"><?= htmlspecialchars($__badgeTh2[$__ap['badge']] ?? $__ap['badge']) ?></span>
                <?php endif; ?>
                <h3 class="font-bold text-brand-navy group-hover:text-brand-navyHover transition text-lg leading-snug mb-2"><?= htmlspecialchars($__ap['title']) ?></h3>
                <div class="flex items-center gap-2 mb-3">
                    <?php if (!empty($__cLogo)): ?>
                    <div class="w-7 h-7 rounded-md bg-white border border-gray-200 flex items-center justify-center overflow-hidden shrink-0"><img src="<?= htmlspecialchars($__cLogo) ?>" alt="<?= htmlspecialchars($__co) ?>" class="w-full h-full object-contain p-0.5" loading="lazy"></div>
                    <?php else: ?>
                    <div class="w-7 h-7 rounded-md flex items-center justify-center overflow-hidden shrink-0" style="background:#<?= htmlspecialchars($__cColor) ?>"><span class="text-white font-bold text-[9px] leading-none px-0.5 text-center"><?= htmlspecialchars(mb_substr($__co, 0, 4)) ?></span></div>
                    <?php endif; ?>
                    <span class="text-xs font-semibold text-brand-gray"><?= htmlspecialchars($__co) ?></span>
                </div>
                <p class="text-sm text-brand-gray leading-relaxed mb-4 flex-1 line-clamp-3"><?= htmlspecialchars(implode(' ', array_slice(explode(',', (string)$__ap['desc_text']), 0, 3))) ?></p>
                <?php if (!empty($__ap['premium_from'])): ?>
                <div class="text-sm text-brand-text mb-4">เบี้ยเริ่มต้น <span class="font-bold text-brand-green"><?= htmlspecialchars(preg_replace('/^เริ่มต้น\s*/', '', (string)$__ap['premium_from'])) ?></span></div>
                <?php endif; ?>
                <div class="flex gap-2 mt-auto pt-3 border-t border-gray-100">
                    <a href="/plan.php?id=<?= (int)$__ap['id'] ?>" class="flex-1 text-center text-sm font-bold text-brand-navy border border-brand-navy hover:bg-brand-light rounded-lg py-2 transition">ดูรายละเอียด</a>
                    <a href="/form/?plan=<?= urlencode('ประกันทั่วไป') ?>&plan_name=<?= urlencode($__ap['title']) ?>" class="flex-1 text-center text-sm font-bold text-white bg-brand-green hover:bg-brand-greenHover rounded-lg py-2 transition">เลือกแผนนี้</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="pb-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="bg-brand-navy rounded-2xl py-10 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4 text-white">สนใจประกันทั่วไป?</h2>
            <p class="text-blue-200 mb-6">ให้คำปรึกษาฟรี! ไม่มีค่าใช้จ่าย พร้อมแนะนำแผนที่ใช่สำหรับคุณ</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/form/" class="inline-flex items-center gap-2 bg-white text-brand-navy font-bold px-8 py-3 rounded-full hover:bg-gray-100 transition shadow-md">ปรึกษาฟรี</a>
                <a href="https://lin.ee/QngrNQ3" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-brand-green text-white font-bold px-8 py-3 rounded-full hover:bg-brand-greenHover transition shadow-md"><img src="assets/icon/line.svg" class="w-5 h-5" alt="LINE"> แชทผ่าน LINE</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

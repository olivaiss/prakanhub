<?php
$pageTitle = 'ประกันสุขภาพ';
try {
    require_once __DIR__ . '/includes/db.php';
} catch (Throwable $e) { /* no DB */ }

// ═══ หมวดย่อยของประกันสุขภาพ (จาก DB categories — ลิงก์ไปหน้าแยก) ═══
$__subSlugs = ['critical', 'cancer', 'kids', 'nocopay', 'additional', 'income', 'senior'];
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
        ['slug' => 'critical', 'title' => 'ประกันโรคร้ายแรง', 'icon' => 'shield-alert', 'description' => 'คุ้มครองโรคหนักแบบรู้กัน เจอ จ่าย จบ', 'link_url' => '/category.php?slug=critical'],
        ['slug' => 'cancer', 'title' => 'ประกันมะเร็ง', 'icon' => 'shield-plus', 'description' => 'คุ้มครองมะเร็งทุกชนิด ทุกระยะ ตรวจเจอปุ๊บ จ่ายปั๊บ', 'link_url' => '/category.php?slug=cancer'],
        ['slug' => 'kids', 'title' => 'ประกันเด็ก', 'icon' => 'baby', 'description' => 'วางแผนอนาคตให้ลูกน้อย ตั้งแต่ออมถึงคุ้มครอง', 'link_url' => '/category.php?slug=kids'],
        ['slug' => 'nocopay', 'title' => 'ประกันสุขภาพไม่มีส่วนร่วมจ่าย', 'icon' => 'credit-card', 'description' => 'จ่ายตรงไม่ต้องสำรองจ่าย ไม่มีค่าใช้จ่ายส่วนแรก', 'link_url' => '/category.php?slug=nocopay'],
        ['slug' => 'additional', 'title' => 'ประกันสุขภาพเพิ่มเติมจากสวัสดิการ', 'icon' => 'layers', 'description' => 'เสริมความคุ้มครองจากสวัสดิการบริษัท/ประกันกลุ่ม', 'link_url' => '/category.php?slug=additional'],
        ['slug' => 'income', 'title' => 'ประกันชดเชยรายได้', 'icon' => 'dollar-sign', 'description' => 'เจ็บป่วยไม่หยุด รายได้ไม่ขาด', 'link_url' => '/category.php?slug=income'],
        ['slug' => 'senior', 'title' => 'ประกันผู้สูงอายุ', 'icon' => 'users', 'description' => 'อุ่นใจในวัยเกษียณด้วยความคุ้มครองที่ออกแบบมาสำหรับผู้สูงอายุ', 'link_url' => '/category.php?slug=senior'],
    ];
}

// นับจำนวนแผนต่อหมวด (badge map)
$__badgeMap = [
    'critical' => ['critical'], 'cancer' => ['cancer'], 'kids' => ['kids'], 'nocopay' => ['nocopay'],
    'additional' => ['additional'], 'income' => ['income'], 'senior' => ['senior50'],
];
$__counts = [];
try {
    if (function_exists('getDB')) {
        $__plans = getDB()->query('SELECT badge, COUNT(*) c FROM products WHERE category = "health" AND is_active = 1 GROUP BY badge')->fetchAll();
        foreach ($__plans as $__p) {
            $__counts[$__p['badge']] = (int)$__p['c'];
        }
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
            <span class="text-white">ประกันสุขภาพ</span>
        </nav>
        <h1 class="text-3xl md:text-5xl font-bold mb-4">ประกันสุขภาพ</h1>
        <p class="text-blue-200 text-lg max-w-3xl">สุขภาพดีคือทรัพย์สินที่มีค่าที่สุด วางแผนค่ารักษาพยาบาลล่วงหน้า ด้วยประกันสุขภาพจากอลิอันซ์ อยุธยา ครอบคลุมทั้งโรคร้ายแรง มะเร็ง เด็ก และผู้สูงอายุ</p>
    </div>
</section>

<!-- หมวดย่อย -->
<section class="py-12">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl font-bold text-brand-navy mb-2">หมวดย่อยประกันสุขภาพ</h2>
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

<!-- CTA -->
<section class="pb-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="bg-brand-navy rounded-2xl py-10 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-4 text-white">สนใจประกันสุขภาพ?</h2>
            <p class="text-blue-200 mb-6">ให้คำปรึกษาฟรี! ไม่มีค่าใช้จ่าย พร้อมแนะนำแผนที่ใช่สำหรับคุณ</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/form/" class="inline-flex items-center gap-2 bg-white text-brand-navy font-bold px-8 py-3 rounded-full hover:bg-gray-100 transition shadow-md">ปรึกษาฟรี</a>
                <a href="https://lin.ee/QngrNQ3" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 bg-brand-green text-white font-bold px-8 py-3 rounded-full hover:bg-brand-greenHover transition shadow-md"><img src="assets/icon/line.svg" class="w-5 h-5" alt="LINE"> แชทผ่าน LINE</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

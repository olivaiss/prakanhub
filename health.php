<?php
$data = json_decode(file_get_contents(__DIR__ . '/assets/data/insurance-data.json'), true);
$sections = $data['health'] ?? [];

// ═══ อ่านแผนประกันจากฐานข้อมูล (ตาราง products) — fallback: JSON ด้านบน ═══
try {
    if (function_exists('getDB')) {
        $__stmt = getDB()->query("SELECT title, desc_text, badge FROM products WHERE category = 'health' AND is_active = 1 ORDER BY sort_order, id");
        $__rows = $__stmt->fetchAll();
        if (count($__rows) > 0) {
            $sections = [];
            foreach ($__rows as $__r) {
                $__g = $__r['badge'] ?: 'อื่นๆ';
                if (!isset($sections[$__g])) $sections[$__g] = [];
                $__highlights = array_values(array_filter(array_map('trim', explode(',', (string)$__r['desc_text']))));
                $sections[$__g][] = [
                    'name' => $__r['title'],
                    'type' => $__r['badge'],
                    'highlights' => $__highlights ?: ['ติดต่อเราเพื่อดูรายละเอียด'],
                    'id' => (int)$__r['id'],
                    'premium_from' => $__r['premium_from'],
                ];
            }
        }
    }
} catch (Throwable $e) {
    // DB ไม่พร้อม — ใช้ JSON
}
?>
<?php include 'includes/header.php'; ?>

<section class="bg-brand-navy text-white py-12">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <nav class="text-sm text-blue-200 mb-3">
            <a href="/index.php" class="hover:text-white transition">หน้าแรก</a><span class="mx-2">/</span>
            <span class="text-white">ประกันสุขภาพ</span>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold mb-3">ประกันสุขภาพ</h1>
        <p class="text-blue-200 text-lg max-w-3xl">ดูแลสุขภาพคุณและคนที่คุณรัก ด้วยแผนประกันสุขภาพที่ครอบคลุมทุกระดับ ตั้งแต่วัยเด็กจนถึงผู้สูงอายุ</p>
    </div>
</section>

<?php
$section_config = [
    'critical'  => ['icon' => 'shield-alert', 'title' => 'ประกันโรคร้ายแรง', 'sub' => 'พร้อมรับมือโรคร้ายด้วยแผนประกันที่ครอบคลุม 48 โรคร้าย'],
    'cancer'    => ['icon' => 'activity', 'title' => 'ประกันมะเร็ง', 'sub' => 'พร้อมรับมือโรคมะเร็งทุกระยะ ตั้งแต่วินิจฉัยจนถึงรักษาหาย'],
    'nocopay'   => ['icon' => 'heart', 'title' => 'ประกันสุขภาพเหมาจ่าย', 'sub' => 'จ่ายค่ารักษาตามจริง ไม่ต้องสำรองจ่าย หลากหลายแผนจาก 750,000 ถึง 200 ล้านบาท'],
    'kids'      => ['icon' => 'baby', 'title' => 'ประกันสุขภาพเด็ก', 'sub' => 'อุ่นใจทั้งครอบครัว ดูแลสุขภาพลูกน้อยอย่างสมบูรณ์ ตั้งแต่แรกเกิด'],
    'senior50'  => ['icon' => 'users', 'title' => 'ประกันสุขภาพผู้สูงอายุ (50 ปีขึ้นไป)', 'sub' => 'ดูแลสุขภาพอย่างมั่นใจในทุกวัย แผนประกันที่ออกแบบสำหรับผู้สูงอายุ'],
    'additional'=> ['icon' => 'file-check-2', 'title' => 'ประกันสุขภาพเสริมสวัสดิการ', 'sub' => 'เสริมวงเงินรักษาพยาบาลให้อุ่นใจยิ่งขึ้น จาก 500,000 ถึง 60 ล้านบาท'],
    'income'    => ['icon' => 'dollar-sign', 'title' => 'ประกันชดเชยรายได้', 'sub' => 'ไม่ต้องกังวลเรื่องขาดรายได้เมื่อเจ็บป่วยนอนโรงพยาบาล'],
];
$bg_alt = false;
foreach ($sections as $key => $products):
    $cfg = $section_config[$key] ?? ['icon' => 'check-circle-2', 'title' => $key, 'sub' => ''];
    $bg_alt = !$bg_alt;
?>
<section id="<?= $key ?>" class="py-12 scroll-mt-20 <?= $bg_alt ? 'bg-brand-light' : '' ?>">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl font-bold text-brand-navy mb-1 flex items-center gap-2"><i data-lucide="<?= $cfg['icon'] ?>" class="w-6 h-6"></i> <?= $cfg['title'] ?></h2>
        <p class="text-brand-gray mb-8"><?= $cfg['sub'] ?></p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($products as $p): ?>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover-card flex flex-col">
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-navy bg-brand-light px-3 py-1 rounded-full"><?= $p['type'] ?? '' ?></span>
                    <?php if (!empty($p['coverage'])): ?>
                    <span class="text-[10px] font-bold text-white bg-brand-navy px-3 py-1 rounded-full"><?= $p['coverage'] ?></span>
                    <?php endif; ?>
                </div>
                <h3 class="text-lg font-bold text-brand-navy mb-3"><?= $p['name'] ?></h3>
                <?php if (!empty($p['plans'])): ?>
                <p class="text-xs text-brand-gray mb-2"><i data-lucide="layers" class="w-3 h-3 inline"></i> <?= $p['plans'] ?></p>
                <?php endif; ?>
                <?php if (!empty($p['room_rate'])): ?>
                <p class="text-xs text-brand-gray mb-2"><i data-lucide="bed" class="w-3 h-3 inline"></i> ค่าห้อง: <?= $p['room_rate'] ?></p>
                <?php endif; ?>
                <?php if (!empty($p['area'])): ?>
                <p class="text-xs text-brand-gray mb-2"><i data-lucide="globe" class="w-3 h-3 inline"></i> พื้นที่คุ้มครอง: <?= $p['area'] ?></p>
                <?php endif; ?>
                <ul class="space-y-1.5 text-sm text-brand-text mb-4 flex-1">
                    <?php foreach ($p['highlights'] as $h): ?>
                    <li class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-600 shrink-0 mt-0.5"></i> <?= $h ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?= !empty($p['id']) ? '/plan.php?id=' . (int)$p['id'] : '/contact.php' ?>" class="text-sm font-bold text-brand-navy hover:underline flex items-center gap-1 mt-auto pt-3 border-t border-gray-100">ดูรายละเอียด <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endforeach; ?>

<section class="py-12 bg-brand-navy text-white text-center">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">เลือกแผนประกันสุขภาพไม่ถูก? ให้เราช่วยคุณ!</h2>
        <p class="text-blue-200 mb-6">ให้คำปรึกษาฟรี! ไม่มีค่าใช้จ่าย พร้อมแนะนำแผนที่ใช่สำหรับคุณ</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-white text-brand-navy font-bold px-8 py-3 rounded-full hover:bg-gray-100 transition shadow-md">ปรึกษาฟรี</a>
            <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-brand-green text-white font-bold px-8 py-3 rounded-full hover:bg-brand-greenHover transition shadow-md"><img src="assets/icon/line.svg" class="w-5 h-5" alt="LINE"> แชทผ่าน LINE</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

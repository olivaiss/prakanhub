<?php
$data = json_decode(file_get_contents(__DIR__ . '/assets/data/insurance-data.json'), true);
$sections = $data['life'] ?? [];

// ═══ อ่านแผนประกันจากฐานข้อมูล (ตาราง products) — fallback: JSON ด้านบน ═══
try {
    if (function_exists('getDB')) {
        $__stmt = getDB()->query("SELECT title, desc_text, badge FROM products WHERE category = 'life' AND is_active = 1 ORDER BY sort_order, id");
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

<section class="bg-brand-navy text-white py-12 md:py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <nav class="text-sm text-blue-200 mb-3">
            <a href="/index.php" class="hover:text-white transition">หน้าแรก</a>
            <span class="mx-2">/</span>
            <span class="text-white">ประกันชีวิต</span>
        </nav>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-3">ประกันชีวิต</h1>
        <p class="text-blue-200 text-lg max-w-3xl">วางแผนอนาคตทางการเงิน มั่นใจทุกช่วงชีวิต ด้วยแผนประกันชีวิตที่หลากหลายจากอลิอันซ์ อยุธยา ครอบคลุมทุกความต้องการ ตั้งแต่ออมทรัพย์ บำนาญ ลดหย่อนภาษี ไปจนถึงส่งต่อมรดก</p>
    </div>
</section>

<?php
$section_config = [
    'saving'      => ['icon' => 'landmark',     'title' => 'ประกันออมทรัพย์',               'sub' => 'วางแผนการออม รับผลตอบแทนคุ้มค่า พร้อมความคุ้มครองชีวิต เลือกระยะเวลาได้ตามต้องการ'],
    'retirement'  => ['icon' => 'home',         'title' => 'ประกันบำนาญ / ประกันเกษียณ',    'sub' => 'วางแผนการเงินเพื่อวัยเกษียณอย่างมั่นคง รับเงินรายได้ระหว่างเกษียณ พร้อมลดหย่อนภาษี'],
    'tax'         => ['icon' => 'receipt',      'title' => 'ประกันลดหย่อนภาษี',             'sub' => 'วางแผนประกันพร้อมลดหย่อนภาษีตามเงื่อนไขกรมสรรพากร สูงสุดถึง 300,000 บาท'],
    'inheritance' => ['icon' => 'building',     'title' => 'ประกันเพื่อมรดก',               'sub' => 'ส่งต่อความมั่งคั่งให้คนที่คุณรักอย่างยั่งยืน ด้วยแผนประกันที่ให้มรดกก้อนใหญ่'],
    'unit-linked' => ['icon' => 'trending-up',  'title' => 'ประกันควบการลงทุน (ยูนิตลิงค์)',  'sub' => 'คุ้มครองชีวิต พร้อมโอกาสรับผลตอบแทนจากการลงทุนในกองทุนรวมชั้นนำ ปรับเปลี่ยนแผนการลงทุนได้'],
    'senior'      => ['icon' => 'users',        'title' => 'ประกันผู้สูงอายุ',               'sub' => 'อุ่นใจในวัยเกษียณด้วยความคุ้มครองที่ออกแบบมาสำหรับผู้สูงอายุ จ่ายเบี้ยคงที่'],
    'accident'    => ['icon' => 'footprints',   'title' => 'ประกันอุบัติเหตุ',               'sub' => 'คุ้มครอง 24 ชั่วโมง ทั่วโลก อุ่นใจทุกที่ทุกเวลา เบี้ยเริ่มต้นเพียงหลักพัน'],
];
// ═══ badge → หมวดในฟอร์ม (สำหรับปุ่ม "เลือกแผนนี้") ═══
$__formPlanMap = [
    'saving' => 'ประกันออมทรัพย์', 'tax' => 'ประกันออมทรัพย์', 'retirement' => 'ประกันบำนาญ',
    'inheritance' => 'ประกันชีวิต', 'unit-linked' => 'ประกันชีวิต', 'senior' => 'ประกันชีวิต',
    'accident' => 'ประกันอุบัติเหตุ',
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
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-navy bg-brand-light px-3 py-1 rounded-full"><?= htmlspecialchars($p['type'] ?? '') ?></span>
                </div>
                <h3 class="text-lg font-bold text-brand-navy mb-3"><?= htmlspecialchars($p['name']) ?></h3>
                <ul class="space-y-2 text-sm text-brand-text mb-4 flex-1">
                    <?php foreach ($p['highlights'] as $h): ?>
                    <li class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> <?= htmlspecialchars($h) ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="flex gap-2 mt-auto pt-3 border-t border-gray-100">
                    <a href="<?= !empty($p['id']) ? '/plan.php?id=' . (int)$p['id'] : '/contact.php' ?>" class="flex-1 text-center text-sm font-bold text-brand-navy border border-brand-navy hover:bg-brand-light rounded-lg py-2 transition">ดูรายละเอียด</a>
                    <a href="/form/?plan=<?= urlencode($__formPlanMap[$key] ?? '') ?>&plan_name=<?= urlencode($p['name']) ?>" class="flex-1 text-center text-sm font-bold text-white bg-brand-green hover:bg-brand-greenHover rounded-lg py-2 transition">เลือกแผนนี้</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endforeach; ?>

<section class="py-12 bg-brand-navy text-white text-center">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">สนใจแผนประกันชีวิต?</h2>
        <p class="text-blue-200 mb-6">ให้คำปรึกษาฟรี! ไม่มีค่าใช้จ่าย พร้อมแนะนำแผนที่ใช่สำหรับคุณ</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-white text-brand-navy font-bold px-8 py-3 rounded-full hover:bg-gray-100 transition shadow-md">ปรึกษาฟรี</a>
            <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-brand-green text-white font-bold px-8 py-3 rounded-full hover:bg-brand-greenHover transition shadow-md"><img src="assets/icon/line.svg" class="w-5 h-5" alt="LINE"> แชทผ่าน LINE</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

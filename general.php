<?php
$data = json_decode(file_get_contents(__DIR__ . '/assets/data/insurance-data.json'), true);
$sections = $data['general'] ?? [];
?>
<?php include 'includes/header.php'; ?>

<section class="bg-brand-navy text-white py-12">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <nav class="text-sm text-blue-200 mb-3">
            <a href="/index.php" class="hover:text-white transition">หน้าแรก</a><span class="mx-2">/</span>
            <span class="text-white">ประกันภัยทั่วไป</span>
        </nav>
        <h1 class="text-3xl md:text-4xl font-bold mb-3">ประกันภัยทั่วไป</h1>
        <p class="text-blue-200 text-lg max-w-3xl">คุ้มครองรถยนต์ บ้าน คอนโด เดินทางต่างประเทศ และกลุ่มองค์กร ครบจบที่เดียว</p>
    </div>
</section>

<?php
$section_config = [
    'motor'    => ['icon' => 'car', 'title' => 'ประกันรถยนต์', 'sub' => 'เลือกความคุ้มครองที่ใช่สำหรับคุณและรถยนต์คู่ใจ ตั้งแต่ชั้น 1 ถึงชั้น 3 คุ้มครองครอบคลุมทุกระดับ'],
    'property' => ['icon' => 'building', 'title' => 'ประกันอัคคีภัยบ้าน/คอนโด', 'sub' => 'ปกป้องบ้านและทรัพย์สินที่คุณรักจากอัคคีภัยและภัยธรรมชาติ'],
    'travel'   => ['icon' => 'plane', 'title' => 'ประกันเดินทางต่างประเทศ', 'sub' => 'มั่นใจทุกการเดินทาง ด้วยบริการช่วยเหลือ 24 ชม. จากทีมงานมืออาชีพ 45 ประเทศทั่วโลก'],
    'group'    => ['icon' => 'users', 'title' => 'ประกันกลุ่มสำหรับองค์กร', 'sub' => 'ดูแลพนักงานของคุณด้วยประกันกลุ่มที่ครอบคลุมทุกระดับ ปรับแผนตามงบประมาณ'],
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
                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-navy bg-brand-light px-3 py-1 rounded-full"><?= $p['type'] ?? '' ?></span>
                </div>
                <h3 class="text-lg font-bold text-brand-navy mb-3"><?= $p['name'] ?></h3>
                <ul class="space-y-1.5 text-sm text-brand-text mb-4 flex-1">
                    <?php foreach ($p['highlights'] as $h): ?>
                    <li class="flex items-start gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-green-600 shrink-0 mt-0.5"></i> <?= $h ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="/contact.php" class="text-sm font-bold text-brand-navy hover:underline flex items-center gap-1 mt-auto pt-3 border-t border-gray-100">รายละเอียดเพิ่มเติม <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endforeach; ?>

<section class="py-12 bg-brand-navy text-white text-center">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">ไม่แน่ใจเลือกอะไรดี? ให้คำปรึกษาฟรี!</h2>
        <p class="text-blue-200 mb-6">ฟรี! ไม่มีค่าใช้จ่าย พร้อมแนะนำแผนที่ใช่สำหรับคุณ</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-white text-brand-navy font-bold px-8 py-3 rounded-full hover:bg-gray-100 transition shadow-md">ปรึกษาฟรี</a>
            <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-brand-green text-white font-bold px-8 py-3 rounded-full hover:bg-brand-greenHover transition shadow-md"><img src="assets/icon/line.svg" class="w-5 h-5" alt="LINE"> แชทผ่าน LINE</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

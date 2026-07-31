<?php
$pageTitle = 'ขั้นตอนการเคลม';
include 'includes/header.php';
?>

<section class="bg-brand-navy text-white py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-3">ขั้นตอนการเคลม</h1>
        <p class="text-blue-200 text-lg">เราดูแลคุณตั้งแต่เริ่มต้นจนได้รับสินไหม</p>
    </div>
</section>

<?php
// ═══ เนื้อหาจากฐานข้อมูล (pages table) — fallback: hardcode ด้านล่าง ═══
$__pageContent = '';
try {
    if (function_exists('getDB')) {
        $__s = getDB()->prepare('SELECT content FROM pages WHERE slug = ? AND content IS NOT NULL AND content != "" LIMIT 1');
        $__s->execute(['claim']);
        $__pageContent = trim((string)$__s->fetchColumn());
    }
} catch (Throwable $e) {
    // DB ไม่พร้อม — ใช้ hardcode
}
?>

<?php if ($__pageContent !== ''): ?>
<section class="py-16">
    <div class="max-w-[900px] mx-auto px-4 md:px-8 prose-db">
        <?= $__pageContent ?>
    </div>
</section>
<?php else: ?>
<section class="py-16">
    <div class="max-w-[900px] mx-auto px-4 md:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold text-brand-navy mb-4">กระบวนการเคลมทีละขั้นตอน</h2>
            <p class="text-brand-text">เราเข้าใจว่าการทำเรื่องเคลมอาจเป็นเรื่องยุ่งยาก — เราเลยทำให้ง่าย</p>
        </div>

        <?php
        $steps = [
            ['icon' => 'phone', 'title' => '1. แจ้งเหตุ', 'desc' => 'เมื่อเกิดเหตุการณ์ที่ต้องเคลม ให้ติดต่อตัวแทนของคุณ (ประกันจริงใจ by ปกป้อง) ทันที หรือโทร 092-515-9991 เราจะแนะนำวิธีการดำเนินการที่ถูกต้อง'],
            ['icon' => 'file-text', 'title' => '2. รวบรวมเอกสาร', 'desc' => 'เราจะแจ้งรายการเอกสารที่ต้องใช้ตามประเภทการเคลม เช่น ใบรับรองแพทย์ ใบเสร็จรับเงิน บัตรประชาชน พร้อมให้คำแนะนำในการเตรียมเอกสาร'],
            ['icon' => 'upload', 'title' => '3. ส่งเอกสารให้เรา', 'desc' => 'ส่งเอกสารทั้งหมดให้ตัวแทนของคุณ เราจะตรวจสอบความถูกต้องและดำเนินการส่งให้บริษัทประกันต่อ โดยคุณไม่ต้องไปติดต่อเอง'],
            ['icon' => 'clock', 'title' => '4. ติดตามสถานะ', 'desc' => 'เราจะติดตามความคืบหน้าการเคลมให้คุณ และแจ้งอัปเดตสถานะเป็นระยะ จนกว่าจะได้รับผลการพิจารณา'],
            ['icon' => 'check-circle-2', 'title' => '5. รับผลการพิจารณา', 'desc' => 'บริษัทจะแจ้งผลการพิจารณาการเคลม เราจะแจ้งรายละเอียดและอธิบายให้คุณเข้าใจอย่างชัดเจน'],
            ['icon' => 'dollar-sign', 'title' => '6. รับเงินสินไหม', 'desc' => 'เมื่อเคลมอนุมัติ เงินสินไหมจะถูกโอนเข้าบัญชีของคุณตามระยะเวลาที่บริษัทกำหนด (โดยปกติภายใน 7-15 วันทำการ)'],
        ];
        foreach ($steps as $s):
        ?>
        <div class="flex items-start gap-5 mb-10 group">
            <div class="w-14 h-14 rounded-2xl bg-brand-light flex items-center justify-center shrink-0 text-brand-navy group-hover:bg-brand-navy group-hover:text-white transition">
                <i data-lucide="<?= $s['icon'] ?>" class="w-7 h-7"></i>
            </div>
            <div class="pt-2">
                <h3 class="font-bold text-brand-navy text-lg mb-1"><?= $s['title'] ?></h3>
                <p class="text-sm text-brand-text leading-relaxed"><?= $s['desc'] ?></p>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- Divider -->
        <div class="border-t border-gray-200 my-12"></div>

        <!-- Document Checklist -->
        <h2 class="text-2xl font-bold text-brand-navy mb-6">เอกสารที่ต้องใช้</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-brand-navy mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 text-sm font-bold">ก</span>
                    ประกันชีวิต
                </h3>
                <ul class="space-y-2 text-sm text-brand-text">
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> ใบมรณบัตร</li>
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> สูติบัตร (สำเนา)</li>
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> บัตรประชาชนผู้เอาประกัน</li>
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> บัตรประชาชนผู้รับผลประโยชน์</li>
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> ทรานสคริปต์การรักษา</li>
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> แบบคำขอรับสินไหม</li>
                </ul>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-brand-navy mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-sm font-bold">ข</span>
                    ประกันสุขภาพ
                </h3>
                <ul class="space-y-2 text-sm text-brand-text">
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> ใบรับรองแพทย์</li>
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> ใบเสร็จรับเงิน/ใบกำกับภาษี</li>
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> ใบสรุปค่าใช้จ่าย</li>
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> สำเนาบัตรประชาชน</li>
                    <li class="flex items-start gap-2"><i data-lucide="file" class="w-4 h-4 text-brand-navy shrink-0 mt-0.5"></i> แบบฟอร์มเคลมของบริษัท</li>
                </ul>
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-brand-light rounded-2xl p-6 border-l-4 border-green-500 mb-12">
            <h3 class="font-bold text-brand-navy mb-2">💡 เคล็ดลับเคลมไว</h3>
            <ul class="space-y-1 text-sm text-brand-text">
                <li>✅ แจ้งตัวแทนทันทีเมื่อเข้ารับการรักษาหรือเกิดเหตุ</li>
                <li>✅ ถ่ายรูปเอกสารทั้งหมดไว้เป็นหลักฐาน</li>
                <li>✅ ตรวจสอบเอกสารให้ครบถ้วนก่อนส่ง</li>
                <li>✅ สอบถามตัวแทนตลอดขั้นตอน — เราช่วยคุณได้</li>
            </ul>
        </div>

        <!-- CTA -->
        <div class="text-center bg-brand-navy rounded-3xl p-8">
            <h2 class="text-2xl font-bold text-white mb-3">ต้องการคำแนะนำเพิ่มเติม?</h2>
            <p class="text-blue-200 mb-6">ทีมงานของเราพร้อมช่วยเหลือคุณทุกขั้นตอน</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-brand-green hover:bg-brand-greenHover text-white font-bold px-8 py-3.5 rounded-full transition shadow-md">
                    <img src="/assets/icon/line.svg" class="w-5 h-5"> ปรึกษาผ่าน LINE
                </a>
                <a href="tel:092-515-9991" class="inline-flex items-center gap-2 bg-white text-brand-navy font-bold px-8 py-3.5 rounded-full hover:bg-gray-100 transition shadow-md">
                    <i data-lucide="phone" class="w-5 h-5"></i> โทร 092-515-9991
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

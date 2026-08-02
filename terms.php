<?php
$pageTitle = 'ข้อกำหนดและเงื่อนไข';
include 'includes/header.php';
?>

<section class="bg-brand-navy text-white py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-3">ข้อกำหนดและเงื่อนไข</h1>
        <p class="text-blue-200 text-lg">Terms of Service — ปรับปรุงล่าสุด 25 กรกฎาคม 2569</p>
    </div>
</section>

<?php
// ═══ เนื้อหาจากฐานข้อมูล (pages table) — fallback: hardcode ด้านล่าง ═══
$__pageContent = '';
$__forceFallback = $__forceFallback ?? false;
try {
    if (function_exists('getDB')) {
        $__s = getDB()->prepare('SELECT content FROM pages WHERE slug = ? AND content IS NOT NULL AND content != "" LIMIT 1');
        $__s->execute(['terms']);
                if (!$__forceFallback) $__pageContent = trim((string)$__s->fetchColumn());
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
<!-- BEGIN-CONTENT -->
<section class="py-16">
    <div class="max-w-[900px] mx-auto px-4 md:px-8 text-sm text-brand-text leading-relaxed space-y-8">

        <div class="bg-brand-light rounded-2xl p-6 border-l-4 border-brand-navy">
            <p class="font-medium">กรุณาอ่านข้อกำหนดและเงื่อนไขเหล่านี้อย่างละเอียดก่อนใช้บริการเว็บไซต์นี้ การเข้าถึงหรือใช้เว็บไซต์นี้ถือว่าคุณยอมรับข้อกำหนดและเงื่อนไขทั้งหมด</p>
        </div>

        <div>
            <h2 class="text-xl font-bold text-brand-navy mb-4">1. ข้อตกลง</h2>
            <p>การเข้าใช้เว็บไซต์ pokpong-insurance.com (ต่อไปจะเรียกว่า "เว็บไซต์") และบริการต่างๆ ที่นำเสนอผ่านเว็บไซต์นี้ อยู่ภายใต้ข้อกำหนดและเงื่อนไขการใช้บริการที่ระบุไว้ในหน้านี้</p>
        </div>

        <div>
            <h2 class="text-xl font-bold text-brand-navy mb-4">2. บริการให้คำปรึกษา</h2>
            <p>เราให้บริการให้คำปรึกษาเกี่ยวกับผลิตภัณฑ์ประกันภัยของบริษัท อลิอันซ์ อยุธยา ประกันชีวิต จำกัด (มหาชน) ซึ่งรวมถึงแต่ไม่จำกัดเพียง:</p>
            <ul class="list-disc pl-6 mt-3 space-y-2">
                <li>การวิเคราะห์ความต้องการและวางแผนการเงิน</li>
                <li>การแนะนำผลิตภัณฑ์ประกันชีวิต ประกันสุขภาพ และประกันภัยทั่วไป</li>
                <li>การเสนอราคาและเปรียบเทียบแผนประกัน</li>
                <li>การช่วยเหลือในการดำเนินการด้านเอกสารและการเคลม</li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-bold text-brand-navy mb-4">3. ข้อจำกัดความรับผิดชอบ</h2>
            <ul class="list-disc pl-6 mt-3 space-y-2">
                <li>ข้อมูลบนเว็บไซต์นี้มีวัตถุประสงค์เพื่อให้ข้อมูลเบื้องต้นเท่านั้น ไม่ถือเป็นคำแนะนำทางการเงินหรือการลงทุน</li>
                <li>การตัดสินใจซื้อผลิตภัณฑ์ประกันภัยขึ้นอยู่กับดุลยพินิจของผู้ขอเอาประกันภัย</li>
                <li>เงื่อนไข ความคุ้มครอง และรายละเอียดของผลิตภัณฑ์ประกันภัยเป็นไปตามที่ระบุในกรมธรรม์ของบริษัท อลิอันซ์ อยุธยา ประกันชีวิต จำกัด (มหาชน)</li>
                <li>เราไม่รับผิดชอบต่อความเสียหายใดๆ ที่เกิดจากการใช้ข้อมูลบนเว็บไซต์นี้</li>
                <li>เราขอสงวนสิทธิ์ในการเปลี่ยนแปลง แก้ไข หรือหยุดให้บริการโดยไม่ต้องแจ้งให้ทราบล่วงหน้า</li>
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-bold text-brand-navy mb-4">4. ทรัพย์สินทางปัญญา</h2>
            <p>เนื้อหา โลโก้ รูปภาพ และสื่อต่างๆ บนเว็บไซต์นี้ได้รับการคุ้มครองตามกฎหมายลิขสิทธิ์และทรัพย์สินทางปัญญา ห้ามทำซ้ำ ดัดแปลง เผยแพร่ หรือใช้ประโยชน์ในเชิงพาณิชย์โดยไม่ได้รับอนุญาตเป็นลายลักษณ์อักษร</p>
        </div>

        <div>
            <h2 class="text-xl font-bold text-brand-navy mb-4">5. การเชื่อมต่อไปยังเว็บไซต์อื่น</h2>
            <p>เว็บไซต์นี้อาจมีลิงก์ไปยังเว็บไซต์ของบุคคลที่สาม (เช่น LINE, Facebook, Google Maps) เราไม่รับผิดชอบต่อเนื้อหา นโยบายความเป็นส่วนตัว หรือแนวปฏิบัติของเว็บไซต์เหล่านั้น</p>
        </div>

        <div>
            <h2 class="text-xl font-bold text-brand-navy mb-4">6. การเปลี่ยนแปลงข้อกำหนด</h2>
            <p>เราขอสงวนสิทธิ์ในการปรับปรุงหรือเปลี่ยนแปลงข้อกำหนดและเงื่อนไขนี้ได้ทุกเมื่อ โดยจะแจ้งให้ทราบผ่านเว็บไซต์นี้</p>
        </div>

        <div>
            <h2 class="text-xl font-bold text-brand-navy mb-4">7. กฎหมายที่ใช้บังคับ</h2>
            <p>ข้อกำหนดและเงื่อนไขนี้อยู่ภายใต้และถูกตีความตามกฎหมายไทย</p>
        </div>

        <div class="text-center pt-6 border-t border-gray-200">
            <a href="/index.php" class="inline-flex items-center gap-2 text-brand-navy font-bold hover:underline">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับสู่หน้าแรก
            </a>
        </div>

    </div>
</section>
<!-- END-CONTENT -->
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

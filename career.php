<?php
$pageTitle = 'ร่วมงานกับเรา';

// ═══ เนื้อหาจากฐานข้อมูล (pages table) — fallback: hardcode ด้านล่าง ═══
$__pageContent = '';
$__forceFallback = $__forceFallback ?? false;
try {
    require_once __DIR__ . '/includes/db.php';
    if (function_exists('getDB')) {
        $__s = getDB()->prepare('SELECT content FROM pages WHERE slug = ? AND content IS NOT NULL AND content != "" LIMIT 1');
        $__s->execute(['career']);
                if (!$__forceFallback) $__pageContent = trim((string)$__s->fetchColumn());
    }
} catch (Throwable $e) { /* fallback */ }

// ═══ เก็บผู้สมัครตัวแทนลงฐานข้อมูล ═══
$applied = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aName = trim($_POST['name'] ?? '');
    $aPhone = trim($_POST['phone'] ?? '');
    $aAge = trim($_POST['age'] ?? '');
    $aEdu = trim($_POST['education'] ?? '');
    $aExp = trim($_POST['experience'] ?? '');
    $aLine = trim($_POST['line'] ?? '');
    if ($aName !== '' && $aPhone !== '') {
        try {
            require_once __DIR__ . '/includes/db.php';
            $__ins = getDB()->prepare('INSERT INTO agent_applications (name, phone, age, education, experience, line) VALUES (?,?,?,?,?,?)');
            $__ins->execute([$aName, $aPhone, $aAge, $aEdu, $aExp, $aLine]);
            $applied = true;
        } catch (Throwable $e) {
            // DB ไม่พร้อม — ไม่บล็อกการใช้งานฟอร์ม
        }
    }
}

include 'includes/header.php';
?>

<section class="bg-brand-navy text-white py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-3">ร่วมงานกับเรา</h1>
        <p class="text-blue-200 text-lg max-w-3xl mx-auto">เติบโตไปพร้อมกัน กับทีมคุณภาพ สร้างรายได้ ไม่จำกัด</p>
    </div>
</section>

<!-- Why Join Us -->
<?php if ($__pageContent !== ''): ?>
<section class="py-16">
    <div class="max-w-[1000px] mx-auto px-4 md:px-8 prose-db"><?= $__pageContent ?></div>
</section>
<?php else: ?>
<!-- BEGIN-CONTENT -->
<section class="py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-brand-navy text-center mb-12">ทำไมต้องร่วมงานกับเรา</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $benefits = [
                ['icon' => 'trending-up', 'title' => 'รายได้ดี ไม่มีเพดาน', 'desc' => 'ค่าคอมมิชชั่นสูงที่สุดในอุตสาหกรรม พร้อมโบนัสและ Incentive อีกมากมาย ยิ่งขายได้มาก ยิ่งรายได้เพิ่ม'],
                ['icon' => 'book-open', 'title' => 'อบรมฟรี โดยมืออาชีพ', 'desc' => 'หลักสูตรอบรมครบวงจร จากทีมวิทยากรมืออาชีพ ทั้งความรู้ผลิตภัณฑ์ เทคนิคการขาย และการพัฒนาตนเอง'],
                ['icon' => 'laptop', 'title' => 'ระบบดูแลครบวงจร', 'desc' => 'ระบบบริหารจัดการตัวแทนที่ทันสมัย พร้อมทีมงาน support ตลอด 24 ชม. ช่วยให้คุณทำงานได้อย่างมีประสิทธิภาพ'],
                ['icon' => 'plane', 'title' => 'โบนัสทริปและรางวัล', 'desc' => 'ร่วมทริปต่างประเทศประจำปี พร้อมรางวัลมากมายสำหรับตัวแทนที่ทำผลงานได้ดี ทั้งเงินสดและของรางวัล'],
            ];
            foreach ($benefits as $b):
            ?>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover-card text-center group">
                <div class="w-14 h-14 rounded-full bg-brand-light flex items-center justify-center mx-auto mb-4 text-brand-navy group-hover:bg-brand-navy group-hover:text-white transition">
                    <i data-lucide="<?= $b['icon'] ?>" class="w-7 h-7"></i>
                </div>
                <h3 class="font-bold text-brand-navy mb-2"><?= $b['title'] ?></h3>
                <p class="text-sm text-brand-text leading-relaxed"><?= $b['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Requirements -->
<section class="py-16 bg-brand-light">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-brand-navy mb-6">คุณสมบัติผู้สมัคร</h2>
                <ul class="space-y-4">
                    <?php
                    $reqs = [
                        'อายุ 20 ปีขึ้นไป',
                        'วุฒิการศึกษามัธยมศึกษาตอนปลายหรือเทียบเท่าขึ้นไป',
                        'มีมนุษย์สัมพันธ์ดี รักงานบริการ',
                        'มีความรับผิดชอบ ขยัน อดทน',
                        'มีรถยนต์และใบขับขี่ (จะพิจารณาเป็นพิเศษ)',
                        'ไม่มีประวัติอาชญากรรม',
                        'ผ่านการอบรมและสอบใบอนุญาตตัวแทนประกันชีวิต (บริษัทจัดอบรมให้)',
                    ];
                    foreach ($reqs as $r):
                    ?>
                    <li class="flex items-start gap-3">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-green-600 shrink-0 mt-0.5"></i>
                        <span class="text-brand-text"><?= $r ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bg-white rounded-3xl p-8 shadow-md border border-gray-100">
                <h3 class="text-xl font-bold text-brand-navy mb-6">สมัครเลยวันนี้</h3>
                <form method="POST" action="" class="space-y-4">
                    <?php if ($applied): ?>
                    <div class="bg-brand-green/10 border border-brand-green text-brand-green rounded-xl px-4 py-3 text-sm font-medium">✅ ส่งใบสมัครเรียบร้อย — ทีมงานจะติดต่อกลับโดยเร็วที่สุด</div>
                    <?php endif; ?>
                    <div>
                        <label class="block text-sm font-medium text-brand-text mb-1">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-navy focus:border-transparent outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-text mb-1">เบอร์โทรศัพท์ <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-navy focus:border-transparent outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-text mb-1">LINE ID</label>
                        <input type="text" name="line" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-navy focus:border-transparent outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-text mb-1">อายุ</label>
                        <input type="number" name="age" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-navy focus:border-transparent outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-text mb-1">วุฒิการศึกษา</label>
                        <select name="education" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-navy focus:border-transparent outline-none transition bg-white">
                            <option>มัธยมศึกษาตอนปลาย</option>
                            <option>ปวช./ปวส.</option>
                            <option>ปริญญาตรี</option>
                            <option>ปริญญาโทขึ้นไป</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-text mb-1">ประสบการณ์ขายประกัน</label>
                        <select name="experience" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-navy focus:border-transparent outline-none transition bg-white">
                            <option>ไม่มีประสบการณ์</option>
                            <option>น้อยกว่า 1 ปี</option>
                            <option>1-3 ปี</option>
                            <option>3-5 ปี</option>
                            <option>มากกว่า 5 ปี</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-brand-navy hover:bg-brand-navyHover text-white font-bold py-3.5 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-5 h-5"></i> ส่งใบสมัคร
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- END-CONTENT -->
<?php endif; ?>

<!-- Testimonials from team -->
<section class="py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-brand-navy text-center mb-12">เสียงจากทีมงานของเรา</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php
            $team = [
                ['name' => 'คุณสมชาย ใจดี', 'role' => 'ตัวแทนอาวุโส 5 ปี', 'text' => 'ร่วมงานที่นี่มา 5 ปี รายได้ดีมาก ระบบ support ดีที่สุดเท่าที่เคยทำงานมา มีอบรมตลอด ทำให้เก่งขึ้นทุกวัน'],
                ['name' => 'คุณสาวิกา รักงาน', 'role' => 'ตัวแทนใหม่ (6 เดือน)', 'text' => 'เพิ่งเริ่มต้นแต่ไม่ต้องกลัว เพราะมีพี่เลี้ยงคอยสอนงานตลอด รายได้เดือนแรกก็เกิน 50,000 บาท'],
                ['name' => 'คุณอนุชา มั่นคง', 'role' => 'หัวหน้าทีม', 'text' => 'การมีทีมที่ดีทำให้ทุกอย่างง่ายขึ้น เราสร้างระบบที่ช่วยให้ทุกคนในทีมประสบความสำเร็จไปด้วยกัน'],
            ];
            foreach ($team as $t):
            ?>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover-card">
                <div class="flex items-center gap-1 text-yellow-400 mb-3">
                    <?php for ($i=0; $i<5; $i++): ?><i data-lucide="star" class="w-4 h-4 fill-current"></i><?php endfor; ?>
                </div>
                <p class="text-sm text-brand-text mb-4 italic">"<?= $t['text'] ?>"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-brand-light flex items-center justify-center text-brand-navy font-bold text-sm"><?= mb_substr($t['name'], 0, 1) ?></div>
                    <div><div class="font-bold text-sm text-brand-navy"><?= $t['name'] ?></div><div class="text-xs text-brand-gray"><?= $t['role'] ?></div></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-brand-navy text-white text-center">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">พร้อมเริ่มต้นเส้นทางใหม่กับเรา?</h2>
        <p class="text-blue-200 mb-8">ร่วมทีมกับประกันจริงใจ by ปกป้อง — ตัวแทน Allianz Ayudhya</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-brand-green hover:bg-brand-greenHover text-white font-bold px-8 py-3.5 rounded-full transition shadow-md">
                <img src="/assets/icon/line.svg" class="w-5 h-5"> สมัครผ่าน LINE
            </a>
            <a href="tel:092-515-9991" class="inline-flex items-center gap-2 bg-white text-brand-navy font-bold px-8 py-3.5 rounded-full hover:bg-gray-100 transition shadow-md">
                <i data-lucide="phone" class="w-5 h-5"></i> โทร 092-515-9991
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<?php
$pageTitle = 'รีวิวจากลูกค้า';
include 'includes/header.php';
?>

<section class="bg-brand-navy text-white py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-3">เสียงจากลูกค้า</h1>
        <p class="text-blue-200 text-lg max-w-2xl mx-auto">ความไว้วางใจที่เราภูมิใจนำเสนอ</p>
    </div>
</section>

<!-- Statistics Bar -->
<section class="border-b border-brand-light/50">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl font-bold text-brand-navy">1,000+</div>
                <div class="text-xs text-brand-gray mt-1">ครอบครัวที่ไว้วางใจ</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-brand-navy">98%</div>
                <div class="text-xs text-brand-gray mt-1">ความพึงพอใจลูกค้า</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-brand-navy">4.9⭐</div>
                <div class="text-xs text-brand-gray mt-1">คะแนนรีวิวเฉลี่ย</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-brand-navy">15+</div>
                <div class="text-xs text-brand-gray mt-1">ปีที่ให้บริการ</div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Grid -->
<section class="py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $testimonials = [
                ['name' => 'คุณธนธัช', 'tag' => 'ประกันชีวิต', 'rating' => 5, 'text' => 'ขอบคุณคุณป้องมากครับ ที่ช่วยแนะนำแผนประกันชีวิตที่เหมาะกับเราจริงๆ หลังจากคุยกันคุณป้องเข้าใจความต้องการของเรามาก แนะนำแผนที่คุ้มค่าและตรงกับที่เราต้องการ ไม่ได้ขายแบบยัดเยียดเลย ไว้ต่ออายุจะมาหาคุณป้องอีก'],
                ['name' => 'คุณพรรณนิภา', 'tag' => 'ประกันสุขภาพ', 'rating' => 5, 'text' => 'ประทับใจมากค่ะ คุณป้องดูแลอย่างดีตั้งแต่แรกจนถึงตอนเคลม เคยเคลมประกันสุขภาพไปรอบนึง คุณป้องช่วยดำเนินการให้ทุกอย่าง เคลมไวมาก ได้เงินเร็ว ไม่ต้องปวดหัวกับเอกสารเลย แนะนำให้เพื่อนๆ มาหาคุณป้องกันหลายคนแล้ว'],
                ['name' => 'คุณวีรัตน์', 'tag' => 'ประกันชีวิต', 'rating' => 5, 'text' => 'ไว้ใจคุณป้องมาตลอด 10 ปี ตั้งแต่ทำประกันชีวิตกับคุณป้องตอนแรกก็รู้สึกดีที่ได้รับคำแนะนำที่ดี คุณป้องไม่เคยทิ้ง ดูแลต่อเนื่องทุกปี มีอะไรอัปเดตก็แจ้งตลอด ผมแนะนำให้คนในครอบครัวมาทำกับคุณป้องทุกคน'],
                ['name' => 'คุณณัฐวุฒิ', 'tag' => 'ประกันอุบัติเหตุ', 'rating' => 5, 'text' => 'คุณป้องเป็นกันเองมาก ให้คำปรึกษาดีมาก ไม่ได้ขายอย่างเดียว แต่ให้ความรู้เกี่ยวกับการวางแผนการเงินด้วย ทำให้เราเข้าใจมากขึ้นว่าทำไมต้องมีประกันแต่ละแบบ สุดยอดครับ'],
                ['name' => 'คุณกัญญา', 'tag' => 'ประกันสุขภาพเด็ก', 'rating' => 5, 'text' => 'ตอนแรกกังวลเรื่องทำประกันให้ลูกมาก ไม่รู้ว่าจะเลือกแบบไหน พี่ป้องแนะนำละเอียดมาก ทั้งข้อดีข้อเสียของแต่ละแผน ทำให้ตัดสินใจได้ง่ายขึ้น ตอนนี้ลูกสบายใจ อุ่นใจที่มีประกันให้ลูก'],
                ['name' => 'คุณสมชาย', 'tag' => 'ประกันบำนาญ', 'rating' => 5, 'text' => 'วางแผนเกษียณกับพี่ป้อง รู้สึกมั่นใจมากขึ้น พี่ป้องคำนวณให้เห็นเป็นรูปธรรมว่าเราต้องมีเงินเท่าไหร่ถึงจะเกษียณอย่างสบาย และเลือกแผนประกันที่ช่วยให้เราไปถึงเป้าหมายนั้นได้'],
                ['name' => 'คุณรุ่งนภา', 'tag' => 'ประกันโรคร้ายแรง', 'rating' => 5, 'text' => 'พอมีคนในครอบครัวเป็นมะเร็ง ทำให้เห็นความสำคัญของประกันโรคร้ายแรง คุณป้องช่วยเลือกแผนที่คุ้มครองครอบคลุมมากที่สุดในราคาที่จ่ายไหว ขอบคุณมากค่ะ'],
                ['name' => 'คุณอภิชาติ', 'tag' => 'ประกันกลุ่มองค์กร', 'rating' => 5, 'text' => 'บริษัทผมทำประกันกลุ่มกับคุณป้อง บริการดีมาก พนักงานในบริษัทพอใจกันทุกคน คุณป้องดูแลตั้งแต่การเสนอแผนจนถึงการเคลม ไว้วางใจได้ 100%'],
                ['name' => 'คุณดาริกา', 'tag' => 'ประกันเดินทาง', 'rating' => 5, 'text' => 'ก่อนไปเที่ยวต่างประเทศพี่ป้องแนะนำให้ทำประกันเดินทาง ตอนแรกคิดว่าไม่จำเป็น แต่ดันเจ็บป่วยระหว่างทริป โทรหาพี่ป้อง พี่ป้องจัดการให้ทุกอย่าง คุ้มค่ามากที่ทำไว้'],
                ['name' => 'คุณธนากร', 'tag' => 'ประกันชีวิต', 'rating' => 5, 'text' => 'พี่ป้องเป็นมากกว่าตัวแทนประกัน คือที่ปรึกษาทางการเงินที่ไว้ใจได้ ปรึกษาได้ทุกเรื่อง วางแผนการเงินให้ครอบคลุมทั้งประกัน การออม และการลงทุน ขอบคุณครับ'],
                ['name' => 'คุณสุภาพร', 'tag' => 'ประกันสุขภาพ', 'rating' => 5, 'text' => 'เคลมประกันสุขภาพรอบที่แล้วรู้สึกว่าง่ายมาก แค่ส่งเอกสารให้คุณป้อง ที่เหลือคุณป้องดำเนินการให้หมด เงินเข้าบัญชีไวมาก ประทับใจบริการหลังการขาย'],
                ['name' => 'คุณมานพ', 'tag' => 'ประกันชีวิต', 'rating' => 5, 'text' => 'ซื้อประกันชีวิตกับคุณป้องมา 5 ปีแล้ว ไม่เคยมีปัญหาอะไร คุณป้องโทรมาอัปเดตข่าวสารและดูแลเราตลอด ทำให้รู้สึกอุ่นใจที่มีตัวแทนที่ดีแบบนี้'],
            ];

            // ═══ อ่านรีวิวจากฐานข้อมูล (ถ้ามีข้อมูล) — fallback ใช้ array ด้านบน ═══
            try {
                if (function_exists('getDB')) {
                    $__dbTesti = getDB()->query('SELECT name, role, rating, message, img FROM testimonials WHERE is_active = 1 ORDER BY id DESC');
                    $__dbRows = $__dbTesti->fetchAll();
                    if (count($__dbRows) > 0) {
                        $testimonials = [];
                        foreach ($__dbRows as $__r) {
                            $testimonials[] = [
                                'name' => $__r['name'],
                                'tag' => $__r['role'] ?: 'ลูกค้า',
                                'rating' => max(1, min(5, (int)$__r['rating'])),
                                'text' => $__r['message'] ?: '',
                                'img' => $__r['img'] ?: '',
                            ];
                        }
                    }
                }
            } catch (Throwable $e) {
                // DB ไม่พร้อม — ใช้ array เดิม
            }

            $perPage = 6;
            $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $totalItems = count($testimonials);
            $totalPages = max(1, (int)ceil($totalItems / $perPage));
            $currentPage = min($currentPage, $totalPages);
            $offset = ($currentPage - 1) * $perPage;
            $pageItems = array_slice($testimonials, $offset, $perPage);

            foreach ($pageItems as $t):
            ?>
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover-card flex flex-col">
                <div class="flex items-center gap-1 text-yellow-400 mb-2">
                    <?php for ($i=0; $i<$t['rating']; $i++): ?><i data-lucide="star" class="w-4 h-4 fill-current"></i><?php endfor; ?>
                </div>
                <span class="text-[10px] font-bold text-brand-navy bg-brand-light px-3 py-1 rounded-full w-fit mb-3"><?= $t['tag'] ?></span>
                <p class="text-sm text-brand-text leading-relaxed flex-1">"<?= $t['text'] ?>"</p>
                <div class="flex items-center gap-3 mt-4 pt-3 border-t border-gray-100">
                    <?php if (!empty($t['img'] ?? '')): ?>
                    <img src="<?= htmlspecialchars($t['img']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm" loading="lazy" onerror="this.style.display='none'">
                    <?php else: ?>
                    <div class="w-9 h-9 rounded-full bg-brand-navy flex items-center justify-center text-white font-bold text-sm"><?= mb_substr($t['name'], 1, 1) ?></div>
                    <?php endif; ?>
                    <div class="font-bold text-sm text-brand-navy"><?= $t['name'] ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-center mt-10 gap-2">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $currentPage): ?>
                    <span class="w-10 h-10 rounded-xl bg-brand-navy text-white font-bold text-sm inline-flex items-center justify-center"><?= $i ?></span>
                <?php else: ?>
                    <a href="?page=<?= $i ?>" class="w-10 h-10 rounded-xl border border-gray-200 text-brand-text font-bold text-sm inline-flex items-center justify-center hover:bg-brand-light transition"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-brand-navy text-white text-center">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">ร่วมเป็นครอบครัวผู้ประกันใจกับเรา</h2>
        <p class="text-blue-200 mb-8">ปรึกษาฟรี! ไม่มีค่าใช้จ่าย พร้อมแนะนำแผนที่ใช่สำหรับคุณ</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-brand-green hover:bg-brand-greenHover text-white font-bold px-8 py-3.5 rounded-full transition shadow-md">
                <img src="/assets/icon/line.svg" class="w-5 h-5"> ปรึกษาฟรีผ่าน LINE
            </a>
            <a href="tel:092-515-9991" class="inline-flex items-center gap-2 bg-white text-brand-navy font-bold px-8 py-3.5 rounded-full hover:bg-gray-100 transition shadow-md">
                <i data-lucide="phone" class="w-5 h-5"></i> โทร 092-515-9991
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

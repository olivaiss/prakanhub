<?php
require_once __DIR__ . '/config.php';
member_guard();

$courses = member_courses();
$pageTitle = 'คอร์สเรียนสมาชิก';

// ═══ ความคืบหน้าการเรียนของสมาชิก (จาก DB) ═══
$__progress = [];
try {
    if (function_exists('getDB')) {
        $__stmt = getDB()->prepare('SELECT course_id, COUNT(*) AS done FROM member_progress WHERE member_code = ? AND done = 1 GROUP BY course_id');
        $__stmt->execute([$_SESSION['member_code'] ?? '']);
        foreach ($__stmt->fetchAll() as $__r) {
            $__progress[(int)$__r['course_id']] = (int)$__r['done'];
        }
    }
} catch (Throwable $e) {
    // DB ไม่พร้อม — ไม่แสดง progress
}

include __DIR__ . '/../includes/header.php';

// นับบทเรียนรวม + เวลารวม
function course_stats(array $c): array {
    $lessons = 0;
    $mins = 0;
    foreach ($c['sections'] as $s) {
        foreach ($s['lessons'] as $l) {
            $lessons++;
            $parts = explode(':', $l['duration']);
            $mins += (int)$parts[0] + ((int)($parts[1] ?? 0) / 60); // m:ss → นาที
        }
    }
    return [$lessons, round($mins / 60, 1)];
}
?>

<!-- HERO -->
<section class="bg-brand-navy text-white">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-10 md:py-14">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 bg-white/10 text-blue-200 text-xs font-bold px-3 py-1.5 rounded-full mb-3">
                    <i data-lucide="graduation-cap" class="w-4 h-4"></i> ระบบเรียนรู้สมาชิก
                </div>
                <h1 class="text-2xl md:text-4xl font-bold">ยินดีต้อนรับสมาชิก 👋</h1>
                <p class="text-blue-200 mt-2 max-w-xl">เลือกคอร์สที่สนใจแล้วเริ่มเรียนได้เลย — ความคืบหน้าของคุณจะถูกบันทึกอัตโนมัติ</p>
            </div>
            <a href="logout.php" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition self-start md:self-auto">
                <i data-lucide="log-out" class="w-4 h-4"></i> ออกจากระบบ
            </a>
        </div>
    </div>
</section>

<!-- COURSE GRID -->
<section class="py-12 md:py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl md:text-2xl font-bold text-brand-navy">คอร์สทั้งหมด <span class="text-brand-gray text-sm font-medium">(<?= count($courses) ?> คอร์ส)</span></h2>
        </div>

        <?php if (empty($courses)): ?>
            <div class="text-center py-20 text-brand-gray">
                <i data-lucide="folder-open" class="w-12 h-12 mx-auto mb-4 opacity-40"></i>
                <p>ยังไม่มีคอร์สในระบบ</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($courses as $c):
                    [$lessonCount, $hours] = course_stats($c);
                    $doneCount = $__progress[(int)$c['id']] ?? 0;
                    $donePct = $lessonCount > 0 ? (int)round($doneCount / $lessonCount * 100) : 0;
                    $catColors = [
                        'ความรู้พื้นฐาน' => 'bg-sky-50 text-sky-700 border-sky-200',
                        'การเงิน'       => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'ทักษะการขาย'  => 'bg-amber-50 text-amber-700 border-amber-200',
                        'ผลิตภัณฑ์'     => 'bg-violet-50 text-violet-700 border-violet-200',
                    ];
                    $catCls = $catColors[$c['category']] ?? 'bg-brand-light text-brand-navy border-brand-navy/15';
                ?>
                <a href="course.php?id=<?= (int)$c['id'] ?>" class="group bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:border-brand-navy/30 transition">
                    <div class="relative aspect-video bg-brand-light overflow-hidden">
                        <?php if (!empty($c['thumb'])): ?>
                            <img src="<?= htmlspecialchars($c['thumb']) ?>" alt="<?= htmlspecialchars($c['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center"><i data-lucide="play-circle" class="w-14 h-14 text-brand-navy/30"></i></div>
                        <?php endif; ?>
                        <span class="absolute top-3 left-3 text-[10px] font-bold px-2.5 py-1 rounded-full border <?= $catCls ?>"><?= htmlspecialchars($c['category']) ?></span>
                        <?php if ($donePct > 0): ?>
                        <span class="absolute top-3 right-3 bg-brand-green text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow">เรียนแล้ว <?= $donePct ?>%</span>
                        <?php endif; ?>
                        <span class="absolute bottom-3 right-3 bg-black/60 text-white text-[10px] font-bold px-2.5 py-1 rounded-full flex items-center gap-1"><i data-lucide="play" class="w-3 h-3"></i> <?= $lessonCount ?> บทเรียน</span>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-brand-text group-hover:text-brand-navy transition leading-snug"><?= htmlspecialchars($c['title']) ?></h3>
                        <p class="text-xs text-brand-gray mt-2 leading-relaxed line-clamp-2"><?= htmlspecialchars($c['desc']) ?></p>
                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                            <span class="text-[11px] text-brand-gray flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> ประมาณ <?= $hours ?> ชม.</span>
                            <span class="text-xs font-bold text-brand-navy inline-flex items-center gap-1 group-hover:gap-2 transition-all">เริ่มเรียน <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>

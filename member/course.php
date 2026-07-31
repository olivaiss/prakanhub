<?php
require_once __DIR__ . '/config.php';
member_guard();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$course = member_course($id);
if (!$course) {
    header('Location: /member/home.php');
    exit;
}

// flatten lessons: [ ['section'=>..,'lesson'=>..,'idx'=>..], ... ]
$flat = [];
foreach ($course['sections'] as $si => $s) {
    foreach ($s['lessons'] as $li => $l) {
        $flat[] = ['section' => $s['name'], 'lesson' => $l, 'idx' => count($flat), 'si' => $si, 'li' => $li];
    }
}
$total = count($flat);
$curIdx = isset($_GET['lesson']) ? max(0, min($total - 1, (int)$_GET['lesson'])) : 0;
$cur = $flat[$curIdx];
$prevIdx = $curIdx > 0 ? $curIdx - 1 : null;
$nextIdx = $curIdx < $total - 1 ? $curIdx + 1 : null;

$pageTitle = $course['title'] . ' — ระบบเรียนรู้สมาชิก';
include __DIR__ . '/../includes/header.php';
?>

<style>
    .member-layout { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
    @media (min-width: 1024px) { .member-layout { grid-template-columns: 1fr 360px; } }
    .video-frame { aspect-ratio: 16/9; background: #000; border-radius: 1rem; overflow: hidden; }
    .video-frame iframe { width: 100%; height: 100%; border: 0; display: block; }
    .lesson-item { display: flex; align-items: center; gap: .75rem; width: 100%; text-align: left; padding: .65rem .85rem; border-radius: .75rem; font-size: .82rem; color: #334155; transition: background .15s; }
    .lesson-item:hover { background: #EEF4F9; }
    .lesson-item.active { background: #003781; color: #fff; font-weight: 600; }
    .lesson-item.done:not(.active) { color: #64748B; }
    .lesson-item.done:not(.active) .ls-num { background: #00C300; color: #fff; border-color: #00C300; }
    .ls-num { width: 24px; height: 24px; border-radius: 9999px; border: 1.5px solid #CBD5E1; display: flex; align-items: center; justify-content: center; font-size: .68rem; font-weight: 700; color: #94A3B8; flex-shrink: 0; }
    .lesson-item.active .ls-num { border-color: #fff; color: #fff; }
    .progress-track { height: 6px; background: #E2E8F0; border-radius: 9999px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #003781, #00C300); border-radius: 9999px; transition: width .4s; }
</style>

<!-- HEADER BAR -->
<section class="bg-brand-navy text-white">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3 min-w-0">
                <a href="home.php" class="shrink-0 w-9 h-9 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition" title="กลับหน้ารายการคอร์ส">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div class="min-w-0">
                    <div class="text-[10px] text-blue-300 font-bold uppercase tracking-wide"><?= htmlspecialchars($course['category']) ?></div>
                    <h1 class="font-bold truncate"><?= htmlspecialchars($course['title']) ?></h1>
                </div>
            </div>
            <a href="logout.php" class="inline-flex items-center gap-1.5 text-xs font-bold bg-white/10 hover:bg-white/20 border border-white/20 px-4 py-2 rounded-xl transition">
                <i data-lucide="log-out" class="w-3.5 h-3.5"></i> ออกจากระบบ
            </a>
        </div>
    </div>
</section>

<section class="py-6 md:py-8">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="member-layout">

            <!-- ══════ LEFT: VIDEO PLAYER ══════ -->
            <div class="min-w-0">
                <div class="video-frame shadow-lg">
                    <iframe id="player" src="https://www.youtube.com/embed/<?= htmlspecialchars($cur['lesson']['video']) ?>?autoplay=1&rel=0" title="<?= htmlspecialchars($cur['lesson']['title']) ?>" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-6 mt-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="text-[10px] font-bold text-brand-gray uppercase tracking-wide"><?= htmlspecialchars($cur['section']) ?></div>
                            <h2 class="text-lg md:text-xl font-bold text-brand-text mt-1"><?= htmlspecialchars($cur['lesson']['title']) ?></h2>
                        </div>
                        <span class="text-xs font-bold text-brand-gray bg-brand-light px-3 py-1.5 rounded-full flex items-center gap-1.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i> <?= htmlspecialchars($cur['lesson']['duration']) ?>
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 mt-5 pt-5 border-t border-gray-100">
                        <!-- เรียนจบแล้ว -->
                        <button id="done-btn" onclick="toggleDone()" class="inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl border-2 transition">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i> <span id="done-label">ทำเครื่องหมายเรียนจบ</span>
                        </button>

                        <div class="flex-1"></div>

                        <?php if ($prevIdx !== null): ?>
                            <a href="?id=<?= $id ?>&lesson=<?= $prevIdx ?>" class="inline-flex items-center gap-2 text-sm font-bold text-brand-navy bg-brand-light hover:bg-brand-navy hover:text-white px-4 py-2.5 rounded-xl transition">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i> บทก่อนหน้า
                            </a>
                        <?php endif; ?>
                        <?php if ($nextIdx !== null): ?>
                            <a href="?id=<?= $id ?>&lesson=<?= $nextIdx ?>" class="inline-flex items-center gap-2 text-sm font-bold text-white bg-brand-navy hover:bg-brand-navyHover px-4 py-2.5 rounded-xl transition">
                                บทถัดไป <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        <?php else: ?>
                            <a href="home.php" class="inline-flex items-center gap-2 text-sm font-bold text-white bg-brand-green hover:bg-brand-greenHover px-4 py-2.5 rounded-xl transition">
                                <i data-lucide="party-popper" class="w-4 h-4"></i> เรียนครบทุกบทแล้ว!
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Progress -->
                <div class="bg-white border border-gray-200 rounded-2xl p-5 mt-5 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-brand-text">ความคืบหน้าการเรียน</span>
                        <span class="text-xs font-bold text-brand-navy" id="pct-label">0%</span>
                    </div>
                    <div class="progress-track"><div class="progress-fill" id="pct-fill" style="width:0%"></div></div>
                </div>
            </div>

            <!-- ══════ RIGHT: LESSON LIST ══════ -->
            <aside class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm h-fit lg:sticky lg:top-6">
                <div class="flex items-center justify-between px-1 pb-3 border-b border-gray-100 mb-3">
                    <h3 class="font-bold text-brand-text text-sm">เนื้อหาคอร์ส</h3>
                    <span class="text-[11px] font-bold text-brand-gray bg-brand-light px-2.5 py-1 rounded-full"><?= $total ?> บท</span>
                </div>

                <?php foreach ($course['sections'] as $si => $s): ?>
                    <div class="mb-1">
                        <div class="px-1 py-2 text-[10px] font-bold uppercase tracking-wide text-brand-gray"><?= htmlspecialchars($s['name']) ?></div>
                        <div class="space-y-0.5">
                            <?php foreach ($s['lessons'] as $li => $l):
                                $flatIdx = 0;
                                foreach ($flat as $f) { if ($f['si'] === $si && $f['li'] === $li) { $flatIdx = $f['idx']; break; } }
                                $isActive = $flatIdx === $curIdx;
                            ?>
                            <a href="?id=<?= $id ?>&lesson=<?= $flatIdx ?>" class="lesson-item <?= $isActive ? 'active' : '' ?>" data-idx="<?= $flatIdx ?>">
                                <span class="ls-num"><?= $flatIdx + 1 ?></span>
                                <span class="flex-1 min-w-0">
                                    <span class="block truncate"><?= htmlspecialchars($l['title']) ?></span>
                                    <span class="block text-[10px] opacity-70"><?= htmlspecialchars($l['duration']) ?></span>
                                </span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </aside>
        </div>
    </div>
</section>

<script>
    // ═══ ความคืบหน้าการเรียน — localStorage (ไม่มี DB) ═══
    var COURSE_ID = <?= $id ?>;
    var CUR_IDX = <?= $curIdx ?>;
    var TOTAL = <?= $total ?>;
    var KEY = 'ph_progress_' + COURSE_ID;

    function getDone() {
        try { return JSON.parse(localStorage.getItem(KEY)) || []; } catch (e) { return []; }
    }
    function saveDone(list) {
        localStorage.setItem(KEY, JSON.stringify(list));
    }

    function toggleDone() {
        var list = getDone();
        var i = list.indexOf(CUR_IDX);
        if (i >= 0) { list.splice(i, 1); } else { list.push(CUR_IDX); }
        saveDone(list);
        renderDone();
    }

    function renderDone() {
        var list = getDone();
        document.querySelectorAll('.lesson-item').forEach(function (el) {
            var idx = parseInt(el.getAttribute('data-idx'), 10);
            if (idx === CUR_IDX) return;
            el.classList.toggle('done', list.indexOf(idx) >= 0);
        });
        // ปุ่มเรียนจบ
        var isDone = list.indexOf(CUR_IDX) >= 0;
        var btn = document.getElementById('done-btn');
        var lbl = document.getElementById('done-label');
        if (isDone) {
            btn.className = 'inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl border-2 border-brand-green bg-brand-green/10 text-brand-greenHover';
            lbl.textContent = 'เรียนจบแล้ว ✓';
        } else {
            btn.className = 'inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl border-2 border-brand-navy/30 text-brand-navy hover:border-brand-navy';
            lbl.textContent = 'ทำเครื่องหมายเรียนจบ';
        }
        // progress
        var pct = Math.round((list.length / TOTAL) * 100);
        document.getElementById('pct-label').textContent = pct + '%';
        document.getElementById('pct-fill').style.width = pct + '%';
    }

    renderDone();
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>

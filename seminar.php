<?php
$pageTitle = 'สัมมนา & คอร์ส';
include 'includes/header.php';
?>

<!-- ============================================
     HERO SECTION
     ============================================ -->
<section class="bg-brand-navy text-white relative overflow-hidden hero-pattern">
    <div class="absolute right-0 top-0 bottom-0 w-1/3 flex items-center justify-center opacity-[0.04] z-0 pointer-events-none hidden lg:flex">
        <div class="flex gap-4">
            <div class="w-16 h-[400px] bg-white rounded-t-full"></div>
            <div class="w-16 h-[500px] bg-white rounded-t-full -mt-10"></div>
            <div class="w-16 h-[400px] bg-white rounded-t-full"></div>
        </div>
    </div>
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-16 md:py-20 text-center relative z-10">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full text-xs font-medium text-blue-200 mb-4">
            <i data-lucide="presentation" class="w-3.5 h-3.5"></i>
            ประกันจริงใจ by ปกป้อง Knowledge Hub
        </div>
        <h1 class="text-3xl md:text-5xl font-bold mb-3 tracking-tight">สัมมนา & คอร์ส</h1>
        <p class="text-blue-200 text-lg max-w-3xl mx-auto leading-relaxed">รวมภาพบรรยากาศกิจกรรมและสัมมนาของเรา</p>
    </div>
</section>

<?php
// ─── Gallery Data (รูปภาพล้วน — ไม่มีข้อความ, ไม่มีปุ่ม, ไม่มี price) ──────
$gallery = [
    ['file' => '1784911006002.webp', 'cat' => 'กิจกรรม',           'aspect' => 'aspect-[3/4]'],
    ['file' => '1784911006082.webp', 'cat' => 'ทีมงาน',           'aspect' => 'aspect-[4/3]'],
    ['file' => '1784911030951.webp', 'cat' => 'เทคนิคการขาย',     'aspect' => 'aspect-[3/4]'],
    ['file' => '1784911044573.webp', 'cat' => 'การเงิน',           'aspect' => 'aspect-[2/3]'],
    ['file' => '1784911045403.webp', 'cat' => 'สุขภาพ',            'aspect' => 'aspect-[3/2]'],
    ['file' => '1784911045509.webp', 'cat' => 'MDRT',             'aspect' => 'aspect-[2/3]'],
    ['file' => '1784911045601.webp', 'cat' => 'สัมมนา',           'aspect' => 'aspect-[3/4]'],
    ['file' => '1784911045672.webp', 'cat' => 'เทคโนโลยี',        'aspect' => 'aspect-[4/3]'],
    ['file' => '1784911056104.webp', 'cat' => 'สอบ',              'aspect' => 'aspect-[3/2]'],
    ['file' => '1784911056614.webp', 'cat' => 'ทีม',              'aspect' => 'aspect-[3/4]'],
];

// ═══ สัมมนาจาก DB (ถ้ามีข้อมูล) — fallback: array ด้านบน ═══
try {
    if (function_exists('getDB')) {
        $__stmt = getDB()->query('SELECT title, img, location, event_date FROM seminars WHERE is_active = 1 AND img != "" ORDER BY sort_order, id LIMIT 30');
        $__rows = $__stmt->fetchAll();
        if (count($__rows) > 0) {
            $gallery = [];
            $__aspects = ['aspect-[3/4]', 'aspect-[4/3]', 'aspect-[2/3]', 'aspect-[3/2]'];
            foreach ($__rows as $i => $__r) {
                // ใช้ URL เต็ม (รองรับทั้ง /assets/image/seminar/ เดิม และ /assets/seminars/ ใหม่)
                $gallery[] = [
                    'file' => $__r['img'],
                    'cat' => $__r['location'] ?: 'สัมมนา',
                    'aspect' => $__aspects[$i % 4],
                ];
            }
        }
    }
} catch (Throwable $e) {
    // DB ไม่พร้อม — ใช้ array เดิม
}
?>

<!-- GALLERY — Pure Image Masonry -->
<section class="py-12 md:py-16 bg-gray-50/70">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="masonry-grid" id="gallery-grid">
            <?php foreach ($gallery as $i => $item): ?>
            <div class="masonry-item gallery-reveal" data-category="<?= htmlspecialchars($item['cat']) ?>" data-index="<?= $i ?>">
                <div class="gallery-card bg-white rounded-2xl overflow-hidden shadow-soft border border-gray-100/80 group cursor-pointer"
                     onclick="openLightbox(<?= $i ?>)">
                    <div class="relative overflow-hidden <?= $item['aspect'] ?>">
                        <!-- Image only — no text overlay, no badge, no button -->
                        <img src="<?= htmlspecialchars($item['file']) ?>"
                             alt="ภาพกิจกรรม"
                             class="gallery-img w-full h-full object-cover"
                             loading="lazy">
                        <!-- Subtle frame overlay on hover (purely visual, no text) -->
                        <div class="absolute inset-0 ring-0 group-hover:ring-[3px] ring-brand-navy/20 transition-all duration-500 pointer-events-none"></div>
                        <!-- Gentle dark tint on hover (purely visual) -->
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-500 pointer-events-none"></div>
                        <!-- Zoom icon hint (discreet, appears on hover) -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
                            <span class="bg-white/15 backdrop-blur-md text-white/90 text-xs px-3 py-1.5 rounded-full border border-white/30">
                                <i data-lucide="search" class="w-3.5 h-3.5 inline -mt-0.5"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty state removed -->
    </div>
</section>

<!-- ============================================
     LIGHTBOX MODAL — Full-size image viewer
     ============================================ -->
<div id="lightbox-overlay" class="fixed inset-0 bg-black/90 z-[999] hidden items-center justify-center"
     onclick="closeLightbox()" role="dialog" aria-label="รูปภาพขนาดใหญ่">
    <div class="relative w-full h-full flex items-center justify-center p-4 md:p-8"
         onclick="event.stopPropagation()">

        <!-- Close button -->
        <button onclick="closeLightbox()"
                class="absolute top-4 right-4 z-20 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all duration-300 border border-white/20 backdrop-blur-sm hover:scale-110">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>

        <!-- Prev button -->
        <button id="lightbox-prev"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all duration-300 border border-white/20 backdrop-blur-sm hover:scale-110">
            <i data-lucide="chevron-left" class="w-6 h-6"></i>
        </button>

        <!-- Next button -->
        <button id="lightbox-next"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all duration-300 border border-white/20 backdrop-blur-sm hover:scale-110">
            <i data-lucide="chevron-right" class="w-6 h-6"></i>
        </button>

        <!-- Image (no caption/text) -->
        <img id="lightbox-img" src=""
             class="max-w-full max-h-full w-auto h-auto object-contain rounded-2xl shadow-2xl"
             alt="รูปภาพ">
    </div>
</div>

<!-- ============================================
     INLINE STYLES — Override / augment gallery
     ============================================ -->
<style>
/* ─── Enhanced Hover Effects ─────────────────── */
.gallery-card {
    transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
}
.gallery-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 48px -12px rgba(0, 55, 129, 0.25);
}
.gallery-img {
    transition: transform 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
}
.group:hover .gallery-img {
    transform: scale(1.08);
}

/* ─── Lightbox transitions ──────────────────── */
#lightbox-overlay {
    transition: opacity 0.3s ease;
}
#lightbox-overlay.hidden { display: none !important; }
#lightbox-overlay.flex   { display: flex !important; }

/* ─── Prev/Next button visibility states ────── */
#lightbox-prev,
#lightbox-next {
    display: none;
}
#lightbox-prev.visible,
#lightbox-next.visible {
    display: flex;
}

/* ─── Image counter badge ───────────────────── */
.lightbox-counter {
    position: absolute;
    bottom: 6rem;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(8px);
    color: rgba(255,255,255,0.7);
    font-size: 0.75rem;
    padding: 0.35rem 1rem;
    border-radius: 9999px;
    border: 1px solid rgba(255,255,255,0.15);
    pointer-events: none;
    z-index: 10;
}

/* ─── Ring overlay for hover frame effect ───── */
.gallery-card .ring-brand-navy\/20 {
    --tw-ring-color: rgba(0, 55, 129, 0.2);
}
</style>

<!-- ============================================
     SCRIPTS
     ============================================ -->
<script>
// ─── Lightbox Data (from PHP) ─────────────────
<?php
$images = [];
foreach ($gallery as $item) {
    $images[] = json_encode([
        'src' => $item['file'],
    ]);
}
echo 'const lightboxImages = [' . implode(',', $images) . '];';
?>

let currentIndex = 0;

function openLightbox(index) {
    const overlay = document.getElementById('lightbox-overlay');
    const img = document.getElementById('lightbox-img');

    currentIndex = index;
    img.src = lightboxImages[currentIndex].src;

    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    document.body.classList.add('lightbox-open');

    updateNavButtons();
}

function closeLightbox() {
    const overlay = document.getElementById('lightbox-overlay');
    overlay.classList.add('hidden');
    overlay.classList.remove('flex');
    document.body.classList.remove('lightbox-open');
}

function updateNavButtons() {
    const prev = document.getElementById('lightbox-prev');
    const next = document.getElementById('lightbox-next');
    prev.classList.toggle('visible', currentIndex > 0);
    next.classList.toggle('visible', currentIndex < lightboxImages.length - 1);
}

// ─── Prev / Next ──────────────────────────────
document.getElementById('lightbox-prev').addEventListener('click', function(e) {
    e.stopPropagation();
    if (currentIndex > 0) {
        currentIndex--;
        document.getElementById('lightbox-img').src = lightboxImages[currentIndex].src;
        updateNavButtons();
    }
});

document.getElementById('lightbox-next').addEventListener('click', function(e) {
    e.stopPropagation();
    if (currentIndex < lightboxImages.length - 1) {
        currentIndex++;
        document.getElementById('lightbox-img').src = lightboxImages[currentIndex].src;
        updateNavButtons();
    }
});

// ─── Keyboard Navigation ──────────────────────
document.addEventListener('keydown', function(e) {
    const overlay = document.getElementById('lightbox-overlay');
    if (overlay.classList.contains('hidden')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') document.getElementById('lightbox-prev').click();
    if (e.key === 'ArrowRight') document.getElementById('lightbox-next').click();
});

// ─── Scroll Reveal (Intersection Observer) ─────
document.addEventListener('DOMContentLoaded', function() {
    const revealEls = document.querySelectorAll('.gallery-reveal');
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        revealEls.forEach(function(el) { observer.observe(el); });
    } else {
        revealEls.forEach(function(el) { el.classList.add('revealed'); });
    }
});
</script>

<?php include 'includes/footer.php'; ?>

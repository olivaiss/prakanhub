<?php
$pageTitle = 'เกี่ยวกับผม';
include 'includes/header.php';
?>
<style>
/* ============================================
   ABOUT PAGE — Premium Design System
   ============================================ */

/* --- CSS Variables --- */
:root {
    --glass-bg: rgba(255,255,255,0.08);
    --glass-border: rgba(255,255,255,0.12);
    --glass-shadow: 0 8px 32px rgba(0,0,0,0.12);
    --glass-blur: blur(16px);
    --navy: #003781;
    --gold: #F5C842;
    --gold-light: #FFD700;
}

/* --- Hero --- */
.about-hero {
    min-height: 92vh;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #0B1628 0%, #0F2140 30%, #1A3A6B 60%, #0F2140 100%);
}
.about-hero::after {
    content: '';
    position: absolute;
    inset: 0;
    background: url('https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1920&q=80') center/cover no-repeat;
    opacity: 0.04;
    z-index: 0;
}
.hero-grid-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    background-image:
        linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
    background-size: 64px 64px;
}
.hero-glow-1 {
    position: absolute;
    top: -20%;
    right: -10%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, transparent 70%);
    border-radius: 50%;
    z-index: 1;
}
.hero-glow-2 {
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(245,200,66,0.08) 0%, transparent 70%);
    border-radius: 50%;
    z-index: 1;
}

/* Particles */
.hero-particle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    z-index: 1;
    pointer-events: none;
    animation: particle-float linear infinite;
}
@keyframes particle-float {
    0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10% { opacity: 0.7; }
    90% { opacity: 0.7; }
    100% { transform: translateY(-10vh) rotate(720deg); opacity: 0; }
}

/* --- Portrait --- */
.portrait-ring {
    position: relative;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    padding: 5px;
    background: conic-gradient(from 0deg, #F5C842, #2563eb, #60a5fa, #F5C842, #2563eb, #F5C842);
}
.portrait-ring-inner {
    border-radius: 50%;
    overflow: hidden;
    width: 100%;
    height: 100%;
    position: relative;
}
.portrait-ring-inner img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.6s ease;
}
.portrait-ring:hover .portrait-ring-inner img {
    transform: scale(1.05);
}
@media (max-width: 640px) {
    .portrait-ring { width: 220px; height: 220px; }
}

/* Glassmorphism Card */
.glass-card {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-blur);
    -webkit-backdrop-filter: var(--glass-blur);
    border: 1px solid var(--glass-border);
    box-shadow: var(--glass-shadow);
}
.glass-card-light {
    background: rgba(255,255,255,0.6);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.8);
}

/* Float animations */
.float-slow { animation: float 6s ease-in-out infinite; }
.float-medium { animation: float 4s ease-in-out infinite; }
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
}

/* --- Timeline --- */
.timeline-modern {
    position: relative;
}
.timeline-modern::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #2563eb, #60a5fa, #93c5fd, #60a5fa, #2563eb);
    transform: translateX(-50%);
    border-radius: 2px;
}
@media (max-width: 768px) {
    .timeline-modern::before { left: 24px; }
}
.timeline-item {
    position: relative;
    margin-bottom: 2.5rem;
}
.timeline-dot-modern {
    position: absolute;
    left: 50%;
    top: 28px;
    width: 20px;
    height: 20px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border-radius: 50%;
    border: 4px solid #dbeafe;
    box-shadow: 0 0 0 6px rgba(37,99,235,0.15), 0 4px 12px rgba(37,99,235,0.3);
    transform: translateX(-50%);
    z-index: 2;
    transition: all 0.3s ease;
}
.timeline-item:hover .timeline-dot-modern {
    box-shadow: 0 0 0 8px rgba(37,99,235,0.2), 0 6px 20px rgba(37,99,235,0.4);
    transform: translateX(-50%) scale(1.15);
}
@media (max-width: 768px) {
    .timeline-dot-modern { left: 24px; top: 24px; }
}

/* --- Experience Cards --- */
.exp-card {
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position: relative;
    overflow: hidden;
}
.exp-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2563eb, #60a5fa, #93c5fd);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.exp-card:hover::before { transform: scaleX(1); }
.exp-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px -12px rgba(0,55,129,0.2);
}
.exp-icon-wrap {
    transition: all 0.4s ease;
}
.exp-card:hover .exp-icon-wrap {
    transform: scale(1.1) rotate(-5deg);
}

/* --- MDRT Premium --- */
.mdrt-glow {
    animation: mdrt-pulse 3s ease-in-out infinite;
}
@keyframes mdrt-pulse {
    0%, 100% { box-shadow: 0 0 20px rgba(245,200,66,0.2), 0 0 40px rgba(245,200,66,0.1); }
    50% { box-shadow: 0 0 30px rgba(245,200,66,0.4), 0 0 60px rgba(245,200,66,0.2); }
}

/* --- Philosophy --- */
.phil-card {
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position: relative;
    overflow: hidden;
}
.phil-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 25%;
    right: 25%;
    height: 3px;
    border-radius: 2px;
    transition: all 0.4s ease;
}
.phil-card:hover::before { left: 10%; right: 10%; }
.phil-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 48px -12px rgba(0,55,129,0.18);
}

/* --- Stats Counter --- */
.stat-glow {
    text-shadow: 0 0 40px rgba(37,99,235,0.3);
}
.stat-card {
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
}
.stat-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 50% 0%, rgba(37,99,235,0.1), transparent 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
}
.stat-card:hover::after { opacity: 1; }
.stat-card:hover {
    transform: translateY(-4px);
}

/* --- Reviews --- */
.review-card {
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position: relative;
}
.review-card:hover {
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 16px 40px -8px rgba(0,55,129,0.15);
}
.review-scroll {
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.review-scroll::-webkit-scrollbar { display: none; }

/* --- CTA --- */
.cta-premium {
    background: linear-gradient(135deg, #0B1628 0%, #0F2140 30%, #1A3A6B 60%, #0F2140 100%);
    position: relative;
    overflow: hidden;
}
.cta-premium::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at 30% 40%, rgba(37,99,235,0.08) 0%, transparent 50%),
                radial-gradient(circle at 70% 60%, rgba(245,200,66,0.05) 0%, transparent 50%);
    z-index: 0;
}

/* --- Scroll Reveal (Enhanced) --- */
.reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.reveal-visible {
    opacity: 1;
    transform: translateY(0);
}
.reveal-left {
    opacity: 0;
    transform: translateX(-40px);
    transition: all 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.reveal-left.reveal-visible {
    opacity: 1;
    transform: translateX(0);
}
.reveal-right {
    opacity: 0;
    transform: translateX(40px);
    transition: all 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.reveal-right.reveal-visible {
    opacity: 1;
    transform: translateX(0);
}
.reveal-scale {
    opacity: 0;
    transform: scale(0.9);
    transition: all 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}
.reveal-scale.reveal-visible {
    opacity: 1;
    transform: scale(1);
}

/* --- Section Divider --- */
.section-divider {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, #003781, #2563eb, #60a5fa);
    border-radius: 2px;
    margin: 0 auto;
}

/* --- Skill Pill Tags --- */
.skill-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 600;
    transition: all 0.3s ease;
}
.skill-pill:hover { transform: translateY(-1px); }

/* --- Shimmer Text --- */
.shimmer-text {
    background: linear-gradient(90deg, #F5C842 0%, #FFD700 25%, #F5C842 50%, #FFD700 75%, #F5C842 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: shimmer 3s linear infinite;
}
@keyframes shimmer {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
}

/* --- Counter number --- */
.stat-number {
    font-variant-numeric: tabular-nums;
}

/* --- Separator line decorative --- */
.gradient-line {
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(37,99,235,0.3), transparent);
    width: 80%;
    margin: 0 auto;
}

/* Mobile adjustments */
@media (max-width: 640px) {
    .about-hero { min-height: 80vh; }
}
</style>

<!-- ============================================================ -->
<!-- SECTION 1: HERO — Premium Fullscreen with Glassmorphism Stats -->
<!-- ============================================================ -->
<section class="about-hero flex items-center relative">
    <!-- Grid overlay -->
    <div class="hero-grid-overlay"></div>
    <div class="hero-glow-1"></div>
    <div class="hero-glow-2"></div>

    <!-- Floating Particles -->
    <?php for ($i = 0; $i < 25; $i++): ?>
    <div class="hero-particle" style="
        left: <?= rand(2, 98) ?>%;
        width: <?= rand(2, 6) ?>px;
        height: <?= rand(2, 6) ?>px;
        animation-duration: <?= rand(15, 35) ?>s;
        animation-delay: <?= rand(0, 20) ?>s;
    "></div>
    <?php endfor; ?>

    <div class="relative z-[3] w-full max-w-7xl mx-auto px-4 lg:px-6 py-12 lg:py-0">
        <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 items-center">

            <!-- LEFT: Portrait & Floating Badges -->
            <div class="lg:col-span-5 flex justify-center lg:justify-end order-1">
                <div class="relative">
                    <!-- Portrait with rotating ring -->
                    <div class="portrait-ring">
                        <div class="portrait-ring-inner">
                            <img src="/assets/image/about/about.webp"
                                 alt="ประกันจริงใจ by ปกป้อง"
                                 loading="eager">
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Hero Content -->
            <div class="lg:col-span-7 text-center lg:text-left order-2">
                <!-- Pill Badge -->
                <div class="inline-flex items-center gap-2 glass-card rounded-full px-4 py-1.5 mb-6 reveal">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    <span class="text-xs font-medium text-blue-200 tracking-wide">Insurance Advisor — Allianz Ayudhya</span>
                </div>

                <!-- Name -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white leading-tight mb-3 font-[Kanit] reveal delay-100">
                    ประกันจริงใจ<br class="hidden sm:block">
                    <span class="bg-gradient-to-r from-yellow-300 to-yellow-500 bg-clip-text text-transparent">by ปกป้อง</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-xl sm:text-2xl lg:text-3xl font-semibold text-blue-200 mb-3 reveal delay-200">
                    ที่ปรึกษาประกันชีวิตมืออาชีพ
                </p>

                <!-- Quote -->
                <p class="text-base sm:text-lg text-blue-100/70 max-w-xl mx-auto lg:mx-0 mb-8 leading-relaxed reveal delay-300">
                    <i data-lucide="quote" class="w-4 h-4 inline-block opacity-50 -translate-y-1"></i>
                    เพราะทุกครอบครัวสมควรได้รับความคุ้มครองที่ดีที่สุด
                    — ผมพร้อมเป็นที่ปรึกษาที่คุณวางใจได้
                </p>

                <!-- CTAs -->
                <div class="flex flex-wrap gap-3 justify-center lg:justify-start reveal delay-400">
                    <a href="https://lin.ee/QngrNQ3" target="_blank"
                       class="group inline-flex items-center gap-2.5 bg-gradient-to-r from-green-400 to-green-500 hover:from-green-500 hover:to-green-600 text-white font-bold px-7 py-3.5 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 text-base relative overflow-hidden">
                        <span class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                        <img src="/assets/icon/line.svg" class="w-5 h-5 relative z-[1]">
                        <span class="relative z-[1]">ปรึกษาฟรี ไม่มีข้อผูกมัด</span>
                    </a>
                    <a href="#history"
                       class="inline-flex items-center gap-2 glass-card hover:bg-white/15 text-white px-7 py-3.5 rounded-xl transition-all duration-300 text-base">
                        <i data-lucide="chevron-down" class="w-5 h-5"></i>
                        รู้จักผมมากขึ้น
                    </a>
                </div>

                <!-- Glassmorphism Stats Row -->
                <div class="grid grid-cols-3 gap-3 sm:gap-4 mt-10 reveal delay-500">
                    <div class="glass-card rounded-2xl p-4 sm:p-5 text-center hover:bg-white/10 transition-all duration-300">
                        <p class="text-2xl sm:text-3xl font-extrabold text-white font-[Kanit]">1,000+</p>
                        <div class="flex items-center justify-center gap-1.5 mt-1">
                            <i data-lucide="users" class="w-3.5 h-3.5 text-blue-300"></i>
                            <p class="text-[11px] sm:text-xs text-blue-200/80">ครอบครัวที่ไว้วางใจ</p>
                        </div>
                    </div>
                    <div class="glass-card rounded-2xl p-4 sm:p-5 text-center hover:bg-white/10 transition-all duration-300">
                        <p class="text-2xl sm:text-3xl font-extrabold text-white font-[Kanit]">MDRT</p>
                        <div class="flex items-center justify-center gap-1.5 mt-1">
                            <i data-lucide="award" class="w-3.5 h-3.5 text-yellow-300"></i>
                            <p class="text-[11px] sm:text-xs text-blue-200/80">รางวัลระดับโลก</p>
                        </div>
                    </div>
                    <div class="glass-card rounded-2xl p-4 sm:p-5 text-center hover:bg-white/10 transition-all duration-300">
                        <p class="text-2xl sm:text-3xl font-extrabold text-white font-[Kanit]">10+</p>
                        <div class="flex items-center justify-center gap-1.5 mt-1">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-300"></i>
                            <p class="text-[11px] sm:text-xs text-blue-200/80">ปีประสบการณ์</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom fade transition -->
    <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-gray-50 to-transparent z-[3]"></div>
</section>

<?php
// ═══ เนื้อหา "เกี่ยวกับผม" จากฐานข้อมูล (pages table) — แสดงถ้ามี ═══
$__pageContent = '';
try {
    if (function_exists('getDB')) {
        $__s = getDB()->prepare('SELECT content FROM pages WHERE slug = ? AND content IS NOT NULL AND content != "" LIMIT 1');
        $__s->execute(['about']);
        $__pageContent = trim((string)$__s->fetchColumn());
    }
} catch (Throwable $e) {
    // DB ไม่พร้อม — ไม่แสดง section
}
?>
<?php if ($__pageContent !== ''): ?>
<section id="about-db" class="py-16 lg:py-24 bg-white relative overflow-hidden">
    <div class="relative max-w-7xl mx-auto px-4 lg:px-6">
        <div class="prose-db">
            <?= $__pageContent ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================================ -->
<!-- SECTION 2: TIMELINE — เส้นทางของผม                           -->
<!-- ============================================================ -->
<section id="history" class="py-16 lg:py-24 bg-gray-50 relative overflow-hidden">
    <!-- Decorative bg -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#003781 1px, transparent 1px); background-size: 40px 40px;"></div>
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-200 to-transparent"></div>

    <div class="relative max-w-7xl mx-auto px-4 lg:px-6">
        <!-- Section Header -->
        <div class="text-center mb-14 lg:mb-18">
            <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-sm font-medium px-5 py-1.5 rounded-full mb-4 reveal">
                <i data-lucide="graduation-cap" class="w-4 h-4"></i>
                ประวัติและความเป็นมา
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4 font-[Kanit] reveal delay-100">เส้นทางของผม</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg reveal delay-200">จากวันแรกที่ก้าวเข้าสู่วงการประกัน สู่การเป็นที่ปรึกษาที่คุณวางใจได้</p>
            <div class="section-divider mt-5"></div>
        </div>

        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-start">
            <!-- LEFT: Bio + Education -->
            <div class="space-y-8 reveal">
                <!-- Story Card -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full -translate-y-1/2 translate-x-1/2 opacity-50 group-hover:opacity-80 transition-opacity"></div>
                    <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center gap-2 font-[Kanit] relative">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-blue-600"></i>
                        </span>
                        เรื่องราวของผม
                    </h3>
                    <div class="space-y-4 text-gray-600 leading-relaxed relative">
                        <p>
                            สวัสดีครับ ผม <strong class="text-gray-900">ปกป้อง</strong> ที่ปรึกษาประกันชีวิตมืออาชีพ
                            ประจำบริษัท <strong class="text-blue-700">อลิอันซ์ อยุธยา</strong>
                            ความหลงใหลในอาชีพที่ปรึกษาประกันของผมเริ่มต้นจากความเชื่อที่ว่า
                            <em class="text-blue-600 not-italic font-medium">"ทุกครอบครัวสมควรได้รับการปกป้องและวางแผนทางการเงินที่ดี"</em>
                        </p>
                        <p>
                            ผมเริ่มต้นเส้นทางนี้เมื่อกว่า 10 ปีที่แล้ว ด้วยความมุ่งมั่นที่จะสร้างความแตกต่าง
                            ให้กับชีวิตของผู้คนผ่านการวางแผนประกันที่ถูกต้อง เหมาะสม และคุ้มค่าที่สุด
                            ไม่ใช่แค่การขายกรมธรรม์ แต่เป็นการสร้าง <strong class="text-gray-900">ความอุ่นใจและความมั่นคง</strong> ให้กับครอบครัว
                        </p>
                        <p>
                            ทุกวันนี้ ผมยังคงยึดมั่นในหลักการเดียวกัน — ฟังลูกค้าอย่างเข้าใจ
                            วิเคราะห์อย่างละเอียด และแนะนำอย่างจริงใจ โดยไม่มุ่งเน้นยอดขาย
                            แต่มุ่งเน้นการสร้างความสัมพันธ์ระยะยาวกับลูกค้าทุกคน
                        </p>
                    </div>
                </div>

                <!-- Education Card -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 left-0 w-32 h-32 bg-green-50 rounded-full -translate-y-1/2 -translate-x-1/2 opacity-50 group-hover:opacity-80 transition-opacity"></div>
                    <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center gap-2 font-[Kanit] relative">
                        <span class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                            <i data-lucide="book-open" class="w-4 h-4 text-green-600"></i>
                        </span>
                        การศึกษา
                    </h3>
                    <div class="space-y-5 relative">
                        <?php
                        $education = [
                            ['icon' => 'graduation-cap', 'bg' => 'bg-blue-100', 'color' => 'text-blue-600',
                             'title' => 'ปริญญาตรี คณะบริหารธุรกิจ',
                             'sub' => 'มหาวิทยาลัยชื่อดัง — เอกการตลาด',
                             'tag' => 'สำเร็จการศึกษา'],
                            ['icon' => 'badge-check', 'bg' => 'bg-green-100', 'color' => 'text-green-600',
                             'title' => 'ใบอนุญาตตัวแทนประกันชีวิต',
                             'sub' => 'ผ่านการอบรมและสอบจาก คปภ. (OIC)',
                             'tag' => 'เลขที่ใบอนุญาต: 12345/2560'],
                            ['icon' => 'trending-up', 'bg' => 'bg-purple-100', 'color' => 'text-purple-600',
                             'title' => 'อบรมพัฒนาตนเองอย่างต่อเนื่อง',
                             'sub' => 'ความรู้ด้านการวางแผนการเงิน สินเชื่อ ประกันภัย ตลอดจนการตลาดดิจิทัล',
                             'tag' => ''],
                        ];
                        foreach ($education as $edu):
                        ?>
                        <div class="flex gap-4 group/edu">
                            <div class="w-12 h-12 rounded-xl <?= $edu['bg'] ?> flex items-center justify-center shrink-0 group-hover/edu:scale-110 transition-transform duration-300">
                                <i data-lucide="<?= $edu['icon'] ?>" class="w-6 h-6 <?= $edu['color'] ?>"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900"><?= $edu['title'] ?></h4>
                                <p class="text-sm text-gray-500"><?= $edu['sub'] ?></p>
                                <?php if ($edu['tag']): ?>
                                <span class="inline-block mt-1.5 text-xs text-gray-400 bg-gray-100 px-2.5 py-0.5 rounded-full"><?= $edu['tag'] ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Timeline -->
            <div class="reveal delay-300">
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2 font-[Kanit]">
                        <span class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                            <i data-lucide="map-pin" class="w-4 h-4 text-orange-600"></i>
                        </span>
                        เส้นทางอาชีพ
                    </h3>

                    <div class="timeline-modern">
                        <?php
                        $milestones = [
                            ['year' => '2556', 'title' => 'เริ่มต้นอาชีพ', 'desc' => 'เริ่มเป็นที่ปรึกษาประกันชีวิต มุ่งมั่นเรียนรู้และพัฒนาทักษะ', 'icon' => 'flag', 'color' => 'blue'],
                            ['year' => '2557', 'title' => 'ผ่านการทดสอบ คปภ.', 'desc' => 'สอบผ่านใบอนุญาตตัวแทนประกันชีวิต พร้อมให้บริการอย่างเต็มรูปแบบ', 'icon' => 'check-circle', 'color' => 'green'],
                            ['year' => '2558', 'title' => 'ลูกค้าครบ 100 ครอบครัว', 'desc' => 'ได้รับความไว้วางใจจากลูกค้าครบ 100 ครอบครัวภายในปีแรก', 'icon' => 'users', 'color' => 'purple'],
                            ['year' => '2560', 'title' => 'คว้ารางวัล MDRT', 'desc' => 'ประสบความสำเร็จระดับสากล ได้รับรางวัล MDRT อันทรงเกียรติ', 'icon' => 'award', 'color' => 'yellow'],
                            ['year' => '2563', 'title' => '500+ ครอบครัวที่ไว้วางใจ', 'desc' => 'ขยายการดูแลลูกค้าไปกว่า 500 ครอบครัวทั่วประเทศ', 'icon' => 'heart', 'color' => 'red'],
                            ['year' => '2567', 'title' => '1,000+ ครอบครัว และ MDRT ซ้ำ', 'desc' => 'ปัจจุบันดูแลกว่า 1,000 ครอบครัว พร้อมรักษามาตรฐาน MDRT อย่างต่อเนื่อง', 'icon' => 'star', 'color' => 'amber'],
                        ];
                        $dotColors = [
                            'blue' => 'bg-blue-500', 'green' => 'bg-green-500', 'purple' => 'bg-purple-500',
                            'yellow' => 'bg-yellow-500', 'red' => 'bg-red-500', 'amber' => 'bg-amber-500'
                        ];
                        $iconBgs = [
                            'blue' => 'bg-blue-100 text-blue-600', 'green' => 'bg-green-100 text-green-600',
                            'purple' => 'bg-purple-100 text-purple-600', 'yellow' => 'bg-yellow-100 text-yellow-600',
                            'red' => 'bg-red-100 text-red-600', 'amber' => 'bg-amber-100 text-amber-600'
                        ];
                        foreach ($milestones as $index => $item):
                        ?>
                        <div class="timeline-item pl-14 md:pl-0 group">
                            <div class="timeline-dot-modern <?= $dotColors[$item['color']] ?>"></div>
                            <div class="md:w-[calc(50%-30px)] <?= $index % 2 === 0 ? 'md:ml-auto md:pl-8' : 'md:mr-auto md:pr-8 md:text-right' ?>">
                                <div class="bg-gradient-to-br from-white to-gray-50/50 rounded-xl p-5 border border-gray-100 group-hover:border-blue-200 group-hover:shadow-md transition-all duration-300">
                                    <div class="flex items-center gap-2 mb-2 <?= $index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse' ?>">
                                        <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2.5 py-0.5 rounded-full shrink-0"><?= $item['year'] ?></span>
                                        <div class="w-7 h-7 rounded-lg <?= $iconBgs[$item['color']] ?> flex items-center justify-center shrink-0">
                                            <i data-lucide="<?= $item['icon'] ?>" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-900"><?= $item['title'] ?></span>
                                    </div>
                                    <p class="text-sm text-gray-500 leading-relaxed"><?= $item['desc'] ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- SECTION 3: EXPERIENCE — ประสบการณ์ 10+ ปี                    -->
<!-- ============================================================ -->
<section class="py-16 lg:py-24 bg-white relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-50 rounded-full blur-3xl opacity-60"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-50 rounded-full blur-3xl opacity-40"></div>

    <div class="relative max-w-7xl mx-auto px-4 lg:px-6">
        <!-- Section Header -->
        <div class="text-center mb-14 lg:mb-18">
            <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-sm font-medium px-5 py-1.5 rounded-full mb-4 reveal">
                <i data-lucide="briefcase" class="w-4 h-4"></i>
                ประสบการณ์
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4 font-[Kanit] reveal delay-100">ประสบการณ์กว่า 10 ปี</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg reveal delay-200">ความเชี่ยวชาญที่ผ่านการพิสูจน์ พร้อมดูแลคุณและครอบครัวอย่างมืออาชีพ</p>
            <div class="section-divider mt-5"></div>
        </div>

        <!-- Key Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <?php
            $experiences = [
                ['icon' => 'users', 'gradient' => 'from-blue-500 to-blue-600', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600',
                 'title' => 'ลูกค้า 1,000+', 'desc' => 'ครอบครัวที่ไว้วางใจให้ผมดูแลแผนประกันและความคุ้มครอง'],
                ['icon' => 'file-text', 'gradient' => 'from-emerald-500 to-emerald-600', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-600',
                 'title' => 'กรมธรรม์ 2,500+ ฉบับ', 'desc' => 'จำนวนกรมธรรม์ที่ดูแลและให้บริการครบวงจร'],
                ['icon' => 'landmark', 'gradient' => 'from-violet-500 to-violet-600', 'bg' => 'bg-violet-100', 'text' => 'text-violet-600',
                 'title' => 'สินเชื่อ 500+ รายการ', 'desc' => 'ช่วยเหลือด้านสินเชื่อเพื่อให้ลูกค้าบรรลุเป้าหมาย'],
                ['icon' => 'award', 'gradient' => 'from-orange-500 to-orange-600', 'bg' => 'bg-orange-100', 'text' => 'text-orange-600',
                 'title' => 'รางวัล MDRT', 'desc' => 'มาตรฐานระดับโลกด้านการเป็นที่ปรึกษาประกันมืออาชีพ'],
            ];
            foreach ($experiences as $exp):
            ?>
            <div class="exp-card bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100 reveal group">
                <div class="exp-icon-wrap w-14 h-14 rounded-2xl <?= $exp['bg'] ?> flex items-center justify-center mb-5">
                    <i data-lucide="<?= $exp['icon'] ?>" class="w-7 h-7 <?= $exp['text'] ?>"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2 font-[Kanit]"><?= $exp['title'] ?></h3>
                <p class="text-sm text-gray-500 leading-relaxed"><?= $exp['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Expertise Areas -->
        <div class="mt-14 lg:mt-18 grid md:grid-cols-3 gap-5 reveal">
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100/50 p-6 lg:p-8 border border-blue-100 hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-blue-200/30 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center gap-3 mb-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md">
                        <i data-lucide="heart" class="w-5 h-5 text-white"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 font-[Kanit]">ประกันชีวิต</h4>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed relative">วางแผนความคุ้มครองชีวิตที่เหมาะสมกับทุกช่วงวัย</p>
            </div>
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100/50 p-6 lg:p-8 border border-emerald-100 hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-emerald-200/30 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center gap-3 mb-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-md">
                        <i data-lucide="activity" class="w-5 h-5 text-white"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 font-[Kanit]">ประกันสุขภาพ</h4>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed relative">ค่ารักษาพยาบาลเหมาจ่าย โรคร้ายแรง คุ้มครองสูงสุด</p>
            </div>
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100/50 p-6 lg:p-8 border border-amber-100 hover:shadow-lg transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-amber-200/30 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="flex items-center gap-3 mb-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-md">
                        <i data-lucide="shield" class="w-5 h-5 text-white"></i>
                    </div>
                    <h4 class="font-bold text-gray-900 font-[Kanit]">วางแผนการเงิน</h4>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed relative">ประกันออมทรัพย์ ประกันบำนาญ ลดหย่อนภาษี</p>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- SECTION 4: MDRT — รางวัล MDRT Premium                        -->
<!-- ============================================================ -->
<section class="py-16 lg:py-24 relative overflow-hidden">
    <!-- Dark luxury background -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-[#0F1A2E] to-gray-900"></div>
    <div class="absolute inset-0 opacity-[0.07]" style="background-image: url('https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1920&q=80'); background-size: cover; background-position: center;"></div>
    <!-- Gold sparkles -->
    <div class="absolute inset-0" style="background: radial-gradient(circle at 25% 40%, rgba(245,200,66,0.08) 0%, transparent 50%), radial-gradient(circle at 75% 60%, rgba(245,200,66,0.05) 0%, transparent 50%);"></div>
    <div class="absolute top-10 left-10 w-2 h-2 bg-yellow-400/30 rounded-full animate-pulse"></div>
    <div class="absolute bottom-20 right-20 w-3 h-3 bg-yellow-400/20 rounded-full animate-pulse" style="animation-delay: 1s;"></div>

    <div class="relative max-w-7xl mx-auto px-4 lg:px-6">
        <!-- Section Header -->
        <div class="text-center mb-14 lg:mb-18">
            <span class="inline-flex items-center gap-2 bg-yellow-500/15 text-yellow-300 text-sm font-medium px-5 py-1.5 rounded-full mb-4 reveal border border-yellow-500/20">
                <i data-lucide="award" class="w-4 h-4"></i>
                ความสำเร็จระดับโลก
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white mb-4 font-[Kanit] reveal delay-100">
                รางวัล <span class="shimmer-text">MDRT</span>
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg reveal delay-200">Million Dollar Round Table — มาตรฐานสูงสุดของวงการที่ปรึกษาประกันชีวิตระดับโลก</p>
            <div class="w-20 h-1 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full mx-auto mt-5"></div>
        </div>

        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <!-- LEFT: MDRT Info -->
            <div class="reveal">
                <div class="glass-card rounded-2xl p-8 lg:p-10 hover:bg-white/[0.10] transition-all duration-300">
                    <!-- MDRT Badge -->
                    <div class="flex items-center gap-5 mb-6">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-yellow-300 to-yellow-600 flex items-center justify-center shadow-2xl mdrt-glow">
                            <span class="text-3xl">🏆</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white font-[Kanit]">MDRT</h3>
                            <p class="text-yellow-300 text-sm font-medium">Million Dollar Round Table</p>
                        </div>
                    </div>

                    <p class="text-gray-300 leading-relaxed mb-6">
                        MDRT (Million Dollar Round Table) เป็นรางวัลอันทรงเกียรติระดับโลกที่มอบให้กับที่ปรึกษาประกันชีวิต
                        ที่มีผลงานและมาตรฐานความเป็นมืออาชีพสูงสุด เพียง <strong class="text-yellow-300">3%</strong> ของที่ปรึกษาประกันทั่วโลก
                        เท่านั้นที่สามารถคว้ารางวัลนี้ได้
                    </p>

                    <div class="space-y-3">
                        <?php
                        $mdrtPoints = [
                            ['text' => 'มาตรฐานจริยธรรมสูงสุด', 'sub' => 'ยึดมั่นในจรรยาบรรณวิชาชีพ ดูแลลูกค้าด้วยความซื่อสัตย์'],
                            ['text' => 'ความรู้ความสามารถระดับสากล', 'sub' => 'ผ่านการพัฒนาทักษะและองค์ความรู้อย่างต่อเนื่อง'],
                            ['text' => 'ผลงานโดดเด่นอย่างต่อเนื่อง', 'sub' => 'รักษามาตรฐาน MDRT ได้อย่างสม่ำเสมอ'],
                        ];
                        foreach ($mdrtPoints as $pt):
                        ?>
                        <div class="flex items-start gap-3 group/mdrt">
                            <div class="w-8 h-8 rounded-lg bg-yellow-500/20 flex items-center justify-center shrink-0 mt-0.5 group-hover/mdrt:bg-yellow-500/30 transition-all">
                                <i data-lucide="check" class="w-4 h-4 text-yellow-400"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm"><?= $pt['text'] ?></p>
                                <p class="text-gray-400 text-xs"><?= $pt['sub'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Certification Showcase -->
            <div class="reveal delay-300">
                <div class="grid grid-cols-2 gap-4">
                    <?php
                    $certs = [
                        ['icon' => '🏅', 'title' => 'MDRT', 'sub' => 'สมาชิกสมาคม', 'delay' => '0'],
                        ['icon' => '📜', 'title' => 'ใบอนุญาต', 'sub' => 'ตัวแทนประกันชีวิต', 'delay' => '100'],
                        ['icon' => '🎯', 'title' => 'Top Producer', 'sub' => 'อันดับต้นของทีม', 'delay' => '200'],
                        ['icon' => '💎', 'title' => 'Allianz', 'sub' => 'พันธมิตรคู่ใจ', 'delay' => '300'],
                    ];
                    foreach ($certs as $c):
                    ?>
                    <div class="glass-card rounded-2xl p-6 text-center hover:bg-white/[0.10] transition-all duration-300 group/cert" style="transition-delay: <?= $c['delay'] ?>ms">
                        <div class="text-4xl mb-3 group-hover/cert:scale-110 transition-transform duration-300 inline-block"><?= $c['icon'] ?></div>
                        <p class="text-white font-bold text-lg font-[Kanit]"><?= $c['title'] ?></p>
                        <p class="text-gray-400 text-xs"><?= $c['sub'] ?></p>
                    </div>
                    <?php endforeach; ?>

                    <!-- Quote -->
                    <div class="col-span-2 rounded-2xl p-6 border border-yellow-500/20 text-center relative overflow-hidden" style="background: linear-gradient(135deg, rgba(245,200,66,0.08) 0%, rgba(245,200,66,0.02) 100%);">
                        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #F5C842 1px, transparent 1px); background-size: 20px 20px;"></div>
                        <div class="relative">
                            <i data-lucide="quote" class="w-8 h-8 text-yellow-500/30 mx-auto mb-2"></i>
                            <p class="text-yellow-300/80 text-sm italic leading-relaxed">
                                "MDRT ไม่ใช่แค่รางวัล แต่คือมาตรฐานที่ผมตั้งไว้กับตัวเอง
                                เพื่อให้ลูกค้าทุกคนได้รับบริการที่ดีที่สุด"
                            </p>
                            <p class="text-yellow-400/50 text-xs mt-2 font-medium">— ประกันจริงใจ by ปกป้อง, MDRT</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="text-center mt-14 reveal">
            <a href="https://lin.ee/QngrNQ3" target="_blank"
               class="group inline-flex items-center gap-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-gray-900 font-bold px-8 py-4 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 relative overflow-hidden">
                <span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                <img src="/assets/icon/line.svg" class="w-5 h-5 relative z-[1]">
                <span class="relative z-[1]">รับคำปรึกษาจาก MDRT</span>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- SECTION 5: PHILOSOPHY — ปรัชญาการทำงาน                      -->
<!-- ============================================================ -->
<section class="py-16 lg:py-24 bg-white relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-blue-200 to-transparent"></div>
    <!-- Decorative -->
    <div class="absolute -bottom-20 -left-20 w-60 h-60 bg-blue-50 rounded-full blur-3xl"></div>
    <div class="absolute -top-20 -right-20 w-60 h-60 bg-blue-50 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 lg:px-6 relative">
        <!-- Section Header -->
        <div class="text-center mb-14 lg:mb-18">
            <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-sm font-medium px-5 py-1.5 rounded-full mb-4 reveal">
                <i data-lucide="heart" class="w-4 h-4"></i>
                ปรัชญาการทำงาน
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4 font-[Kanit] reveal delay-100">ความเชื่อในการทำงานของผม</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg reveal delay-200">หัวใจสำคัญที่ทำให้ผมเป็นที่ปรึกษาที่แตกต่าง</p>
            <div class="section-divider mt-5"></div>
        </div>

        <!-- Philosophy Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $philosophies = [
                ['icon' => 'ear', 'title' => 'ฟังอย่างเข้าใจ',
                 'desc' => 'ทุกความต้องการเริ่มจากการฟัง ผมฟังลูกค้าอย่างตั้งใจ เพื่อเข้าใจความต้องการที่แท้จริง ไม่ใช่แค่ขายประกัน',
                 'color' => 'blue', 'grad' => 'from-blue-500 to-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200'],
                ['icon' => 'search', 'title' => 'วิเคราะห์อย่างรอบคอบ',
                 'desc' => 'เปรียบเทียบทุกทางเลือกอย่างละเอียด เพื่อให้ได้แผนประกันที่เหมาะสม คุ้มค่า และตรงกับความต้องการมากที่สุด',
                 'color' => 'green', 'grad' => 'from-emerald-500 to-emerald-600', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-200'],
                ['icon' => 'heart-handshake', 'title' => 'แนะนำด้วยความจริงใจ',
                 'desc' => 'ไม่แนะนำอะไรที่ไม่จำเป็น ผมให้คำแนะนำที่ตรงไปตรงมา เหมาะสมกับงบประมาณและเป้าหมายของลูกค้า',
                 'color' => 'red', 'grad' => 'from-red-500 to-red-600', 'bg' => 'bg-red-50', 'border' => 'border-red-200'],
                ['icon' => 'clock', 'title' => 'ดูแลอย่างต่อเนื่อง',
                 'desc' => 'ความสัมพันธ์ไม่จบแค่การขาย ผมดูแลลูกค้าทุกคนอย่างต่อเนื่อง ตลอดอายุกรมธรรม์',
                 'color' => 'purple', 'grad' => 'from-violet-500 to-violet-600', 'bg' => 'bg-violet-50', 'border' => 'border-violet-200'],
            ];
            $topBorders = [
                'blue' => 'bg-gradient-to-r from-blue-400 to-blue-600',
                'green' => 'bg-gradient-to-r from-emerald-400 to-emerald-600',
                'red' => 'bg-gradient-to-r from-red-400 to-red-600',
                'purple' => 'bg-gradient-to-r from-violet-400 to-violet-600',
            ];
            foreach ($philosophies as $phi):
            ?>
            <div class="phil-card bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100 group hover:border-<?= $phi['color'] ?>-200 reveal">
                <!-- Top gradient bar -->
                <div class="h-1 rounded-full mb-6 <?= $topBorders[$phi['color']] ?> opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <!-- Icon -->
                <div class="w-14 h-14 rounded-2xl <?= $phi['bg'] ?> flex items-center justify-center mb-5 group-hover:scale-110 group-hover:rotate-[-5deg] transition-all duration-300">
                    <?php if ($phi['icon'] === 'ear'): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M6 8.5a6.5 6.5 0 1 1 13 0c0 6-3 8.5-3 8.5"/><path d="M10 18v2a2 2 0 0 0 2 2"/><path d="M12 14v-2a2 2 0 0 1 2-2"/></svg>
                    <?php elseif ($phi['icon'] === 'heart-handshake'): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><path d="M19 14c1.5-2.5 2-5 2-5"/><path d="M3 9c0 2.5 1 6 3 8"/><path d="M12 3c-1.5 2-4 3-6 3"/><path d="M6 6c-1.5 2-1.5 5-1.5 7"/><path d="M12 3c1.5 2 4 3 6 3"/><path d="M18 6c1.5 2 1.5 5 1.5 7"/><path d="M19 14c-1 3-3 6-7 7-4-1-6-4-7-7"/></svg>
                    <?php else: ?>
                    <i data-lucide="<?= $phi['icon'] ?>" class="w-7 h-7 <?= $phi['color'] === 'green' ? 'text-emerald-600' : ($phi['color'] === 'red' ? 'text-red-600' : ($phi['color'] === 'purple' ? 'text-violet-600' : 'text-blue-600')) ?>"></i>
                    <?php endif; ?>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-3 font-[Kanit]"><?= $phi['title'] ?></h3>
                <p class="text-sm text-gray-500 leading-relaxed"><?= $phi['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Core Value Quote -->
        <div class="mt-14 rounded-2xl relative overflow-hidden reveal">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800"></div>
            <div class="absolute inset-0 opacity-[0.08]" style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 28px 28px;"></div>
            <div class="relative p-8 lg:p-12 text-center text-white">
                <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="quote" class="w-6 h-6 text-blue-200"></i>
                </div>
                <blockquote class="text-2xl lg:text-3xl font-bold font-[Kanit] max-w-3xl mx-auto leading-snug">
                    "ผมไม่ได้ขายประกัน — ผมช่วยให้คุณและครอบครัวมีอนาคตที่มั่นคง"
                </blockquote>
                <div class="flex items-center justify-center gap-3 mt-4">
                    <span class="w-8 h-px bg-blue-400/50"></span>
                    <p class="text-blue-200 text-sm font-medium">ประกันจริงใจ by ปกป้อง | Insurance Advisor, Allianz Ayudhya</p>
                    <span class="w-8 h-px bg-blue-400/50"></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- SECTION 6: STATS — ตัวเลขที่สะท้อนความไว้วางใจ                -->
<!-- ============================================================ -->
<section class="py-16 lg:py-24 relative overflow-hidden" style="background: linear-gradient(135deg, #0B1628 0%, #0F2140 30%, #1A3A6B 60%, #0F2140 100%);">
    <div class="absolute inset-0 opacity-[0.06]" style="background-image: url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1920&q=80'); background-size: cover; background-position: center;"></div>
    <!-- Grid pattern -->
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.05) 1px, transparent 1px); background-size: 50px 50px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 lg:px-6">
        <!-- Section Header -->
        <div class="text-center mb-14 lg:mb-18">
            <span class="inline-flex items-center gap-2 bg-white/10 text-blue-300 text-sm font-medium px-5 py-1.5 rounded-full mb-4 reveal border border-white/10">
                <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                ตัวเลขบอกเล่าเรื่องราว
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white mb-4 font-[Kanit] reveal delay-100">ตัวเลขที่สะท้อนความไว้วางใจ</h2>
            <p class="text-blue-200/70 max-w-2xl mx-auto text-lg reveal delay-200">ทุกตัวเลขคือครอบครัวที่ไว้วางใจและเส้นชีวิตที่ได้รับการปกป้อง</p>
            <div class="w-20 h-1 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full mx-auto mt-5"></div>
        </div>

        <!-- Main Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 lg:gap-6">
            <?php
            $stats = [
                ['value' => 1000, 'suffix' => '+', 'label' => 'ครอบครัวที่ไว้วางใจ', 'icon' => 'users', 'border' => 'border-blue-400/30', 'glow' => 'shadow-blue-500/20'],
                ['value' => 10, 'suffix' => '+ ปี', 'label' => 'ประสบการณ์', 'icon' => 'clock', 'border' => 'border-emerald-400/30', 'glow' => 'shadow-emerald-500/20'],
                ['value' => 2500, 'suffix' => '+', 'label' => 'กรมธรรม์ที่ดูแล', 'icon' => 'file-text', 'border' => 'border-violet-400/30', 'glow' => 'shadow-violet-500/20'],
                ['value' => 98, 'suffix' => '%', 'label' => 'ความพึงพอใจลูกค้า', 'icon' => 'smile', 'border' => 'border-amber-400/30', 'glow' => 'shadow-amber-500/20'],
            ];
            foreach ($stats as $stat):
            ?>
            <div class="stat-card glass-card rounded-2xl p-6 lg:p-8 text-center group hover:bg-white/[0.10] transition-all duration-300 reveal <?= $stat['border'] ?> <?= $stat['glow'] ?>">
                <div class="w-12 h-12 rounded-xl bg-white/[0.08] flex items-center justify-center mx-auto mb-4 group-hover:scale-110 group-hover:bg-white/[0.15] transition-all duration-300">
                    <i data-lucide="<?= $stat['icon'] ?>" class="w-6 h-6 text-blue-300"></i>
                </div>
                <div class="stat-number text-4xl lg:text-5xl font-extrabold text-white mb-2 font-[Kanit] stat-glow" data-target="<?= $stat['value'] ?>" data-suffix="<?= $stat['suffix'] ?>">
                    0<?= $stat['suffix'] ?>
                </div>
                <p class="text-sm text-blue-200/70"><?= $stat['label'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Micro Stats -->
        <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-3 reveal">
            <?php
            $micros = [
                ['label' => 'จังหวัดที่ให้บริการ', 'value' => '10+ จังหวัด'],
                ['label' => 'เคลมสำเร็จ', 'value' => '100%'],
                ['label' => 'ช่องทางติดต่อ', 'value' => '5+ ช่องทาง'],
                ['label' => 'Allianz Partnership', 'value' => '10+ ปี'],
            ];
            foreach ($micros as $m):
            ?>
            <div class="glass-card rounded-xl px-4 py-3 text-center border border-white/[0.06] hover:bg-white/[0.08] transition-all">
                <p class="text-blue-300/60 text-xs"><?= $m['label'] ?></p>
                <p class="text-white font-bold font-[Kanit] text-base"><?= $m['value'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- SECTION 7: REVIEWS — เสียงจากลูกค้า                          -->
<!-- ============================================================ -->
<section class="py-16 lg:py-24 bg-gray-50 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#003781 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 lg:px-6">
        <!-- Section Header -->
        <div class="text-center mb-14 lg:mb-18">
            <span class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 text-sm font-medium px-5 py-1.5 rounded-full mb-4 reveal">
                <i data-lucide="messages-square" class="w-4 h-4"></i>
                ความประทับใจ
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4 font-[Kanit] reveal delay-100">เสียงจากลูกค้าของเรา</h2>
            <p class="text-gray-500 max-w-2xl mx-auto text-lg reveal delay-200">สิ่งที่ลูกค้าพูดถึงบริการของเรา — ความจริงใจที่ส่งต่อถึงกัน</p>
            <div class="section-divider mt-5"></div>
        </div>

        <!-- Testimonials Carousel -->
        <div class="review-scroll flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory reveal">
            <?php
            $testimonials = [
                [
                    'name' => 'คุณสมชาย ใจดี',
                    'role' => 'ลูกค้าประกันชีวิต',
                    'text' => 'ปกป้องเป็นที่ปรึกษาที่ใส่ใจมาก ตั้งแต่ครั้งแรกที่คุยกัน เค้าฟังความต้องการของเราอย่างละเอียด แนะนำแผนที่เหมาะกับเราจริงๆ ไม่ใช่แค่ขายของอย่างเดียว รู้สึกอุ่นใจที่มีเค้าดูแล',
                    'rating' => 5,
                    'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face&q=80'
                ],
                [
                    'name' => 'คุณสาวิกา รักครอบครัว',
                    'role' => 'ลูกค้าประกันสุขภาพเหมาจ่าย',
                    'text' => 'ตอนแรกกังวลเรื่องค่าใช้จ่าย แต่ปกป้องช่วยเลือกแผนที่ครอบคลุมและคุ้มค่าที่สุด พอเราต้องเคลมจริงๆ เค้าก็จัดการให้ทุกอย่าง รวดเร็วมาก ขอบคุณจริงๆค่ะ',
                    'rating' => 5,
                    'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80&h=80&fit=crop&crop=face&q=80'
                ],
                [
                    'name' => 'คุณอนุชา วงศ์เจริญ',
                    'role' => 'ลูกค้าประกันออมทรัพย์',
                    'text' => 'ผมชอบที่ปกป้องอธิบายรายละเอียดทุกอย่างชัดเจน ไม่มีคำพูดกำกวม ตอนตัดสินใจซื้อประกันออมทรัพย์เค้าทำตารางเปรียบเทียบให้ดูหลายๆ แผน ทำให้เลือกได้ง่ายขึ้นมาก',
                    'rating' => 5,
                    'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=80&h=80&fit=crop&crop=face&q=80'
                ],
                [
                    'name' => 'คุณประภาพร สุขสบาย',
                    'role' => 'ลูกค้าประกันอุบัติเหตุ',
                    'text' => 'เค้าไม่ทิ้งลูกค้าหลังการขายเลยนะคะ มีอะไรสงสัยก็ทักLINEไปได้ตลอด ให้คำปรึกษาจริงใจ ไม่ได้มองแต่ยอดขายอย่างเดียว ขอชื่นชมจริงๆ',
                    'rating' => 5,
                    'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=80&h=80&fit=crop&crop=face&q=80'
                ],
                [
                    'name' => 'คุณกิตติพงษ์ สุทธิรักษ์',
                    'role' => 'ลูกค้าประกันชีวิต+สุขภาพ',
                    'text' => 'เพื่อนแนะนำมาครับ ตอนแรกก็ลังเล แต่พอได้คุยกับปกป้องแล้วรู้สึกไว้ใจได้ เค้าตั้งใจฟังและแนะนำแบบไม่กดดัน สุดท้ายได้ประกันที่ใช่สำหรับครอบครัว',
                    'rating' => 5,
                    'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&crop=face&q=80'
                ],
                [
                    'name' => 'คุณรุ่งทิพย์ วัฒนา',
                    'role' => 'ลูกค้าประกันโรคร้ายแรง',
                    'text' => 'รู้สึกโชคดีที่มีปกป้องเป็นที่ปรึกษา ตอนที่ตรวจเจอโรค เค้าช่วยดำเนินการเรื่องเคลมให้ทุกอย่าง ไม่ต้องเร่งรีบหรือกังวลอะไรเลย ขอบคุณมากนะคะ',
                    'rating' => 5,
                    'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=80&h=80&fit=crop&crop=face&q=80'
                ],
            ];
            foreach ($testimonials as $t):
            ?>
            <div class="review-card snap-center shrink-0 w-[90vw] sm:w-[430px] lg:w-[460px] bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100 card-hover">
                <!-- Quote icon -->
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-4">
                    <i data-lucide="quote" class="w-5 h-5 text-blue-400"></i>
                </div>

                <p class="text-gray-600 leading-relaxed mb-6 text-sm lg:text-base line-clamp-4">
                    "<?= $t['text'] ?>"
                </p>

                <!-- Rating -->
                <div class="flex items-center gap-0.5 mb-4">
                    <?php for ($r = 0; $r < 5; $r++): ?>
                    <i data-lucide="star" class="w-4 h-4 <?= $r < $t['rating'] ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200' ?>"></i>
                    <?php endfor; ?>
                </div>

                <!-- Divider -->
                <div class="gradient-line mb-4"></div>

                <!-- Author -->
                <div class="flex items-center gap-3">
                    <img src="<?= $t['avatar'] ?>" alt="<?= $t['name'] ?>"
                         class="w-11 h-11 rounded-full object-cover ring-2 ring-gray-100"
                         loading="lazy"
                         onerror="this.style.display='none'">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm"><?= $t['name'] ?></p>
                        <p class="text-xs text-gray-400"><?= $t['role'] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Carousel Indicators -->
        <div class="flex items-center justify-center gap-2 mt-6 reveal">
            <span class="text-xs text-gray-400 hidden sm:inline">←</span>
            <div class="flex gap-1.5">
                <?php foreach ($testimonials as $i => $t): ?>
                <button onclick="document.querySelector('.review-scroll').scrollTo({left: document.querySelector('.review-scroll').querySelectorAll('.review-card')[<?= $i ?>].offsetLeft - 24, behavior: 'smooth'})"
                        class="w-2 h-2 rounded-full transition-all duration-300 <?= $i === 0 ? 'bg-blue-600 w-6' : 'bg-gray-300 hover:bg-gray-400' ?>"></button>
                <?php endforeach; ?>
            </div>
            <span class="text-xs text-gray-400 hidden sm:inline">→</span>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- SECTION 8: CTA — พร้อมดูแลคุณและครอบครัว                     -->
<!-- ============================================================ -->
<section class="py-16 lg:py-24 cta-premium relative">
    <!-- Decorative circles -->
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-green-500/10 rounded-full blur-3xl"></div>

    <div class="relative z-[1] max-w-4xl mx-auto px-4 lg:px-6 text-center">
        <div class="reveal">
            <span class="inline-flex items-center gap-2 glass-card rounded-full text-green-300 text-sm font-medium px-5 py-2 mb-6 border border-green-400/20 hover:bg-white/[0.12] transition-all">
                <img src="/assets/icon/line.svg" class="w-4 h-4">
                ปรึกษาวันนี้ รับคำแนะนำดีๆ ฟรี!
            </span>
        </div>

        <h2 class="text-3xl lg:text-5xl font-extrabold text-white mb-4 font-[Kanit] reveal delay-100">
            พร้อมดูแลคุณและครอบครัว
        </h2>

        <p class="text-blue-200/80 text-lg lg:text-xl max-w-2xl mx-auto mb-8 reveal delay-200">
            ไม่ว่าคุณจะสนใจประกันชีวิต สุขภาพ ออมทรัพย์ หรือวางแผนการเงิน
            ผมพร้อมให้คำปรึกษาฟรี ไม่มีค่าใช้จ่าย ไม่มีข้อผูกมัด
        </p>

        <!-- Benefits -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto mb-10 reveal delay-300">
            <div class="glass-card rounded-xl px-4 py-4 border border-white/10 hover:bg-white/[0.10] transition-all">
                <i data-lucide="clock" class="w-5 h-5 text-green-400 mx-auto mb-2"></i>
                <p class="text-white text-sm font-medium">ปรึกษาฟรี ไม่มีค่าใช้จ่าย</p>
            </div>
            <div class="glass-card rounded-xl px-4 py-4 border border-white/10 hover:bg-white/[0.10] transition-all">
                <i data-lucide="shield" class="w-5 h-5 text-green-400 mx-auto mb-2"></i>
                <p class="text-white text-sm font-medium">ข้อมูลถูกต้อง ครบถ้วน</p>
            </div>
            <div class="glass-card rounded-xl px-4 py-4 border border-white/10 hover:bg-white/[0.10] transition-all">
                <i data-lucide="heart" class="w-5 h-5 text-green-400 mx-auto mb-2"></i>
                <p class="text-white text-sm font-medium">ไม่มีข้อผูกมัดใดๆ</p>
            </div>
        </div>

        <!-- Main CTAs -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center reveal delay-400">
            <a href="https://lin.ee/QngrNQ3" target="_blank"
               class="group inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-400 to-green-500 hover:from-green-500 hover:to-green-600 text-white font-bold px-8 py-4 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 text-lg relative overflow-hidden">
                <span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                <img src="/assets/icon/line.svg" class="w-6 h-6 relative z-[1]">
                <span class="relative z-[1]">ปรึกษาฟรีทาง LINE</span>
            </a>
            <a href="tel:092-515-9991"
               class="group inline-flex items-center justify-center gap-2 glass-card hover:bg-white/15 text-white border border-white/20 px-8 py-4 rounded-xl transition-all duration-300 text-lg">
                <i data-lucide="phone" class="w-6 h-6 group-hover:scale-110 transition-transform"></i>
                โทร 092-515-9991
            </a>
        </div>

        <!-- Social links -->
        <p class="text-blue-300/60 text-sm mt-6 reveal delay-500">
            หรือติดต่อผ่าน <a href="https://facebook.com/pp.insure168" target="_blank" class="text-blue-300 hover:text-white underline underline-offset-2">Facebook pp.insure168</a>
            <span class="mx-2">·</span>
            ตอบกลับภายใน 15 นาที ในเวลาทำการ
        </p>

        <!-- LINE QR -->
        <div class="mt-10 reveal delay-600">
            <div class="inline-block glass-card rounded-2xl p-6 border border-white/10 hover:bg-white/[0.08] transition-all">
                <p class="text-blue-200 text-sm mb-3 flex items-center justify-center gap-2">
                    <i data-lucide="smartphone" class="w-4 h-4"></i>
                    แสกน QR เพื่อแอด LINE
                </p>
                <img src="/assets/qrcode/qrcode-line.jpg" alt="LINE QR Code"
                     class="w-32 h-32 mx-auto rounded-xl shadow-lg"
                     loading="lazy"
                     onerror="this.outerHTML='<div class=\\'text-blue-300 text-sm py-4\\'>LINE ID: <strong class=\\'text-white\\'>@945ampel</strong></div>'">
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
// ============================================
// Counter Animation (IntersectionObserver)
// ============================================
document.addEventListener('DOMContentLoaded', function(){
    var counters = document.querySelectorAll('.stat-number');
    var observed = new WeakSet();
    var counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting && !observed.has(entry.target)) {
                observed.add(entry.target);
                var el = entry.target;
                var target = parseInt(el.dataset.target) || 0;
                var suffix = el.dataset.suffix || '';
                var duration = 1800;
                var start = performance.now();
                function update(now) {
                    var progress = Math.min((now - start) / duration, 1);
                    // Cubic ease-out
                    var eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target).toLocaleString() + suffix;
                    if (progress < 1) requestAnimationFrame(update);
                }
                requestAnimationFrame(update);
            }
        });
    }, {threshold: 0.4});
    counters.forEach(function(c) { counterObserver.observe(c); });
});

// ============================================
// Enhanced Scroll Reveal Animations
// ============================================
document.addEventListener('DOMContentLoaded', function(){
    var revealSelectors = ['.reveal', '.reveal-left', '.reveal-right', '.reveal-scale'];
    var allReveals = [];
    revealSelectors.forEach(function(sel) {
        document.querySelectorAll(sel).forEach(function(el) { allReveals.push(el); });
    });

    var revealObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            if (e.isIntersecting) {
                // Extract delay from class or data attribute
                var delay = 0;
                var match = e.target.className.match(/delay-(\d+)/);
                if (match) delay = parseInt(match[1]);
                setTimeout(function() {
                    e.target.classList.add('reveal-visible');
                }, delay);
                revealObserver.unobserve(e.target);
            }
        });
    }, {threshold: 0.08, rootMargin: '0px 0px -40px 0px'});

    allReveals.forEach(function(r) { revealObserver.observe(r); });
});

// ============================================
// Testimonial Scroll Spy (update indicators)
// ============================================
document.addEventListener('DOMContentLoaded', function(){
    var scrollContainer = document.querySelector('.review-scroll');
    if (!scrollContainer) return;
    var dots = document.querySelectorAll('[onclick*="review-scroll"]');
    scrollContainer.addEventListener('scroll', function() {
        var cards = scrollContainer.querySelectorAll('.review-card');
        var scrollLeft = scrollContainer.scrollLeft;
        var containerWidth = scrollContainer.clientWidth;
        var activeIndex = 0;
        cards.forEach(function(card, i) {
            var cardLeft = card.offsetLeft - 24;
            var cardRight = cardLeft + card.offsetWidth;
            var center = scrollLeft + containerWidth / 2;
            if (center >= cardLeft && center <= cardRight) {
                activeIndex = i;
            }
        });
        if (dots.length > 0) {
            dots.forEach(function(dot, i) {
                if (i === activeIndex) {
                    dot.classList.remove('bg-gray-300', 'hover:bg-gray-400');
                    dot.classList.add('bg-blue-600', 'w-6');
                } else {
                    dot.classList.remove('bg-blue-600', 'w-6');
                    dot.classList.add('bg-gray-300', 'hover:bg-gray-400');
                }
            });
        }
    });
});
</script>

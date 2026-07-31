<?php $pageTitle = $pageTitle ?? 'ที่ปรึกษาประกันชีวิตและการเงิน';
$currentPage = basename($_SERVER['SCRIPT_NAME']);
function navActive($page, $current) {
    return $page === $current ? 'text-brand-navy font-bold border-b-2 border-brand-navy' : 'brand-text hover:text-brand-navy';
}
function mobActive($page, $current) {
    return $page === $current ? 'text-brand-navy font-bold border-l-2 border-brand-navy pl-1.5' : 'text-brand-text pl-2';
}

// ═══ ตั้งค่าจาก DB (fallback: ค่า default ถ้า DB ไม่พร้อม) ═══
$SITE = [
    'title'       => 'ที่ปรึกษาประกันชีวิตและการเงิน',
    'description' => 'ที่ปรึกษาประกันชีวิตและการเงิน Allianz Ayudhya วางแผนอนาคตอย่างมั่นใจ ครบทุกความคุ้มครอง ด้วยประสบการณ์และความจริงใจ',
    'keywords'    => 'ประกันชีวิต, ประกันสุขภาพ, ประกันโรคร้ายแรง, ที่ปรึกษาประกัน, Allianz',
    'og_image'    => '/assets/image/hero-portrait.webp',
    'phone'       => '092-515-9991',
    'line_id'     => '@945ampel',
    'line_url'    => 'https://line.me/R/ti/p/@945ampel',
    'facebook_url'=> 'https://www.facebook.com/pp.insure168',
    'youtube_url' => 'https://www.youtube.com/',
    'instagram_url'=> 'https://www.instagram.com/',
    'tiktok_url'  => 'https://www.tiktok.com/',
    'address'     => 'กรุงเทพมหานคร ประเทศไทย',
];
try {
    $__dbFile = __DIR__ . '/db.php';
    if (file_exists($__dbFile)) {
        require_once $__dbFile;
        $__stmt = getDB()->query("SELECT setting_key, setting_value FROM settings");
        foreach ($__stmt as $__row) {
            if (isset($SITE[$__row['setting_key']])) {
                $SITE[$__row['setting_key']] = (string)$__row['setting_value'];
            }
        }
    }
} catch (Throwable $e) {
    // DB ไม่พร้อม — ใช้ค่า default ต่อ
}
$__canonical = 'https://prakanhub.com' . ($_SERVER['REQUEST_URI'] ?? '/');
$__pageDesc = $pageDesc ?? $SITE['description'];
?>
<!DOCTYPE html>
<html lang="th" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | <?= htmlspecialchars($SITE['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($__pageDesc) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($SITE['keywords']) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($__canonical) ?>">

    <!-- Open Graph / Social -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="ประกันจริงใจ by ปกป้อง">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?> | <?= htmlspecialchars($SITE['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($__pageDesc) ?>">
    <meta property="og:image" content="https://prakanhub.com<?= htmlspecialchars($SITE['og_image']) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($__canonical) ?>">
    <meta property="og:locale" content="th_TH">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle) ?> | <?= htmlspecialchars($SITE['title']) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($__pageDesc) ?>">
    <meta name="twitter:image" content="https://prakanhub.com<?= htmlspecialchars($SITE['og_image']) ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#003781',
                            navyHover: '#00265A',
                            light: '#EEF4F9',
                            green: '#00C300',
                            greenHover: '#00A000',
                            text: '#1E293B',
                            gray: '#64748B'
                        }
                    },
                    fontFamily: {
                        sans: ['Prompt', 'sans-serif'],
                        signature: ['Dancing Script', 'cursive'],
                    },
                    boxShadow: {
                        'soft': '0 4px 25px rgba(0, 0, 0, 0.05)',
                        'card': '0 10px 30px -5px rgba(0, 55, 129, 0.08)',
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@0.379.0"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        /* ─── Nav Hover Underline Animation ─── */
        .nav-link {
            position: relative;
            transition: color 0.2s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 0;
            height: 2px;
            background: #003781;
            transition: width 0.25s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .nav-link.active-nav::after {
            width: 100%;
        }
        /* ─── Mobile menu item hover ─── */
        .mob-link {
            transition: all 0.2s ease;
            border-left: 2px solid transparent;
        }
        .mob-link:hover {
            background: #EEF4F9;
            border-left-color: #003781;
            padding-left: 10px;
        }
    </style>

    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "ประกันจริงใจ by ปกป้อง - Allianz Ayudhya",
        "description": <?= json_encode($SITE['description'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
        "image": "https://prakanhub.com<?= htmlspecialchars($SITE['og_image']) ?>",
        "telephone": "<?= htmlspecialchars($SITE['phone']) ?>",
        "url": "https://prakanhub.com",
        "sameAs": [
            <?= json_encode($SITE['facebook_url'], JSON_UNESCAPED_SLASHES) ?>,
            <?= json_encode($SITE['line_url'], JSON_UNESCAPED_SLASHES) ?>,
            <?= json_encode($SITE['youtube_url'], JSON_UNESCAPED_SLASHES) ?>,
            <?= json_encode($SITE['instagram_url'], JSON_UNESCAPED_SLASHES) ?>,
            <?= json_encode($SITE['tiktok_url'], JSON_UNESCAPED_SLASHES) ?>
        ],
        "address": {
            "@type": "PostalAddress",
            "streetAddress": <?= json_encode($SITE['address'], JSON_UNESCAPED_UNICODE) ?>,
            "addressCountry": "TH"
        },
        "knowsAbout": ["ประกันชีวิต", "ประกันสุขภาพ", "ประกันภัยทั่วไป", "การวางแผนการเงิน"],
        "priceRange": "฿"
    }
    </script>

</head>
<body class="relative">

<!-- Cookies Consent Banner -->
<div id="cookies-banner" class="fixed bottom-0 left-0 right-0 bg-brand-navy text-white z-[9999] shadow-2xl" style="display:none;">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-xs text-blue-200">เว็บไซต์นี้ใช้คุกกี้เพื่อเพิ่มประสิทธิภาพการใช้งานของคุณ <a href="/privacy.php" class="text-white underline hover:no-underline">อ่านนโยบายความเป็นส่วนตัว</a></p>
        <div class="flex gap-2 shrink-0">
            <button onclick="document.getElementById('cookies-banner').style.display='none';localStorage.setItem('cookies_accepted','1')" class="bg-white text-brand-navy text-xs font-bold px-5 py-2 rounded-full hover:bg-gray-100 transition">ยอมรับ</button>
        </div>
    </div>
</div>
<script>if(!localStorage.getItem('cookies_accepted'))document.getElementById('cookies-banner').style.display='';
function toggleMenu(){var m=document.getElementById('mobile-menu'),o=document.getElementById('mobile-menu-overlay');if(!m||!o)return;var open=m.classList.contains('menu-open');if(open){m.classList.remove('menu-open');o.classList.remove('opacity-100');setTimeout(function(){o.classList.add('hidden')},300)}else{m.classList.add('menu-open');o.classList.remove('hidden');setTimeout(function(){o.classList.add('opacity-100')},10)}}
function closeMenu(){toggleMenu()}
</script>

    <!-- HEADER -->
    <header class="bg-white sticky top-0 z-50 shadow-sm border-b border-gray-100">
        <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-3 flex justify-between items-center">
            
            <!-- Logo -->
            <a href="/index.php" class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="flex gap-0.5 mt-1">
                        <div class="w-1.5 h-6 bg-brand-navy rounded-t-full"></div>
                        <div class="w-1.5 h-8 bg-brand-navy rounded-t-full -mt-2"></div>
                        <div class="w-1.5 h-6 bg-brand-navy rounded-t-full"></div>
                    </div>
                    <div class="flex flex-col leading-none text-brand-navy">
                        <span class="font-bold text-xl tracking-tight">Allianz</span>
                        <span class="font-medium text-[11px] tracking-[0.2em] uppercase">Ayudhya</span>
                    </div>
                </div>
            </a>

            <!-- Desktop Menu -->
            <nav class="hidden lg:flex items-center gap-8 font-medium text-sm">
                <a href="/index.php" class="nav-link <?= navActive('index.php', $currentPage) ?> flex items-center gap-1.5 pb-1"><i data-lucide="home" class="w-4 h-4"></i> หน้าแรก</a>
                <a href="/about.php" class="nav-link <?= navActive('about.php', $currentPage) ?> flex items-center gap-1.5 pb-1 hover:text-brand-navy transition"><i data-lucide="user" class="w-4 h-4"></i> เกี่ยวกับผม</a>
                <div class="relative group">
                    <a href="#" class="nav-link flex items-center gap-1 pb-1 hover:text-brand-navy transition cursor-pointer">ประกันของเรา <i data-lucide="chevron-down" class="w-4 h-4 transition-transform group-hover:rotate-180"></i></a>
                    <!-- Dropdown -->
                    <div class="absolute top-full left-0 mt-2 w-[650px] bg-white rounded-2xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 p-6">
                        <div class="grid grid-cols-5 gap-6">
                            <div class="col-span-3">
                                <h4 class="font-bold text-brand-navy text-sm mb-3 flex items-center gap-2"><i data-lucide="user" class="w-4 h-4"></i> สำหรับบุคคลทั่วไป</h4>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-[11px] font-bold text-brand-gray uppercase tracking-wider mb-2">ประกันหลัก</p>
                                        <div class="space-y-2 pl-1">
                                            <a href="/life.php" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-navy transition"><i data-lucide="heart" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันชีวิต</a>
                                            <a href="/life.php" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-navy transition"><i data-lucide="landmark" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันเงินออม</a>
                                            <a href="/life.php" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-navy transition"><i data-lucide="home" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันเกษียณ</a>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-bold text-brand-gray uppercase tracking-wider mb-2">ประกันเสริม</p>
                                        <div class="space-y-2 pl-1">
                                            <a href="/health.php" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-navy transition"><i data-lucide="activity" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันสุขภาพ</a>
                                            <a href="/health.php" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-navy transition"><i data-lucide="shield-alert" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันโรคร้ายแรง</a>
                                            <a href="/health.php" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-navy transition"><i data-lucide="dollar-sign" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันชดเชยรายได้</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2 border-l border-gray-100 pl-6">
                                <h4 class="font-bold text-brand-navy text-sm mb-3 flex items-center gap-2"><i data-lucide="building" class="w-4 h-4"></i> สำหรับองค์กร</h4>
                                <div class="space-y-2 pl-1">
                                    <a href="/general.php" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-navy transition"><i data-lucide="users" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันกลุ่ม</a>
                                    <a href="/general.php" class="flex items-center gap-2 text-sm text-brand-text hover:text-brand-navy transition"><i data-lucide="briefcase" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันนิติบุคคล</a>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 text-center">
                            <a href="/life.php" class="text-xs font-medium text-brand-navy hover:underline flex items-center justify-center gap-1">ดูประกันทั้งหมด <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                        </div>
                    </div>
                </div>
                <a href="/career.php" class="nav-link <?= navActive('career.php', $currentPage) ?> flex items-center gap-1.5 pb-1 hover:text-brand-navy transition"><i data-lucide="handshake" class="w-4 h-4"></i> ร่วมงานกับเรา</a>
                <a href="/seminar.php" class="nav-link <?= navActive('seminar.php', $currentPage) ?> flex items-center gap-1.5 pb-1 hover:text-brand-navy transition"><i data-lucide="presentation" class="w-4 h-4"></i> สัมมนา & คอร์ส</a>
                <a href="/articles.php" class="nav-link <?= navActive('articles.php', $currentPage) ?> flex items-center gap-1.5 pb-1 hover:text-brand-navy transition"><i data-lucide="file-text" class="w-4 h-4"></i> บทความ</a>
                <a href="/contact.php" class="nav-link <?= navActive('contact.php', $currentPage) ?> flex items-center gap-1.5 pb-1 hover:text-brand-navy transition"><i data-lucide="phone" class="w-4 h-4"></i> ติดต่อเรา</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-4">
                <button class="hidden md:flex items-center gap-2 bg-brand-navy hover:bg-brand-navyHover text-white px-5 py-2.5 rounded-full text-sm font-semibold transition shadow-md">
                    <img src="/assets/icon/line.svg" class="w-4 h-4" alt="LINE"> ปรึกษาฟรี
                </button>
                <button id="hamburger-btn" class="lg:hidden text-brand-navy p-2" onclick="toggleMenu()"><i data-lucide="menu" class="w-7 h-7"></i></button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-40 hidden opacity-0 transition-opacity"></div>
        <div id="mobile-menu" class="fixed top-0 right-0 w-72 h-full bg-white z-50 transform translate-x-full shadow-2xl flex flex-col">
            <div class="p-4 border-b flex justify-between items-center bg-brand-navy text-white">
                <span class="font-signature text-2xl">ประกันจริงใจ by ปกป้อง</span>
                <button id="close-menu-btn" onclick="toggleMenu()"><i data-lucide="x" class="w-6 h-6"></i></button>
            </div>
            <div class="p-4 flex flex-col gap-3 text-sm font-medium overflow-y-auto pb-20">
                <a href="/index.php" class="mob-link <?= mobActive('index.php', $currentPage) ?> flex items-center gap-2 py-2 rounded-lg"><i data-lucide="home" class="w-4 h-4"></i> หน้าแรก</a>
                <a href="/about.php" class="mob-link <?= mobActive('about.php', $currentPage) ?> flex items-center gap-2 py-2 rounded-lg"><i data-lucide="user" class="w-4 h-4"></i> เกี่ยวกับผม</a>
                <div class="mob-sub">
                    <button onclick="this.parentElement.classList.toggle('open')" class="mob-link flex items-center justify-between w-full gap-2 py-2 rounded-lg text-left cursor-pointer pl-2">
                        <span class="flex items-center gap-2">ประกันของเรา</span>
                        <i data-lucide="chevron-down" class="mob-chev w-4 h-4 transition-transform"></i>
                    </button>
                    <div class="mob-sub-content mt-3 space-y-3 pl-2 border-l-2 border-gray-100 ml-1">
                        <div>
                            <p class="text-xs font-bold text-brand-gray uppercase tracking-wider mb-2 flex items-center gap-1.5 ml-1"><i data-lucide="user" class="w-3 h-3"></i> สำหรับบุคคลทั่วไป</p>
                            <div class="space-y-2 pl-3 mb-3">
                                <p class="text-[10px] font-bold text-brand-gray/70 uppercase tracking-wider">ประกันหลัก</p>
                                <div class="space-y-1.5 pl-2">
                                    <a href="/life.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="heart" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันชีวิต</a>
                                    <a href="/life.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="landmark" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันเงินออม</a>
                                    <a href="/life.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="home" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันเกษียณ</a>
                                </div>
                                <p class="text-[10px] font-bold text-brand-gray/70 uppercase tracking-wider mt-2">ประกันเสริม</p>
                                <div class="space-y-1.5 pl-2">
                                    <a href="/health.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="activity" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันสุขภาพ</a>
                                    <a href="/health.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="shield-alert" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันโรคร้ายแรง</a>
                                    <a href="/health.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="dollar-sign" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันชดเชยรายได้</a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-brand-gray uppercase tracking-wider mb-2 flex items-center gap-1.5 ml-1"><i data-lucide="building" class="w-3 h-3"></i> สำหรับองค์กร</p>
                            <div class="space-y-1.5 pl-3">
                                <a href="/general.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="users" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันกลุ่ม</a>
                                <a href="/general.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="briefcase" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันนิติบุคคล</a>
                                <a href="/general.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="car" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันรถยนต์</a>
                                <a href="/general.php" class="flex items-center gap-2 text-sm hover:text-brand-navy transition"><i data-lucide="plane" class="w-3.5 h-3.5 text-brand-navy shrink-0"></i> ประกันเดินทาง</a>
                            </div>
                        </div>
                        <a href="/life.php" class="flex items-center gap-1 text-xs font-medium text-brand-navy hover:underline pt-1 pl-1">ดูประกันทั้งหมด <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
                    </div>
                </div>
                <a href="/career.php" class="mob-link <?= mobActive('career.php', $currentPage) ?> flex items-center gap-2 py-2 rounded-lg"><i data-lucide="handshake" class="w-4 h-4"></i> ร่วมงานกับเรา</a>
                <a href="/seminar.php" class="mob-link <?= mobActive('seminar.php', $currentPage) ?> flex items-center gap-2 py-2 rounded-lg"><i data-lucide="presentation" class="w-4 h-4"></i> สัมมนา & คอร์ส</a>
                <a href="/articles.php" class="mob-link <?= mobActive('articles.php', $currentPage) ?> flex items-center gap-2 py-2 rounded-lg"><i data-lucide="file-text" class="w-4 h-4"></i> บทความ</a>
                <a href="/contact.php" class="mob-link <?= mobActive('contact.php', $currentPage) ?> flex items-center gap-2 py-2 rounded-lg"><i data-lucide="phone" class="w-4 h-4"></i> ติดต่อเรา</a>
                <hr>
                <button class="flex items-center justify-center gap-2 bg-brand-navy text-white px-5 py-3 rounded-xl text-sm font-semibold w-full mt-2">
                    <img src="/assets/icon/line.svg" class="w-4 h-4" alt="LINE"> ปรึกษาฟรี
                </button>
            </div>
        </div>
    </header>

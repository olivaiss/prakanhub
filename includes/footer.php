<?php
// ลิงก์ฟอร์มทำประกัน + ระบบสมาชิก — ใช้ได้ทั้ง main domain และ subdomain form.
$isFormSubdomain = (strpos($_SERVER['HTTP_HOST'] ?? '', 'form.') === 0);
$formUrl = $isFormSubdomain ? '/index.php' : '/form/';
$memberUrl = $isFormSubdomain ? 'https://prakanhub.com/member/' : '/member/';

// ข้อมูลติดต่อจาก DB (ตั้งไว้ใน header.php แล้ว — fallback ถ้าเรียก footer โดดๆ)
if (!isset($SITE)) {
    $SITE = [
        'phone' => '092-515-9991', 'line_id' => '@945ampel', 'line_url' => 'https://lin.ee/QngrNQ3',
        'facebook_url' => 'https://facebook.com/pp.insure168', 'youtube_url' => 'https://youtube.com',
        'instagram_url' => 'https://instagram.com', 'tiktok_url' => 'https://tiktok.com',
        'address' => 'กรุงเทพมหานคร ประเทศไทย',
    ];
}
$__sitePhone = $SITE['phone'] ?? '092-515-9991';
$__siteLineId = $SITE['line_id'] ?? '@945ampel';
$__siteLineUrl = $SITE['line_url'] ?? 'https://lin.ee/QngrNQ3';
$__siteFb = $SITE['facebook_url'] ?? '#';
$__siteYt = $SITE['youtube_url'] ?? '#';
$__siteIg = $SITE['instagram_url'] ?? '#';
$__siteTt = $SITE['tiktok_url'] ?? '#';
?>
    <!-- 8. PRE-FOOTER CONTACT BAR -->
    <section class="bg-brand-light border-y border-gray-200">
        <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-6 flex flex-col lg:flex-row items-center justify-between gap-6">
            
            <div class="text-center lg:text-left">
                <h3 class="text-lg font-bold text-brand-navy mb-1">ปรึกษาและวางแผนการเงินกับเรา</h3>
                <p class="text-xs text-brand-gray">ยินดีให้คำแนะนำ ฟรี! ไม่มีค่าใช้จ่าย</p>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-6 md:gap-12">
                <a href="tel:<?= htmlspecialchars($__sitePhone) ?>" class="flex items-center gap-3 text-brand-text hover:text-brand-navy transition group">
                    <i data-lucide="phone" class="w-6 h-6 group-hover:scale-110 transition"></i>
                    <span class="font-bold text-lg"><?= htmlspecialchars($__sitePhone) ?></span>
                </a>
                <a href="<?= htmlspecialchars($__siteLineUrl) ?>" target="_blank" class="flex items-center gap-3 text-brand-text hover:text-brand-green transition group">
                    <img src="/assets/icon/line.svg" class="w-7 h-7 hover:scale-110 transition" alt="LINE">
                    <span class="font-bold text-lg"><?= htmlspecialchars($__siteLineId) ?></span>
                </a>
                <a href="<?= htmlspecialchars($__siteFb) ?>" target="_blank" class="flex items-center gap-3 text-brand-text hover:text-blue-600 transition group">
                    <img src="/assets/icon/facebook.svg" class="w-7 h-7 hover:scale-110 transition" alt="Facebook">
                    <span class="font-bold text-lg">pp.insure168</span>
                </a>
            </div>

            <div class="flex items-center gap-4 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                <!-- QR Code LINE -->
                <img src="/assets/qrcode/qrcode-line.jpg" class="w-12 h-12 rounded" alt="QR LINE">
                <div class="leading-tight">
                    <div class="text-xs font-bold text-brand-navy">สแกนเพิ่มเพื่อน</div>
                    <div class="text-[10px] text-brand-gray">รับคำปรึกษาฟรี</div>
                </div>
                <img src="/assets/icon/line.svg" class="w-6 h-6 ml-2" alt="LINE">
            </div>

        </div>
    </section>

    <!-- 9. FOOTER -->
    <footer class="bg-brand-navy text-white">
        <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-8 flex flex-col md:flex-row justify-between items-center gap-6">
            
            <!-- Logos -->
            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 border-b sm:border-b-0 sm:border-r border-white/20 pb-4 sm:pb-0 pr-0 sm:pr-6">
                <!-- Allianz -->
                <div class="flex items-center gap-1.5">
                    <div class="flex gap-[1px] mt-0.5">
                        <div class="w-1 h-4 bg-white rounded-t-full"></div>
                        <div class="w-1 h-5 bg-white rounded-t-full -mt-1"></div>
                        <div class="w-1 h-4 bg-white rounded-t-full"></div>
                    </div>
                    <div class="flex flex-col leading-none text-white">
                        <span class="font-bold text-sm tracking-tight">Allianz</span>
                        <span class="font-medium text-[7px] tracking-[0.2em] uppercase">Ayudhya</span>
                    </div>
                </div>
                <!-- ประกันจริงใจ by ปกป้อง -->
                <div class="flex flex-col leading-none items-center sm:items-start">
                    <span class="font-signature text-xl font-bold -mb-1">ประกันจริงใจ by ปกป้อง</span>
                    <span class="text-[7px] font-medium text-blue-200 tracking-wider">Insurance Advisor</span>
                </div>
            </div>

            <!-- Links & Copyright -->
            <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left">
                <div class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-1.5 text-xs font-medium text-blue-200 mb-2">
                    <a href="/index.php" class="hover:text-white">หน้าแรก</a> <span class="text-white/20">|</span>
                    <a href="/about.php" class="hover:text-white">เกี่ยวกับผม</a> <span class="text-white/20">|</span>
                    <a href="/category.php?slug=life" class="hover:text-white">ประกันของเรา</a> <span class="text-white/20">|</span>
                    <a href="<?= $formUrl ?>" class="hover:text-white">แบบฟอร์มทำประกัน</a> <span class="text-white/20">|</span>
                    <a href="<?= $memberUrl ?>" class="hover:text-white">ระบบสมาชิก</a> <span class="text-white/20">|</span>
                    <a href="/career.php" class="hover:text-white">ร่วมงานกับเรา</a> <span class="text-white/20">|</span>
                    <a href="/seminar.php" class="hover:text-white">สัมมนา & คอร์ส</a> <span class="text-white/20">|</span>
                    <a href="/articles.php" class="hover:text-white">บทความ</a> <span class="text-white/20">|</span>
                    <a href="/contact.php" class="hover:text-white">ติดต่อเรา</a>
                </div>
                <div class="text-[10px] text-blue-300 leading-relaxed">
                    &copy; 2026 NextGen Digital Solutions. All rights reserved.<br>
                    <a href="/privacy.php" class="hover:text-white underline">นโยบายความเป็นส่วนตัว</a> · <a href="/terms.php" class="hover:text-white underline">ข้อกำหนดและเงื่อนไข</a> · <a href="/claim.php" class="hover:text-white underline">ขั้นตอนการเคลม</a>
                </div>
                <div class="mt-1 text-[10px] text-blue-300 leading-relaxed">
                    เว็บไซต์นี้เป็นของตัวแทนประกันชีวิต Allianz Ayudhya
                </div>
            </div>

            <!-- Social Icons -->
            <div class="flex gap-2 pb-4 md:pb-0">
                <a href="<?= htmlspecialchars($__siteFb) ?>" target="_blank" rel="noopener"><img src="/assets/icon/facebook.svg" class="w-8 h-8 hover:scale-110 transition" alt="Facebook"></a>
                <a href="<?= htmlspecialchars($__siteLineUrl) ?>" target="_blank" rel="noopener"><img src="/assets/icon/line.svg" class="w-8 h-8 hover:scale-110 transition" alt="LINE"></a>
                <a href="<?= htmlspecialchars($__siteYt) ?>" target="_blank" rel="noopener"><img src="/assets/icon/youtube.svg" class="w-8 h-8 hover:scale-110 transition" alt="YouTube"></a>
                <a href="<?= htmlspecialchars($__siteIg) ?>" target="_blank" rel="noopener"><img src="/assets/icon/instagram.svg" class="w-8 h-8 hover:scale-110 transition" alt="Instagram"></a>
                <a href="<?= htmlspecialchars($__siteTt) ?>" target="_blank" rel="noopener"><img src="/assets/icon/tiktok.svg" class="w-8 h-8 hover:scale-110 transition" alt="TikTok"></a>
            </div>

        </div>
    </footer>

    <!-- Floating Contact Button -->
    <a href="tel:092-515-9991" class="fixed bottom-6 right-6 w-14 h-14 bg-brand-navy rounded-full shadow-2xl flex items-center justify-center text-white hover:scale-110 hover:bg-brand-navyHover transition z-50" id="floating-phone">
        <i data-lucide="phone" class="w-6 h-6 fill-current"></i>
    </a>

    <script>lucide.createIcons();</script>
    <script>
    // Move floating button above cookie banner
    (function(){
        var banner = document.getElementById('cookies-banner');
        var phone = document.getElementById('floating-phone');
        if (banner && phone) {
            var mo = new MutationObserver(function(){
                if (banner.style.display !== 'none' && !localStorage.getItem('cookies_accepted')) {
                    phone.style.bottom = '90px';
                } else {
                    phone.style.bottom = '24px';
                }
            });
            mo.observe(banner, {attributes: true, attributeFilter: ['style']});
            if (banner.style.display !== 'none') phone.style.bottom = '90px';
        }
    })();
    // MutationObserver for Lucide icons (re-render on class/style changes)
    new MutationObserver(function(){lucide.createIcons();}).observe(document.documentElement,{attributes:true,attributeFilter:['class','style'],subtree:true});
    </script>

    <!-- Main JavaScript -->
    <script src="/assets/js/main.js"></script>

</body>

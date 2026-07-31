<?php
// ลิงก์ฟอร์มทำประกัน — ใช้ได้ทั้ง main domain และ subdomain form.
$isFormSubdomain = (strpos($_SERVER['HTTP_HOST'] ?? '', 'form.') === 0);
$formUrl = $isFormSubdomain ? '/index.php' : '/form/';
?>
    <!-- 8. PRE-FOOTER CONTACT BAR -->
    <section class="bg-brand-light border-y border-gray-200">
        <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-6 flex flex-col lg:flex-row items-center justify-between gap-6">
            
            <div class="text-center lg:text-left">
                <h3 class="text-lg font-bold text-brand-navy mb-1">ปรึกษาและวางแผนการเงินกับเรา</h3>
                <p class="text-xs text-brand-gray">ยินดีให้คำแนะนำ ฟรี! ไม่มีค่าใช้จ่าย</p>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-6 md:gap-12">
                <a href="tel:092-515-9991" class="flex items-center gap-3 text-brand-text hover:text-brand-navy transition group">
                    <i data-lucide="phone" class="w-6 h-6 group-hover:scale-110 transition"></i>
                    <span class="font-bold text-lg">092-515-9991</span>
                </a>
                <a href="https://lin.ee/QngrNQ3" target="_blank" class="flex items-center gap-3 text-brand-text hover:text-brand-green transition group">
                    <img src="/assets/icon/line.svg" class="w-7 h-7 hover:scale-110 transition" alt="LINE">
                    <span class="font-bold text-lg">@945ampel</span>
                </a>
                <a href="https://facebook.com/pp.insure168" target="_blank" class="flex items-center gap-3 text-brand-text hover:text-blue-600 transition group">
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
                    <a href="/life.php" class="hover:text-white">ประกันของเรา</a> <span class="text-white/20">|</span>
                    <a href="<?= $formUrl ?>" class="hover:text-white">แบบฟอร์มทำประกัน</a> <span class="text-white/20">|</span>
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
                <a href="https://facebook.com/pp.insure168" target="_blank" rel="noopener"><img src="/assets/icon/facebook.svg" class="w-8 h-8 hover:scale-110 transition" alt="Facebook"></a>
                <a href="https://lin.ee/QngrNQ3" target="_blank" rel="noopener"><img src="/assets/icon/line.svg" class="w-8 h-8 hover:scale-110 transition" alt="LINE"></a>
                <a href="https://youtube.com" target="_blank" rel="noopener"><img src="/assets/icon/youtube.svg" class="w-8 h-8 hover:scale-110 transition" alt="YouTube"></a>
                <a href="https://instagram.com" target="_blank" rel="noopener"><img src="/assets/icon/instagram.svg" class="w-8 h-8 hover:scale-110 transition" alt="Instagram"></a>
                <a href="https://www.tiktok.com/@945ampel" target="_blank" rel="noopener"><img src="/assets/icon/tiktok.svg" class="w-8 h-8 hover:scale-110 transition" alt="TikTok"></a>
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

    <!-- Development Notice Popup -->
    <div id="dev-popup" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300" style="opacity:0; pointer-events:none;">
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-[90%] p-8 text-center relative transform scale-90 transition-transform duration-300">
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto mb-5 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M14.7 2.34a1 1 0 0 0-1.4 0l-10 10a1 1 0 0 0 0 1.4l10 10a1 1 0 0 0 1.4 0l10-10a1 1 0 0 0 0-1.4Z"/><path d="M12 16.5v.5"/></svg>
            </div>
            <!-- Text -->
            <h4 class="text-xl font-bold text-gray-900 mb-2">🚧 เว็บไซต์กำลังพัฒนา</h4>
            <p class="text-sm text-gray-500 mb-6 leading-relaxed">
                เว็บไซต์นี้อยู่ระหว่างการพัฒนา<br>เพื่อประสบการณ์ที่ดีที่สุดสำหรับคุณ
            </p>
            <!-- Button -->
            <button onclick="closeDevPopup()" class="bg-brand-navy hover:bg-brand-navyHover text-white font-bold px-8 py-3 rounded-xl transition text-sm w-full shadow-md">
                รับทราบ เข้าเว็บไซต์
            </button>
        </div>
    </div>
    <script>
    function closeDevPopup() {
        var popup = document.getElementById('dev-popup');
        popup.style.opacity = '0';
        popup.style.pointerEvents = 'none';
        popup.querySelector('div').style.transform = 'scale(0.9)';
        localStorage.setItem('dev_popup_closed', '1');
    }
    (function(){
        if (!localStorage.getItem('dev_popup_closed')) {
            var popup = document.getElementById('dev-popup');
            setTimeout(function() {
                popup.style.opacity = '1';
                popup.style.pointerEvents = 'auto';
                popup.querySelector('div').style.transform = 'scale(1)';
            }, 800);
        }
    })();
    </script>
</body>

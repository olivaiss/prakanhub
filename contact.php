<?php
$pageTitle = 'ติดต่อเรา';
include 'includes/header.php';
?>

<!-- Page Hero -->
<section class="bg-brand-navy text-white py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-3">ติดต่อเรา</h1>
        <p class="text-blue-200 text-lg max-w-2xl mx-auto">พร้อมให้คำปรึกษาทุกเรื่องประกัน การเงิน และการวางแผนอนาคต</p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-brand-light">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

            <!-- Left: Contact Form -->
            <div>
                <h2 class="text-2xl font-bold text-brand-navy mb-6">ส่งข้อความถึงเรา</h2>
                <form method="POST" action="" class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-brand-text mb-1">ชื่อ <span class="text-red-500">*</span></label>
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
                        <label class="block text-sm font-medium text-brand-text mb-1">หัวข้อ <span class="text-red-500">*</span></label>
                        <select name="subject" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-navy focus:border-transparent outline-none transition bg-white">
                            <option value="">— เลือกหัวข้อ —</option>
                            <option>ปรึกษาแผนประกันชีวิต</option>
                            <option>ปรึกษาแผนประกันสุขภาพ</option>
                            <option>ปรึกษาแผนประกันภัยทั่วไป</option>
                            <option>สนใจร่วมงานกับเรา</option>
                            <option>สมัครสัมมนา/คอร์ส</option>
                            <option>สอบถามข้อมูลทั่วไป</option>
                            <option>ขอใบเสนอราคา</option>
                            <option>อื่นๆ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-brand-text mb-1">ข้อความ <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="5" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-brand-navy focus:border-transparent outline-none transition resize-y"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-brand-navy hover:bg-brand-navyHover text-white font-bold py-3.5 rounded-xl transition shadow-md flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-5 h-5"></i> ส่งข้อความ
                    </button>
                </form>
            </div>

            <!-- Right: Contact Info -->
            <div>
                <h2 class="text-2xl font-bold text-brand-navy mb-6">ข้อมูลติดต่อ</h2>
                <div class="space-y-6">
                    <a href="tel:092-515-9991" class="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover-card transition group">
                        <div class="w-12 h-12 rounded-full bg-brand-light flex items-center justify-center text-brand-navy group-hover:bg-brand-navy group-hover:text-white transition"><i data-lucide="phone" class="w-5 h-5"></i></div>
                        <div><div class="text-xs text-brand-gray">โทรศัพท์</div><div class="font-bold text-brand-navy">092-515-9991</div></div>
                    </a>
                    <a href="https://lin.ee/QngrNQ3" target="_blank" class="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover-card transition group">
                        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition"><img src="/assets/icon/line.svg" class="w-5 h-5"></div>
                        <div><div class="text-xs text-brand-gray">LINE</div><div class="font-bold text-green-600">@945ampel</div></div>
                    </a>
                    <a href="https://facebook.com/pp.insure168" target="_blank" class="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-sm border border-gray-100 hover-card transition group">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition"><img src="/assets/icon/facebook.svg" class="w-5 h-5"></div>
                        <div><div class="text-xs text-brand-gray">Facebook</div><div class="font-bold text-blue-600">pp.insure168</div></div>
                    </a>

                    <!-- Business Hours Card -->
                    <div class="p-5 bg-white rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-brand-navy mb-3 flex items-center gap-2"><i data-lucide="clock" class="w-5 h-5"></i> เวลาทำการ</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between"><span class="text-brand-gray">จันทร์ - ศุกร์</span><span class="font-medium">09:00 - 18:00 น.</span></div>
                            <div class="flex justify-between"><span class="text-brand-gray">เสาร์</span><span class="font-medium">09:00 - 16:00 น.</span></div>
                            <div class="flex justify-between"><span class="text-brand-gray">อาทิตย์</span><span class="font-medium text-red-500">หยุด</span></div>
                        </div>
                    </div>

                    <!-- LINE QR -->
                    <div class="p-5 bg-white rounded-2xl shadow-sm border border-gray-100 text-center">
                        <h3 class="font-bold text-brand-navy mb-3 flex items-center justify-center gap-2"><img src="/assets/icon/line.svg" class="w-5 h-5"> LINE @945ampel</h3>
                        <img src="/assets/qrcode/qrcode-line.jpg" alt="QR LINE" class="w-40 h-40 mx-auto rounded-xl shadow-sm" onerror="this.src='https://via.placeholder.com/160x160?text=LINE+QR'">
                        <p class="text-xs text-brand-gray mt-2">สแกนเพื่อเพิ่มเพื่อน รับคำปรึกษาฟรี</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Google Maps -->
<section>
    <div class="w-full h-[400px] bg-gray-200 relative">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1370.0003260487795!2d100.54569707254034!3d13.78212382572387!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29dbd421a5833%3A0xa9672ffdc6c546ab!2z4Lit4Liy4LiE4Liy4Lij4Lie4Lir4Lil4LmC4Lii4LiY4Li04LiZIOC5gOC4nuC4peC4qg!5e0!3m2!1sth!2sth!4v1784934788774!5m2!1sth!2sth" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-brand-navy text-white text-center">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">พร้อมที่จะวางแผนการเงินแล้วหรือยัง?</h2>
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

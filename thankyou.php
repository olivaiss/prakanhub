<?php
$pageTitle = 'ขอบคุณที่ติดต่อเรา';
include 'includes/header.php';
?>

<section class="min-h-[70vh] flex items-center justify-center py-16">
    <div class="max-w-[600px] mx-auto px-4 md:px-8 text-center">
        <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
            <i data-lucide="check-circle-2" class="w-10 h-10 text-green-600"></i>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-brand-navy mb-4">ขอบคุณที่ติดต่อเรา!</h1>
        <p class="text-brand-text text-lg mb-2">เราได้รับข้อความของคุณเรียบร้อยแล้ว</p>
        <p class="text-brand-gray mb-8">ทีมงานของเราจะตอบกลับโดยเร็วที่สุดภายใน 24 ชั่วโมงทำการ</p>
        <div class="bg-brand-light rounded-2xl p-6 mb-8">
            <p class="text-sm text-brand-text mb-4">ระหว่างรอ คุณสามารถติดต่อเราผ่านช่องทางอื่นได้</p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-green-700 transition">
                    <img src="/assets/icon/line.svg" class="w-4 h-4"> LINE @945ampel
                </a>
                <a href="tel:092-515-9991" class="inline-flex items-center gap-2 bg-brand-navy text-white px-5 py-2.5 rounded-full text-sm font-bold hover:bg-brand-navyHover transition">
                    <i data-lucide="phone" class="w-4 h-4"></i> 092-515-9991
                </a>
            </div>
        </div>
        <a href="/index.php" class="inline-flex items-center gap-2 text-brand-navy font-bold hover:underline">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับสู่หน้าแรก
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

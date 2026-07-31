<?php
http_response_code(404);
$pageTitle = 'ไม่พบหน้าที่คุณค้นหา';
include 'includes/header.php';
?>

<section class="min-h-[70vh] flex items-center justify-center py-16">
    <div class="max-w-[600px] mx-auto px-4 md:px-8 text-center">
        <div class="text-8xl font-bold text-brand-light mb-4">404</div>
        <h1 class="text-2xl md:text-3xl font-bold text-brand-navy mb-4">ไม่พบหน้าที่คุณค้นหา</h1>
        <p class="text-brand-text mb-2">หน้าที่คุณกำลังมองหาอาจถูกย้าย ลบ หรือไม่มีอยู่</p>
        <p class="text-brand-gray mb-8">ลองตรวจสอบ URL หรือกลับไปที่หน้าแรก</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="/index.php" class="inline-flex items-center gap-2 bg-brand-navy text-white px-6 py-3 rounded-full text-sm font-bold hover:bg-brand-navyHover transition shadow-md">
                <i data-lucide="home" class="w-4 h-4"></i> กลับหน้าแรก
            </a>
            <a href="/contact.php" class="inline-flex items-center gap-2 bg-white border border-brand-navy/20 text-brand-navy px-6 py-3 rounded-full text-sm font-bold hover:bg-brand-light transition shadow-sm">
                <i data-lucide="phone" class="w-4 h-4"></i> ติดต่อเรา
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

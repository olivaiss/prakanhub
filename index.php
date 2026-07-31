<?php include 'includes/header.php'; ?>

    <!-- HERO SECTION -->
    <section class="hero-gradient relative w-full overflow-hidden min-h-[90vh] flex items-center bg-gradient-to-br from-blue-50 via-white to-blue-100">
        
        <!-- Background Image (subtle) -->
        <div class="absolute inset-0 z-0">
            <img src="/assets/image/hero-bg.webp" alt="" class="w-full h-full object-cover lg:object-right">
            <div class="absolute top-0 left-0 bottom-0 w-[90%] lg:w-[60%] bg-gradient-to-r from-white via-white/60 to-transparent"></div>
        </div>

        <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-12 lg:py-20 flex flex-col lg:flex-row items-center relative z-10 w-full">
            
            <!-- Left Content -->
            <div class="w-full lg:w-1/2 pt-4 pb-10 lg:pb-20 z-20">
                <!-- Subtitle Pill -->
                <div class="inline-flex items-center gap-2 bg-brand-navy/10 text-brand-navy text-xs font-semibold px-4 py-1.5 rounded-full mb-4 border border-brand-navy/10">
                    <i data-lucide="shield" class="w-3.5 h-3.5"></i>
                    ที่ปรึกษาประกันชีวิตและการเงิน Allianz Ayudhya
                </div>

                <h1 class="text-[1.75rem] sm:text-5xl md:text-7xl font-bold text-brand-navy leading-[1.1] mb-2 tracking-tight">
                    วางแผนอนาคต<br>อย่างมั่นใจ
                </h1>
                <h2 class="text-2xl md:text-3xl font-semibold text-brand-navy/80 mb-6">พร้อมดูแลทุกเป้าหมายชีวิต</h2>
                <p class="text-brand-gray text-base md:text-lg mb-10 max-w-lg leading-relaxed">
                    ครบทุกความคุ้มครอง วางแผนให้เหมาะกับคุณ<br>ด้วยประสบการณ์และความจริงใจ
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-14">
                    <button class="flex items-center justify-center gap-3 bg-brand-navy hover:bg-brand-navyHover text-white px-6 py-3.5 rounded-xl shadow-lg transition group">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                        <div class="text-left leading-none"><div class="font-bold text-sm">ปรึกษาฟรี</div><div class="text-[10px] font-light mt-0.5">วางแผนการเงิน</div></div>
                    </button>
                    
                    <a href="https://www.allianz.co.th/th_TH/services/my-allianz.html" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-3 bg-white border border-brand-navy/20 hover:border-brand-navy text-brand-navy px-6 py-3.5 rounded-xl shadow-sm transition group">
                        <i data-lucide="file-check-2" class="w-5 h-5"></i>
                        <div class="text-left leading-none"><div class="font-bold text-sm">เช็กเบี้ยประกัน</div><div class="text-[10px] text-brand-gray mt-0.5">เปรียบเทียบแผน</div></div>
                    </a>

                    <a href="https://line.me/R/ti/p/@945ampel" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-3 bg-brand-green hover:bg-brand-greenHover text-white px-6 py-3.5 rounded-xl shadow-lg transition">
                        <img src="assets/icon/line.svg" class="w-5 h-5" alt="LINE">
                        <div class="text-left leading-none"><div class="font-bold text-sm">แชทกับผม</div><div class="text-[10px] font-light mt-0.5">ผ่าน LINE OA</div></div>
                    </a>
                </div>

                <!-- Stats -->
                <div class="flex flex-wrap items-center gap-6 md:gap-12">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full border-2 border-brand-navy/20 flex items-center justify-center text-brand-navy"><i data-lucide="award" class="w-6 h-6 stroke-[1.5]"></i></div>
                        <div class="leading-none"><div class="text-[10px] text-brand-gray mb-1">ประสบการณ์</div><div class="font-bold text-xl text-brand-navy mb-0.5">10+ ปี</div><div class="text-[10px] text-brand-gray">ในวงการประกันชีวิต</div></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full border-2 border-brand-navy/20 flex items-center justify-center text-brand-navy"><i data-lucide="users" class="w-6 h-6 stroke-[1.5]"></i></div>
                        <div class="leading-none"><div class="text-[10px] text-brand-gray mb-1">ดูแลลูกค้า</div><div class="font-bold text-xl text-brand-navy mb-0.5">1,000+ คน</div><div class="text-[10px] text-brand-gray">วางแผนการเงินสำเร็จ</div></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full border-2 border-brand-navy/20 flex items-center justify-center text-brand-navy"><i data-lucide="medal" class="w-6 h-6 stroke-[1.5]"></i></div>
                        <div class="leading-none"><div class="text-[10px] text-brand-gray mb-1">รางวัลคุณวุฒิ</div><div class="font-bold text-xl text-brand-navy mb-0.5">MDRT</div><div class="text-[10px] text-brand-gray">ตัวแทนคุณภาพระดับสากล</div></div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="max-w-[1400px] mx-auto px-4 md:px-8 py-16 space-y-16">

        <!-- CATEGORIES (ประกันของเรา - JSON) -->
        <section>
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-brand-navy mb-2">ประกันของเรา</h2>
                <p class="text-sm text-brand-gray">ครบทุกความคุ้มครอง ดูแลคุณและคนที่คุณรัก</p>
            </div>
            <div id="category-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-3 md:gap-4">
                <!-- Data injected via JS -->
            </div>
        </section>

        <!-- SPLIT BANNERS (Financial & Seminar) -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Left Banner -->
            <div class="bg-brand-navy rounded-3xl overflow-hidden relative shadow-card flex flex-col sm:flex-row group hover-card">
                <div class="p-8 sm:w-3/5 z-10 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold text-white mb-4 leading-tight">อยากวางแผนการเงิน<br>ให้เหมาะกับคุณ?</h3>
                    <ul class="space-y-2 mb-6 text-sm text-white/80">
                        <li class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-white"></i> วางแผนการเงินส่วนบุคคล</li>
                        <li class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-white"></i> วิเคราะห์ความต้องการ</li>
                        <li class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-white"></i> ออกแบบแผนประกันที่เหมาะกับคุณ</li>
                        <li class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-white"></i> ดูแลต่อเนื่องในระยะยาว</li>
                    </ul>
                    <button class="bg-white text-brand-navy font-bold text-sm px-6 py-2.5 rounded-full w-fit flex items-center gap-2 hover:bg-gray-100 transition">
                        ปรึกษาฟรี คลิกเลย <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="sm:w-2/5 h-64 sm:h-auto relative overflow-hidden">
                    <img src="/assets/image/seminar/1784911056104.webp" alt="" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Right Banner -->
            <div class="bg-gradient-to-br from-blue-50 to-white rounded-3xl overflow-hidden shadow-card flex flex-col sm:flex-row group border border-gray-100 hover-card">
                <div class="p-8 sm:w-3/5 z-10 flex flex-col justify-center">
                    <h3 class="text-2xl font-bold text-brand-navy mb-4 leading-tight">สัมมนา & คอร์ส<br>อัปสกิลความรู้</h3>
                    <ul class="space-y-2 mb-6 text-sm text-brand-gray">
                        <li class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-brand-navy"></i> ความรู้ด้านประกันชีวิต</li>
                        <li class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-brand-navy"></i> เทคนิคการวางแผนการเงิน</li>
                        <li class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-brand-navy"></i> การพัฒนาตนเอง</li>
                        <li class="flex items-center gap-2"><i data-lucide="check-circle-2" class="w-4 h-4 text-brand-navy"></i> เครือข่ายตัวแทนมืออาชีพ</li>
                    </ul>
                    <button class="bg-brand-navy text-white font-bold text-sm px-6 py-2.5 rounded-full w-fit flex items-center gap-2 hover:bg-brand-navyHover transition">
                        ดูสัมมนาทั้งหมด <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="sm:w-2/5 h-64 sm:h-auto relative overflow-hidden">
                    <img src="/assets/image/seminar/1784911045672.webp" alt="" class="w-full h-full object-cover">
                </div>
            </div>
        </section>

        <!-- CAREER BANNER -->
        <section class="bg-gradient-to-br from-brand-navy to-brand-navyHover rounded-3xl overflow-hidden shadow-card relative group">
            <div class="absolute inset-0 opacity-[0.06]">
                <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=1400&q=80" alt="" class="w-full h-full object-cover">
            </div>
            <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-[0.03] pointer-events-none hidden lg:flex items-center justify-center">
                <div class="flex gap-2">
                    <div class="w-12 h-64 bg-white rounded-t-full"></div>
                    <div class="w-12 h-80 bg-white rounded-t-full -mt-8"></div>
                    <div class="w-12 h-64 bg-white rounded-t-full"></div>
                </div>
            </div>
            <div class="relative z-10 p-8 md:p-12 flex flex-col lg:flex-row items-center gap-8">
                <div class="lg:w-2/3">
                    <h3 class="text-2xl md:text-3xl font-bold text-white mb-2">ร่วมงานกับเรา สร้างรายได้ ไม่จำกัด</h3>
                    <p class="text-blue-200 mb-6">เติบโตไปพร้อมกัน กับทีมคุณภาพ</p>
                    <div class="flex flex-wrap gap-6 md:gap-10 mb-8 lg:mb-0">
                        <div><div class="text-2xl font-bold text-white mb-1"><i data-lucide="trending-up" class="w-5 h-5 inline text-green-300"></i> รายได้ดี</div><div class="text-xs text-blue-200">ไม่มีเพดาน</div></div>
                        <div><div class="text-2xl font-bold text-white mb-1"><i data-lucide="graduation-cap" class="w-5 h-5 inline text-green-300"></i> อบรมฟรี</div><div class="text-xs text-blue-200">โดยมืออาชีพ</div></div>
                        <div><div class="text-2xl font-bold text-white mb-1"><i data-lucide="heart-handshake" class="w-5 h-5 inline text-green-300"></i> ระบบดูแล</div><div class="text-xs text-blue-200">ครบวงจร</div></div>
                        <div><div class="text-2xl font-bold text-white mb-1"><i data-lucide="plane" class="w-5 h-5 inline text-green-300"></i> โบนัสทริป</div><div class="text-xs text-blue-200">และรางวัลมากมาย</div></div>
                    </div>
                </div>
                <div class="lg:w-1/3 flex flex-col sm:flex-row lg:flex-col gap-4 lg:items-stretch lg:justify-center">
                    <a href="/career.php" class="bg-white text-brand-navy font-bold px-6 py-3.5 rounded-xl hover:bg-gray-100 transition flex items-center justify-center gap-2 text-sm lg:w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="user-plus" class="lucide lucide-user-plus w-4 h-4"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" x2="19" y1="8" y2="14"></line><line x1="22" x2="16" y1="11" y2="11"></line></svg> สมัครตัวแทน
                    </a>
                    <a href="https://line.me/R/ti/p/@945ampel" target="_blank" rel="noopener noreferrer" class="bg-brand-green hover:bg-brand-greenHover text-white font-bold px-6 py-3.5 rounded-xl flex items-center justify-center gap-2 transition text-sm lg:w-full">
                        <img src="assets/icon/line.svg" class="w-4 h-4" alt="LINE"> แชทกับผม ผ่าน LINE OA
                    </a>
                </div>
            </div>
        </section>

        <!-- ARTICLES (JSON) -->
        <section>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-brand-navy mb-2">บทความ & ความรู้</h2>
                    <p class="text-sm text-brand-gray">อัปเดตความรู้ด้านประกัน การเงิน และการพัฒนาตนเอง</p>
                </div>
                <a href="/articles.php" class="text-sm font-bold text-brand-navy hover:underline flex items-center gap-1 mt-2 md:mt-0">
                    ดูบทความทั้งหมด <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            <div id="articles-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Data injected via JS -->
            </div>
        </section>

        <!-- TESTIMONIALS (JSON) -->
        <section>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-brand-navy mb-2">เสียงจากลูกค้า</h2>
                    <p class="text-sm text-brand-gray">ความไว้วางใจที่ทำให้เรามุ่งมั่นให้บริการยิ่งขึ้น</p>
                </div>
                <a href="/testimonials.php" class="text-sm font-bold text-brand-navy hover:underline flex items-center gap-1 mt-2 md:mt-0">
                    ดูรีวิวทั้งหมด <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            <div id="reviews-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Data injected via JS -->
            </div>
        </section>

    </main>

<?php include 'includes/footer.php'; ?>

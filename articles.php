<?php
$pageTitle = 'บทความทั้งหมด';
include 'includes/header.php';
?>

<!-- Page Hero -->
<section class="bg-brand-navy text-white py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 text-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-3">บทความ & ความรู้</h1>
        <p class="text-blue-200 text-lg max-w-2xl mx-auto">อัปเดตความรู้ด้านประกัน การเงิน และการพัฒนาตนเอง จากประกันจริงใจ by ปกป้อง</p>
    </div>
</section>

<!-- Articles Grid -->
<section class="py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div id="articles-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Rendered by JS -->
        </div>
        <!-- Pagination -->
        <div id="pagination" class="flex flex-wrap items-center justify-center gap-2 mt-10">
            <span id="page-info" class="text-xs text-brand-gray mr-2"></span>
        </div>
    </div>
</section>

<script>
const articles = [
    {
        id: 1,
        tag: 'ประกันชีวิต',
        title: 'ทำประกันชีวิตไว้ ดีกับตัวเองและครอบครัวอย่างไร?',
        excerpt: 'ประกันชีวิตไม่ใช่แค่การออม แต่คือความมั่นคงของคนที่เรารัก มาดูสาเหตุที่ทุกคนควรมีประกันชีวิต',
        date: '15 ม.ค. 2026',
        cover: 'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=600&h=375&fit=crop',
        coverAlt: 'ครอบครัวอบอุ่น'
    },
    {
        id: 2,
        tag: 'ประกันสุขภาพ',
        title: 'ประกันสุขภาพเหมาจ่าย เลือกแบบไหนดี?',
        excerpt: 'เปรียบเทียบข้อดีข้อเสียของประกันสุขภาพเหมาจ่ายแต่ละแบบ เลือกให้เหมาะกับความต้องการของคุณ',
        date: '10 ม.ค. 2026',
        cover: 'https://images.unsplash.com/photo-1505751172876-fa1923c5c528?w=600&h=375&fit=crop',
        coverAlt: 'สุขภาพดี'
    },
    {
        id: 3,
        tag: 'การเงิน',
        title: 'วางแผนการเงินฉบับเริ่มต้น สำหรับคนทำงาน',
        excerpt: 'เริ่มต้นวางแผนการเงินอย่างไรให้มั่นคง กับขั้นตอนง่ายๆ ที่คนทำงานทุกคนทำได้',
        date: '5 ม.ค. 2026',
        cover: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=600&h=375&fit=crop',
        coverAlt: 'วางแผนการเงิน'
    },
    {
        id: 4,
        tag: 'เทคนิคการขาย',
        title: '5 เทคนิคสร้างความเชื่อมั่น ในการขายประกัน',
        excerpt: 'เทคนิคที่จะช่วยให้คุณสร้างความไว้วางใจกับลูกค้า และปิดการขายได้อย่างมืออาชีพ',
        date: '1 ม.ค. 2026',
        cover: 'https://images.unsplash.com/photo-1552581234-26160f608093?w=600&h=375&fit=crop',
        coverAlt: 'เทคนิคการขาย'
    },
    {
        id: 5,
        tag: 'ประกันอุบัติเหตุ',
        title: 'ประกันอุบัติเหตุ คุ้มครอง 24 ชม. ทั่วโลก เบี้ยเริ่มต้นเพียงหลักร้อย',
        excerpt: 'อุบัติเหตุเกิดเมื่อไหร่ก็ได้ ประกันอุบัติเหตุช่วยคุณได้ทุกที่ทุกเวลา 24 ชั่วโมงทั่วโลก ในราคาเบี้ยที่เริ่มต้นเพียงหลักร้อยต่อปี',
        date: '26 ก.ค. 2026',
        cover: '/assets/image/articles/article5.webp',
        coverAlt: 'ประกันอุบัติเหตุ คุ้มครอง 24 ชั่วโมง'
    },
    {
        id: 6,
        tag: 'ประกันโรคร้ายแรง',
        title: 'ประกันโรคร้ายแรง เจอ จ่าย จบ คุ้มครองโรคหนักแบบรู้กัน',
        excerpt: 'โรคร้ายแรงมาไม่เคยบอกล่วงหน้า แต่ประกันโรคร้ายแรงแบบเจอ จ่าย จบ พร้อมจ่ายก้อนใหญ่ให้คุณสู้ต่อได้ทันที',
        date: '26 ก.ค. 2026',
        cover: '/assets/image/articles/article6.webp',
        coverAlt: 'ประกันโรคร้ายแรง'
    },
    {
        id: 7,
        tag: 'ประกันเด็ก',
        title: 'ประกันเด็ก วางแผนอนาคตให้ลูกน้อย ตั้งแต่ออมถึงคุ้มครอง',
        excerpt: 'เริ่มต้นวางแผนอนาคตให้ลูกน้อยด้วยประกันเด็ก ที่ให้ทั้งความคุ้มครองด้านสุขภาพและการออมเพื่อการศึกษา',
        date: '26 ก.ค. 2026',
        cover: '/assets/image/articles/article7.webp',
        coverAlt: 'ประกันเด็ก'
    },
    {
        id: 8,
        tag: 'ประกันออมทรัพย์',
        title: 'ประกันออมทรัพย์ ออมเงิน พร้อมคุ้มครองชีวิต ได้เงินคืนแน่นอน',
        excerpt: 'ออมเงินไปพร้อมรับความคุ้มครองชีวิต ประกันออมทรัพย์ตอบโจทย์คนอยากเก็บเงินแบบมีวินัย พร้อมผลตอบแทนที่แน่นอน',
        date: '26 ก.ค. 2026',
        cover: '/assets/image/articles/article8.webp',
        coverAlt: 'ประกันออมทรัพย์'
    },
    {
        id: 9,
        tag: 'ประกันบำนาญ',
        title: 'ประกันบำนาญ วางแผนเกษียณ สบายมั่นคง พร้อมลดหย่อนภาษี',
        excerpt: 'เกษียณอย่างมั่นคงด้วยประกันบำนาญ วางแผนการเงินระยะยาว พร้อมสิทธิประโยชน์ลดหย่อนภาษีสูงสุดถึง 200,000 บาท',
        date: '26 ก.ค. 2026',
        cover: '/assets/image/articles/article9.webp',
        coverAlt: 'ประกันบำนาญ'
    },
    {
        id: 10,
        tag: 'ประกันเดินทาง',
        title: 'ประกันเดินทาง คุ้มครองทุกทริป ทั้งในประเทศและต่างประเทศ',
        excerpt: 'เดินทางทั้งทีต้องอุ่นใจด้วยประกันเดินทาง คุ้มครองตั้งแต่เที่ยวในประเทศจนถึงท่องเที่ยวต่างประเทศ เบี้ยเริ่มต้นเพียงวันละไม่กี่สิบบาท',
        date: '26 ก.ค. 2026',
        cover: '/assets/image/articles/article10.webp',
        coverAlt: 'ประกันเดินทาง'
    }
];

const PER_PAGE = 8;
let currentPage = 1;

function renderArticles() {
    const container = document.getElementById('articles-container');
    const pagination = document.getElementById('pagination');
    if (!container) return;

    const totalPages = Math.ceil(articles.length / PER_PAGE);
    const start = (currentPage - 1) * PER_PAGE;
    const end = Math.min(start + PER_PAGE, articles.length);
    const pageArticles = articles.slice(start, end);

    container.innerHTML = '';
    pageArticles.forEach(a => {
        container.innerHTML += `
            <a href="article.php?id=${a.id}" class="group block bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover-card">
                <div class="aspect-[16/10] overflow-hidden">
                    <img src="${a.cover}" alt="${a.coverAlt}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
                <div class="p-5">
                    <span class="text-[10px] font-bold text-brand-navy bg-brand-light px-3 py-1 rounded-full">${a.tag}</span>
                    <div class="text-xs text-brand-gray mt-2 flex items-center gap-1"><i data-lucide="calendar" class="w-3 h-3"></i> ${a.date}</div>
                    <h2 class="text-sm font-bold text-brand-navy mt-2 group-hover:text-brand-navyHover transition leading-snug">${a.title}</h2>
                    <p class="text-xs text-brand-text mt-2 line-clamp-3 leading-relaxed">${a.excerpt}</p>
                    <div class="mt-3 text-xs font-bold text-brand-navy flex items-center gap-1 group-hover:gap-2 transition-all">อ่านต่อ <i data-lucide="arrow-right" class="w-3 h-3"></i></div>
                </div>
            </a>
        `;
    });

    // Pagination
    if (pagination) {
        const pageInfo = document.getElementById('page-info');
        if (pageInfo) pageInfo.textContent = `หน้า ${currentPage} จาก ${totalPages}`;
        let html = '';
        html += `<button data-page="${currentPage - 1}" class="page-btn px-3 py-1.5 rounded-lg text-sm font-medium transition ${currentPage <= 1 ? 'text-gray-300 cursor-not-allowed' : 'text-brand-navy hover:bg-brand-light'}">&laquo; ก่อนหน้า</button>`;
        for (let i = 1; i <= totalPages; i++) {
            html += `<button data-page="${i}" class="page-btn px-3 py-1.5 rounded-lg text-sm font-medium transition ${i === currentPage ? 'bg-brand-navy text-white' : 'text-brand-navy hover:bg-brand-light'}">${i}</button>`;
        }
        html += `<button data-page="${currentPage + 1}" class="page-btn px-3 py-1.5 rounded-lg text-sm font-medium transition ${currentPage >= totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-brand-navy hover:bg-brand-light'}">ถัดไป &raquo;</button>`;
        pagination.innerHTML = html;
    }

    lucide.createIcons();
}

// Pagination via event delegation
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.page-btn');
    if (!btn) return;
    const page = parseInt(btn.dataset.page);
    if (isNaN(page)) return;
    const totalPages = Math.ceil(articles.length / PER_PAGE);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderArticles();
    const section = document.querySelector('.py-16');
    if (section) window.scrollTo({ top: section.offsetTop - 100, behavior: 'smooth' });
});

// Init
document.addEventListener('DOMContentLoaded', renderArticles);
</script>

<?php include 'includes/footer.php'; ?>

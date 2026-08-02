/* ============================================
   ประกันจริงใจ by ปกป้อง - Main JavaScript
   ============================================ */

// --- Mobile Menu Toggle ---
const btn = document.getElementById('hamburger-btn');
const closeBtn = document.getElementById('close-menu-btn');
const menu = document.getElementById('mobile-menu');
const overlay = document.getElementById('mobile-menu-overlay');

function toggleMenu() {
    menu.classList.toggle('menu-open');
    if (menu.classList.contains('menu-open')) {
        overlay.classList.remove('hidden');
        setTimeout(() => overlay.classList.add('opacity-100'), 10);
    } else {
        overlay.classList.remove('opacity-100');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
}
// btn uses inline onclick="toggleMenu()" in header.php
overlay?.addEventListener('click', toggleMenu);

// --- JSON DATA MODELS (DB first — index.php injects window.__DB_*) ---
const categories = (typeof window.__DB_CATEGORIES !== 'undefined' && window.__DB_CATEGORIES.length > 0) ? window.__DB_CATEGORIES : [
    { icon: "heart", title: "ประกันชีวิต", desc: "คุ้มครองชีวิต<br>และคนที่คุณรัก" },
    { icon: "activity", title: "ประกันสุขภาพ", desc: "คุ้มครองค่ารักษา<br>โรคร้ายภัยเจอ" },
    { icon: "shield-alert", title: "ประกันโรคร้ายแรง", desc: "เจอ จ่าย จบ<br>ไม่กระทบอนาคต" },
    { icon: "footprints", title: "ประกันอุบัติเหตุ", desc: "คุ้มครอง 24 ชม.<br>ทั่วโลก" },
    { icon: "baby", title: "ประกันเด็ก", desc: "วางแผนอนาคต<br>ให้ลูกน้อย" },
    { icon: "landmark", title: "ประกันออมทรัพย์", desc: "ออมเงิน<br>พร้อมรับผลตอบแทน" },
    { icon: "home", title: "ประกันบำนาญ", desc: "เกษียณสบาย<br>มั่นใจในทุกระดับ" },
    { icon: "arrow-right", title: "ดูทั้งหมด", desc: "ทุกแผนประกัน<br>คลิกที่นี่", isDark: true }
];

const articles = (typeof window.__DB_ARTICLES !== 'undefined' && window.__DB_ARTICLES.length > 0) ? window.__DB_ARTICLES : [
    { id: 1, img: "https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=400&q=80", tag: "ประกันชีวิต", title: "ทำประกันชีวิตไว้<br>ดีกับตัวเองและครอบครัวอย่างไร?" },
    { id: 2, img: "https://images.unsplash.com/photo-1505751172876-fa1923c5c528?auto=format&fit=crop&w=400&q=80", tag: "ประกันสุขภาพ", title: "ประกันสุขภาพเหมาจ่าย<br>เลือกแบบไหนดี?" },
    { id: 3, img: "https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=400&q=80", tag: "การเงิน", title: "วางแผนการเงินฉบับเริ่มต้น<br>สำหรับคนทำงาน" },
    { id: 4, img: "https://images.unsplash.com/photo-1552581234-26160f608093?auto=format&fit=crop&w=400&q=80", tag: "เทคนิคการขาย", title: "5 เทคนิคสร้างความเชื่อมั่น<br>ในการขายประกัน" }
];

const reviews = (typeof window.__DB_REVIEWS !== 'undefined' && window.__DB_REVIEWS.length > 0) ? window.__DB_REVIEWS : [
    { img: "https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=100&q=80", text: "คุณป้องดูแลดีมากครับ ให้คำแนะนำวางแผนได้ตรงกับความต้องการจริงๆ", name: "คุณธนธัช", desc: "เจ้าของธุรกิจ" },
    { img: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=100&q=80", text: "ให้ความรู้เข้าใจง่าย และใส่ใจลูกค้าทุกคน ประทับใจมากค่ะ", name: "คุณพรรณนิภา", desc: "พนักงานบริษัท" },
    { img: "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=100&q=80", text: "วางแผนเกษียณและความปลอดภัย ช่วยให้ครอบครัวเรามั่นคงขึ้นอย่างเห็นได้ชัด", name: "คุณวีรัตน์", desc: "เจ้าของธุรกิจ" },
    { img: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=100&q=80", text: "ได้รับข้อมูลแบบมืออาชีพ และตอบโจทย์ความต้องการของผมเลยครับ", name: "คุณณัฐวุฒิ", desc: "นักลงทุนอิสระ" }
];

// --- RENDER FUNCTIONS ---

// 1. Render Categories
const catGrid = document.getElementById('category-grid');
if (catGrid) {
    categories.forEach(cat => {
        const linkMap = {
            'ประกันชีวิต': '/life.php',
            'ประกันสุขภาพ': '/health.php',
            'ประกันโรคร้ายแรง': '/health.php',
            'ประกันอุบัติเหตุ': '/life.php',
            'ประกันเด็ก': '/health.php',
            'ประกันออมทรัพย์': '/life.php',
            'ประกันบำนาญ': '/life.php',
            'ดูทั้งหมด': '/life.php',
        };
        const linkUrl = (cat.link && cat.link !== '') ? cat.link : (linkMap[cat.title] || '#');
            
        if (cat.isDark) {
            catGrid.innerHTML += `
                <a href="${linkUrl}" class="bg-brand-navy rounded-2xl p-4 flex flex-col items-center justify-center text-center hover-card group border border-brand-navy min-w-[130px] md:min-w-[150px] shrink-0 snap-start">
                    <h3 class="font-bold text-sm text-white mb-2">${cat.title}</h3>
                    <p class="text-[10px] text-blue-200 leading-tight flex items-center gap-1">${cat.desc} <i data-lucide="${cat.icon}" class="w-3 h-3 group-hover:translate-x-1 transition-transform"></i></p>
                </a>
            `;
        } else {
            catGrid.innerHTML += `
                <a href="${linkUrl}" class="bg-white rounded-2xl p-4 flex flex-col items-center text-center shadow-sm border border-gray-100 hover-card group min-w-[130px] md:min-w-[150px] shrink-0 snap-start">
                    <div class="w-12 h-12 rounded-full border border-gray-200 flex items-center justify-center mb-3 text-brand-navy group-hover:bg-brand-light transition-colors">
                        <i data-lucide="${cat.icon}" class="w-6 h-6 stroke-[1.5]"></i>
                    </div>
                    <h3 class="font-bold text-sm text-brand-navy mb-1">${cat.title}</h3>
                    <p class="text-[10px] text-brand-gray leading-tight">${cat.desc}</p>
                </a>
            `;
        }
    });
}

// 2. Render Articles
const artGrid = document.getElementById('articles-grid');
if (artGrid) {
    articles.forEach(art => {
        artGrid.innerHTML += `
            <a href="/article.php?id=${art.id}" class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover-card flex flex-col group">
                <div class="h-40 overflow-hidden relative">
                    <img src="${art.img}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute bottom-2 left-2 bg-white/90 backdrop-blur text-brand-navy text-[10px] font-bold px-3 py-1 rounded-full">${art.tag}</div>
                </div>
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-bold text-sm text-brand-text mb-4 leading-tight group-hover:text-brand-navy transition">${art.title}</h3>
                    <div class="mt-auto text-[10px] font-bold text-brand-navy flex justify-end items-center gap-1">อ่านต่อ <i data-lucide="arrow-right" class="w-3 h-3"></i></div>
                </div>
            </a>
        `;
    });
}

// 3. Render Reviews
const revGrid = document.getElementById('reviews-grid');
if (revGrid) {
    reviews.forEach(rev => {
        revGrid.innerHTML += `
            <div class="bg-brand-light rounded-2xl p-6 hover-card border border-white relative">
                <div class="flex text-yellow-400 mb-3"><i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i><i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i><i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i><i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i><i data-lucide="star" class="w-3.5 h-3.5 fill-current"></i></div>
                <p class="text-xs text-brand-text font-medium leading-relaxed mb-6">"${rev.text}"</p>
                <div class="flex items-center gap-3">
                    ${rev.img ? `<img src="${rev.img}" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">` : `<div class="w-10 h-10 rounded-full bg-brand-navy text-white flex items-center justify-center font-bold text-sm border-2 border-white shadow-sm">${(rev.name || 'ค').charAt(0)}</div>`}
                    <div>
                        <h4 class="font-bold text-xs text-brand-navy">${rev.name}</h4>
                        <p class="text-[9px] text-brand-gray">${rev.desc}</p>
                    </div>
                </div>
            </div>
        `;
    });
}

// Initialize Icons after DOM injection
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

# รายงานการตรวจสอบ Mobile Responsive (375px Viewport)
## Pokpong Insurance Advisor Website — 25 กรกฎาคม 2569

---

## สรุปผลการตรวจสอบ

- **จำนวนหน้าที่ตรวจ**: 19 หน้า (+ includes)
- **สถานะ**: ผ่านเกณฑ์ส่วนใหญ่ มีประเด็นที่ต้องแก้ไข 8 รายการ
- **JS Errors**: ไม่พบ
- **Lucide Icons**: โหลดและแสดงผลปกติ
- **Mobile Menu**: ทำงานได้ทุกหน้าที่มี header (17 หน้า)

---

## รายการปัญหาที่พบ พร้อมแนวทางแก้ไข

### 🔴 P1 — Critical (ต้องแก้ไขก่อนใช้งานจริง)

#### 1. Floating Contact Button ทับ Cookie Banner (index.php และทุกหน้า)
- **ไฟล์**: `includes/footer.php` (บรรทัด 96-98)
- **ปัญหา**: Floating button `fixed bottom-6 right-6` อาจทับกับ Cookies banner `fixed bottom-0` เมื่อแสดงพร้อมกันบน mobile ที่ 375px
- **แนวทางแก้**: เพิ่ม `bottom-20` หรือ `bottom-[calc(theme(spacing.6)+80px)]` เมื่อ cookie banner ปรากฏ, หรือใช้ JS ขยับปุ่มให้สูงขึ้นเมื่อ cookie banner แสดง
```css
/* ใส่ใน style.css */
#cookies-banner:not([style*="display:none"]) ~ a[href*="tel:0891234567"].fixed {
    bottom: 120px; /* อยู่เหนือ cookie banner */
}
```

#### 2. Hero Text ขนาดใหญ่เกินไปบน 375px (index.php)
- **ไฟล์**: `index.php` (บรรทัด 20)
- **ปัญหา**: `<h1 class="text-5xl md:text-7xl ...">` — ที่ 375px font-size = 48px สำหรับ "วางแผนอนาคต\nอย่างมั่นใจ" กินพื้นที่แนวตั้งมาก ทำให้ Hero section สูงเกินจำเป็น
- **แนวทางแก้**: เพิ่ม responsive class `text-4xl` หรือ `text-[2rem]` ก่อน `text-5xl`
```html
<h1 class="text-[2rem] sm:text-5xl md:text-7xl font-bold ...">วางแผนอนาคต<br>อย่างมั่นใจ</h1>
```

#### 3. Animation `animate-bounce` บน Floating Button อาจรบกวนบน Mobile
- **ไฟล์**: `includes/footer.php` (บรรทัด 96)
- **ปัญหา**: ปุ่ม `animate-bounce` เคลื่อนไหวตลอดเวลา ใช้พื้นที่ bottom-right ซึ่งเป็นพื้นที่ที่นิ้วเข้าถึงง่าย ทำให้ผู้ใช้คลิกพลาดหรือรำคาญ
- **แนวทางแก้**: เอา `animate-bounce` ออก หรือเปลี่ยนเป็น hover effect
```html
<a href="tel:0891234567" class="... hover:scale-110 transition ...">
```

---

### 🟡 P2 — Moderate (ควรแก้ไข)

#### 4. Stats Section — จัด Layout บน 375px แน่นเกินไป (index.php)
- **ไฟล์**: `index.php` (บรรทัด 45-58) — Hero stats
- **ปัญหา**: Stats 3 รายการใน `flex-wrap gap-6 md:gap-12` แต่ละรายการมี icon (w-12) + text block ที่ 375px อาจต้องกินพื้นที่แถวละ 1 รายการ หรือแคบมาก
- **แนวทางแก้**: เปลี่ยนเป็น `grid grid-cols-1 sm:grid-cols-3` เพื่อให้ mobile แสดงทีละแถว
```html
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
```

#### 5. About Page — Hero Section สูงเกิน (85vh) บน Mobile
- **ไฟล์**: `about.php` (บรรทัด 9, 195)
- **ปัญหา**: `min-height: 85vh` บน hero ทำให้เนื้อหาช่วงแรกกินพื้นที่มากเกินไปใน 375px viewport (85% ของหน้าจอ ≈ 650px)
- **แนวทางแก้**: เพิ่ม media query
```css
@media (max-width: 640px) {
    .hero-section { min-height: 60vh; }
}
```

#### 6. Mobile Menu Submenu Accordion — ขาด visual feedback
- **ไฟล์**: `includes/header.php` (บรรทัด 181-185)
- **ปัญหา**: ปุ่มประกันของเราในเมนูมือถือมี onclick toggle แต่ไม่มี :focus หรือ :active state styling
- **แนวทางแก้**: เพิ่ม CSS
```css
.mob-sub > button:focus-visible {
    outline: 2px solid #003781;
    border-radius: 8px;
}
```

---

### 🟢 P3 — Minor (แนะนำให้ปรับปรุง)

#### 7. Article Page Breadcrumb — Truncate Width บน Mobile
- **ไฟล์**: `article.php` (บรรทัด 201)
- **ปัญหา**: `truncate max-w-[200px] lg:max-w-[400px]` — ที่ 375px ความกว้าง breadcrumb + arrows + text = อาจล้น ถ้าชื่อบทความยาว
- **แนวทางแก้**: ปรับเป็น `max-w-[140px]` บน mobile
```php
<span class="... truncate max-w-[120px] sm:max-w-[200px] lg:max-w-[400px]">
```

#### 8. Footer Social Icons — ไม่มี padding ด้านล่างบน mobile
- **ไฟล์**: `includes/footer.php` (บรรทัด 84-89)
- **ปัญหา**: Social icons (`flex gap-2`) ใน footer ไม่มี padding-bottom บน mobile ทำให้ชิดขอบ footer เกินไป
- **แนวทางแก้**: เพิ่ม `pb-2` หรือ `mb-2` บน container
```html
<div class="flex gap-2 pb-2 md:pb-0">
```

#### 9. Recruitment Section — CTA Button `max-w-[200px]` แคบเกิน (index.php)
- **ไฟล์**: `index.php` (บรรทัด 153, 157)
- **ปัญหา**: `max-w-[200px]` บนปุ่ม "สมัครตัวแทน" ข้อความภาษาไทยยาวอาจถูกตัดหรือขึ้น 2 บรรทัด
- **แนวทางแก้**: เปลี่ยนเป็น `max-w-[220px]` หรือ `w-full max-w-[240px]`

#### 10. Split Banner Image Height (index.php)
- **ไฟล์**: `index.php` (บรรทัด 105, 124)
- **ปัญหา**: `h-48` (12rem = 192px) สำหรับภาพใน banners บน mobile อาจสูงเกินสัดส่วน
- **แนวทางแก้**: เพิ่ม `h-36` สำหรับ mobile
```html
<div class="... h-36 sm:h-48 sm:h-auto relative">
```

#### 11. FAQ First Item — Content แสดงเปิดค้างไว้
- **ไฟล์**: `faq.php` (บรรทัด 56-64)
- **ปัญหา**: FAQ item ที่ 1 มี `<div class="faq-answer ...">` ที่มี `max-h-0` โดยไม่มี `padding-bottom: 0` ในข้อมูลเริ่มต้น — แต่เนื้อหา paragraph แสดงโดยค่าเริ่มต้นเพราะไม่มีการซ่อนด้วย JS จนกว่าจะคลิก — คำตอบแรกอาจแสดงทันที
- **แนวทางแก้**: ตั้ง `max-height: 0; padding: 0; overflow: hidden` เป็นค่าเริ่มต้น หรือซ่อนด้วย CSS

#### 12. About Page Missing CSS Animation `reveal-visible` 
- **ไฟล์**: `about.php` (ปลายไฟล์)
- **ปัญหา**: มีการเพิ่ม `.reveal-visible` class ผ่าน JS IntersectionObserver แต่ไม่มี CSS `.reveal-visible` transition ใน style.css
- **แนวทางแก้**: เพิ่มใน style.css
```css
.reveal {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease-out;
}
.reveal-visible {
    opacity: 1;
    transform: translateY(0);
}
```

---

## ผลการตรวจสอบตาม Checklist

| # | รายการตรวจสอบ | สถานะ | หมายเหตุ |
|---|---------------|--------|----------|
| 1 | ทุกหน้า responsive 375px | ✅ ผ่าน | ใช้ Tailwind responsive classes ถูกต้อง |
| 2 | Mobile hamburger เปิด/ปิด | ✅ ผ่าน | JS toggleMenu() ทำงาน |
| 3 | Mobile dropdown "ประกันของเรา" | ✅ ผ่าน | mob-sub class + JS toggle |
| 4 | Category grid 1-2-4 columns | ✅ ผ่าน | `grid-cols-2 md:grid-cols-4 lg:grid-cols-8` |
| 5 | Product cards grid responsive | ✅ ผ่าน | `grid-cols-1 md:grid-cols-2 lg:grid-cols-3` |
| 6 | Contact form 1 column บน mobile | ✅ ผ่าน | `grid-cols-1 lg:grid-cols-2` |
| 7 | Footer responsive | ⚠️ ผ่าน | ต้องเพิ่ม padding-bottom |
| 8 | Cookies banner ไม่บัง content | ⚠️ มีปัญหา | ทับกับ floating button |
| 9 | Floating button ไม่ทับเนื้อหา | ⚠️ มีปัญหา | animation bounce + ทับ cookie banner |
| 10 | Table/content overflow | ✅ ผ่าน | ไม่มี tables, content ใช้ prose + max-width |
| 11 | FAQ accordion เปิด/ปิดบน mobile | ✅ ผ่าน | toggleFaq() ทำงาน |
| 12 | Breadcrumb responsive | ⚠️ ผ่าน | truncate ทำงาน แต่ width อาจน้อยไป |
| 13 | Images ไม่ล้น viewport | ✅ ผ่าน | object-cover + w-full h-full |
| 14 | CTA buttons ไม่เหลื่อม | ⚠️ ผ่าน | max-w-[200px] อาจแคบ |

---

## สรุปการทำงานใน Browser

| หน้าที่ตรวจสอบ | Status | JS Errors | Mobile Menu | Notes |
|---------------|--------|-----------|-------------|-------|
| index.php | ✅ | 0 | ✅ | Hero text size issue |
| about.php | ✅ | 0 | ✅ | min-height 85vh บน mobile |
| life.php | ✅ | 0 | ✅ | เนื้อหามาก — single column OK |
| health.php | ✅ | 0 | ✅ | สามารถ scroll ได้ |
| general.php | ✅ | 0 | ✅ | เนื้อหาน้อยที่สุด |
| articles.php | ✅ | 0 | ✅ | JS render ทำงาน |
| article.php | ✅ | 0 | ✅ | Breadcrumb truncate |
| career.php | ✅ | 0 | ✅ | Forms OK |
| seminar.php | ✅ | 0 | ✅ | Cards OK |
| testimonials.php | ✅ | 0 | ✅ | 12 cards OK |
| contact.php | ✅ | 0 | ✅ | Form + info layout OK |
| faq.php | ✅ | 0 | ✅ | FAQ accordion OK |
| claim.php | ✅ | 0 | ✅ | Steps layout OK |
| privacy.php | ✅ | 0 | ✅ | Legal text OK |
| terms.php | ✅ | 0 | ✅ | Legal text OK |
| thankyou.php | ✅ | 0 | ✅ | Centered layout OK |
| 404.php | ✅ | 0 | ✅ | Centered layout OK |

---

## หมายเหตุ

1. **ต้องเปิด PHP server** ก่อนเรียกใช้งาน (`php -S 127.0.0.1:8080 -t "D:/prakanhub/prakanhub.com"`)
2. **Desktop dropdown menu** (hover "ประกันของเรา") ใช้ `group-hover` ซึ่งบน mobile/touch devices จะไม่ทำงาน — ต้องพัฒนาทางเลือกด้วย click event หรือ tap เพื่อให้ใช้งานบน mobile ได้
3. **ไอคอน** ใช้ Lucide CDN + สร้างด้วย JS `lucide.createIcons()` ซึ่งอาจ delay ในการโหลดครั้งแรก
4. **ภาพ Unsplash** ใช้ CDN อาจช้าในเน็ตช้า — ไม่มีผลต่อ responsive layout

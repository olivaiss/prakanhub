-- ═══════════════════════════════════════════════════════════
-- prakanhub.com — Database Schema (MariaDB 10.6.26)
-- DB: ricecra_prakanhub / user: ricecra_prakanhub
-- ═══════════════════════════════════════════════════════════
SET NAMES utf8mb4;

-- ─── ผู้ดูแลระบบ ───
CREATE TABLE IF NOT EXISTS admin_users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    display_name VARCHAR(100) NOT NULL DEFAULT '',
    role ENUM('admin','editor') NOT NULL DEFAULT 'admin',
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── ตั้งค่าเว็บ (SEO, ข้อมูลติดต่อ, hero, ฯลฯ) ───
CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value LONGTEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── หมวดหมู่ประกัน (หน้าแรก category grid) ───
CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) NOT NULL DEFAULT 'shield',
    description VARCHAR(255) NOT NULL DEFAULT '',
    link_url VARCHAR(255) NOT NULL DEFAULT '',
    is_dark TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── บทความ ───
CREATE TABLE IF NOT EXISTS articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NULL UNIQUE,
    tag VARCHAR(50) NOT NULL DEFAULT '',
    img VARCHAR(500) NOT NULL DEFAULT '',
    excerpt VARCHAR(500) NOT NULL DEFAULT '',
    content LONGTEXT NULL,
    author VARCHAR(100) NOT NULL DEFAULT '',
    publish_date DATE NULL,
    seo_title VARCHAR(255) NULL,
    seo_desc VARCHAR(500) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── รีวิวลูกค้า ───
CREATE TABLE IF NOT EXISTS testimonials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL DEFAULT '',
    rating TINYINT NOT NULL DEFAULT 5,
    message TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── สัมมนา/คอร์ส ───
CREATE TABLE IF NOT EXISTS seminars (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    img VARCHAR(500) NOT NULL DEFAULT '',
    event_date DATE NULL,
    location VARCHAR(255) NOT NULL DEFAULT '',
    description TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── FAQ ───
CREATE TABLE IF NOT EXISTS faqs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(500) NOT NULL,
    answer TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── หน้าเนื้อหา (about, claim, terms, privacy, career) ───
CREATE TABLE IF NOT EXISTS pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NULL,
    seo_title VARCHAR(255) NULL,
    seo_desc VARCHAR(500) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── ผลิตภัณฑ์ประกัน (life/health/general) ───
CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL DEFAULT 'life',  -- life | health | general
    title VARCHAR(255) NOT NULL,
    desc_text VARCHAR(500) NOT NULL DEFAULT '',
    img VARCHAR(500) NOT NULL DEFAULT '',
    link_url VARCHAR(255) NOT NULL DEFAULT '',
    badge VARCHAR(50) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── รายการเมนู (header/footer) ───
CREATE TABLE IF NOT EXISTS menus (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    location ENUM('header','footer') NOT NULL DEFAULT 'footer',
    label VARCHAR(100) NOT NULL,
    link_url VARCHAR(255) NOT NULL,
    target ENUM('_self','_blank') NOT NULL DEFAULT '_self',
    sort_order INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── สมาชิก (member system) ───
CREATE TABLE IF NOT EXISTS members (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_code VARCHAR(18) NOT NULL UNIQUE,
    display_name VARCHAR(100) NOT NULL DEFAULT '',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ═══════════════════════════════════════════════════════════
-- Seed ข้อมูลเริ่มต้น
-- ═══════════════════════════════════════════════════════════

-- Admin user: admin / admin123 (เปลี่ยนหลัง login ครั้งแรก!)
INSERT INTO admin_users (username, password_hash, display_name) VALUES
('admin', '$2y$10$K9XmO8n1yQhZvWcQnJq2f.6lSfBwQyYQnQvUeL2Q8nWfHtqYdGj6', 'ผู้ดูแลระบบ');

-- Settings เริ่มต้น (SEO + ติดต่อ)
INSERT INTO settings (setting_key, setting_value) VALUES
('site_title', 'ที่ปรึกษาประกันชีวิตและการเงิน | ประกันจริงใจ by ปกป้อง | Insurance Advisor - Allianz Ayudhya'),
('site_description', 'ที่ปรึกษาประกันชีวิตและการเงิน Allianz Ayudhya วางแผนอนาคตอย่างมั่นใจ ครบทุกความคุ้มครอง ด้วยประสบการณ์และความจริงใจ'),
('site_keywords', 'ประกันชีวิต, ประกันสุขภาพ, ประกันโรคร้ายแรง, ที่ปรึกษาประกัน, Allianz'),
('og_image', '/assets/image/hero-portrait.webp'),
('phone', '092-515-9991'),
('line_id', '@945ampel'),
('line_url', 'https://line.me/R/ti/p/@945ampel'),
('facebook_url', 'https://www.facebook.com/pp.insure168'),
('youtube_url', 'https://www.youtube.com/'),
('instagram_url', 'https://www.instagram.com/'),
('tiktok_url', 'https://www.tiktok.com/'),
('hero_title', 'วางแผนอนาคต อย่างมั่นใจ'),
('hero_subtitle', 'พร้อมดูแลทุกเป้าหมายชีวิต'),
('hero_desc', 'ครบทุกความคุ้มครอง วางแผนให้เหมาะกับคุณ ด้วยประสบการณ์และความจริงใจ'),
('career_title', 'ร่วมงานกับเรา สร้างรายได้ ไม่จำกัด'),
('career_desc', 'เติบโตไปพร้อมกัน กับทีมคุณภาพ'),
('address', 'กรุงเทพมหานคร ประเทศไทย'),
('member_code_default', '123456789012345678');

-- หมวดหมู่ประกันเริ่มต้น (12 การ์ด)
INSERT INTO categories (title, slug, icon, description, link_url, is_dark, sort_order) VALUES
('ประกันชีวิต', 'life', 'heart', 'คุ้มครองชีวิต และคนที่คุณรัก', '/life.php', 0, 1),
('ประกันสุขภาพ', 'health', 'activity', 'คุ้มครองค่ารักษา โรคร้ายภัยเจอ', '/health.php', 0, 2),
('ประกันโรคร้ายแรง', 'critical', 'shield-alert', 'เจอ จ่าย จบ ไม่กระทบอนาคต', '/health.php#critical', 0, 3),
('ประกันอุบัติเหตุ', 'accident', 'footprints', 'คุ้มครอง 24 ชม. ทั่วโลก', '/health.php#accident', 0, 4),
('ประกันเด็ก', 'kids', 'baby', 'วางแผนอนาคต ให้ลูกน้อย', '/life.php#kids', 0, 5),
('ประกันออมทรัพย์', 'savings', 'landmark', 'ออมเงิน พร้อมรับผลตอบแทน', '/life.php#savings', 0, 6),
('ประกันบำนาญ', 'pension', 'home', 'เกษียณสบาย มั่นใจในทุกระดับ', '/life.php#pension', 1, 7),
('ประกันชดเชยรายได้', 'income', 'dollar-sign', 'เจ็บป่วยไม่หยุด รายได้ไม่ขาด', '/health.php#income', 0, 8),
('ประกันกลุ่ม', 'group', 'users', 'คุ้มครองพนักงานทั้งองค์กร', '/general.php#group', 0, 9),
('ประกันนิติบุคคล', 'corporate', 'building-2', 'บริหารความเสี่ยงธุรกิจ', '/general.php#corporate', 1, 10),
('ประกันรถยนต์', 'car', 'car', 'คุ้มครองรถยนต์ครบวงจร', '/general.php#car', 0, 11),
('ประกันเดินทาง', 'travel', 'plane', 'เดินทางอุ่นใจ ทั่วโลก', '/general.php#travel', 0, 12);

-- หน้าเนื้อหาเริ่มต้น
INSERT INTO pages (slug, title, content, seo_title, seo_desc) VALUES
('about', 'เกี่ยวกับผม', '<h2>ประกันจริงใจ by ปกป้อง</h2><p>ตัวแทนประกันชีวิต Allianz Ayudhya ประสบการณ์ 10+ ปี ในวงการประกันชีวิต</p>', 'เกี่ยวกับผม | ประกันจริงใจ by ปกป้อง', 'ทำความรู้จัก ปกป้อง ที่ปรึกษาประกันชีวิตและการเงิน'),
('career', 'ร่วมงานกับเรา', '<h2>ร่วมงานกับเรา</h2><p>สร้างรายได้ไม่จำกัด กับทีมคุณภาพ อบรมฟรีโดยมืออาชีพ</p>', 'ร่วมงานกับเรา | ประกันจริงใจ by ปกป้อง', 'ร่วมงานกับทีมประกันมืออาชีพ'),
('claim', 'ขั้นตอนการเคลม', '<h2>ขั้นตอนการเคลม</h2><p>ติดต่อที่ปรึกษาของคุณ หรือโทร 092-515-9991 เพื่อรับคำแนะนำการเคลม</p>', 'ขั้นตอนการเคลม | ประกันจริงใจ by ปกป้อง', 'ขั้นตอนการเคลมประกันง่ายๆ'),
('privacy', 'นโยบายความเป็นส่วนตัว', '<h2>นโยบายความเป็นส่วนตัว (PDPA)</h2><p>ข้อมูลของคุณจะถูกเก็บเป็นความลับ ใช้สำหรับการจัดทำประกันเท่านั้น</p>', 'นโยบายความเป็นส่วนตัว | ประกันจริงใจ by ปกป้อง', 'นโยบายความเป็นส่วนตัว'),
('terms', 'ข้อกำหนดและเงื่อนไข', '<h2>ข้อกำหนดและเงื่อนไข</h2><p>การใช้งานเว็บไซต์นี้อยู่ภายใต้ข้อกำหนดดังนี้</p>', 'ข้อกำหนดและเงื่อนไข | ประกันจริงใจ by ปกป้อง', 'ข้อกำหนดและเงื่อนไขการใช้งานเว็บไซต์');

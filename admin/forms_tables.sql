-- ═══ ตารางใหม่: ฟอร์มติดต่อ / สมัครตัวแทน / ฟอร์มประกัน / ความคืบหน้า member ═══

-- ข้อความจากฟอร์มติดต่อ (contact.php)
CREATE TABLE IF NOT EXISTS contacts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    subject VARCHAR(255) NOT NULL DEFAULT '',
    line VARCHAR(100) NOT NULL DEFAULT '',
    message TEXT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ผู้สมัครตัวแทน (career.php)
CREATE TABLE IF NOT EXISTS agent_applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    age VARCHAR(20) NOT NULL DEFAULT '',
    education VARCHAR(255) NOT NULL DEFAULT '',
    experience VARCHAR(255) NOT NULL DEFAULT '',
    line VARCHAR(100) NOT NULL DEFAULT '',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ใบสมัครทำประกันจากฟอร์ม 6 ขั้นตอน (form/index.php) — mirror ลง DB
CREATE TABLE IF NOT EXISTS form_submissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ref_code VARCHAR(30) NOT NULL UNIQUE,
    payload LONGTEXT NULL COMMENT 'JSON ข้อมูลฟอร์มเต็ม',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ความคืบหน้าการเรียนของสมาชิก (member LMS)
CREATE TABLE IF NOT EXISTS member_progress (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    member_code VARCHAR(18) NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    lesson_idx INT NOT NULL DEFAULT 0,
    done TINYINT(1) NOT NULL DEFAULT 1,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_progress (member_code, course_id, lesson_idx)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

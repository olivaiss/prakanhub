<?php
/**
 * ระบบสมาชิก — Config & Auth
 * Login ด้วยรหัส 18 หลัก (ไม่มีฐานข้อมูล — ใช้ session อย่างเดียว)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ให้ getDB() พร้อมใช้ (db.php มี credentials จริง — ถ้าไม่มีให้ fallback)
try {
    require_once __DIR__ . '/../includes/db.php';
} catch (Throwable $e) {
    // DB ไม่พร้อม — ใช้ $MEMBER_CODES fallback
}

/* ═══════════════════════════════════════════════
 * 🔑 รหัสสมาชิกที่ใช้ Login ได้ (18 หลัก)
 * ใครมีรหัสในรายการนี้ก็เข้าได้
 * ⚠️ เปลี่ยนรหัสตรงนี้ได้เลย — เพิ่มได้หลายรหัส
 * ═══════════════════════════════════════════════ */
$MEMBER_CODES = [
    '123456789012345678',   // รหัสตัวอย่าง — เปลี่ยนเป็นรหัสจริง
];

/** ตรวจว่า logged in หรือไม่ */
function member_logged_in(): bool {
    return !empty($_SESSION['member_logged_in']);
}

/** กันหน้า — ถ้ายังไม่ login ให้ redirect ไปหน้า login */
function member_guard(): void {
    if (!member_logged_in()) {
        header('Location: /member/index.php');
        exit;
    }
}

/** ตรวจรหัส 18 หลัก — เช็คจากตาราง members (DB) ก่อน, fallback $MEMBER_CODES */
function member_check_code(string $code): bool {
    global $MEMBER_CODES;
    if (!preg_match('/^[0-9]{18}$/', $code)) {
        return false;
    }
    // DB first (ตาราง members — จัดการผ่าน admin)
    try {
        if (function_exists('getDB')) {
            $__stmt = getDB()->prepare('SELECT id FROM members WHERE member_code = ? AND is_active = 1 LIMIT 1');
            $__stmt->execute([$code]);
            if ($__stmt->fetch()) {
                return true;
            }
        }
    } catch (Throwable $e) {
        // DB ไม่พร้อม — ใช้ $MEMBER_CODES
    }
    // Fallback: รายการรหัสใน config นี้
    return in_array($code, $MEMBER_CODES, true);
}

/** โหลดข้อมูลคอร์ส — จากตาราง courses (DB) ก่อน, fallback: data/courses.json */
function member_courses(): array {
    $courses = [];
    try {
        if (function_exists('getDB')) {
            $__stmt = getDB()->query('SELECT id, title, category, description, thumb, sections FROM courses WHERE is_active = 1 ORDER BY sort_order, id');
            foreach ($__stmt as $__r) {
                $sections = json_decode((string)$__r['sections'], true);
                if (!is_array($sections)) {
                    $sections = [];
                }
                $courses[] = [
                    'id' => (int)$__r['id'],
                    'title' => $__r['title'],
                    'category' => $__r['category'],
                    'desc' => $__r['description'],
                    'thumb' => $__r['thumb'],
                    'sections' => $sections,
                ];
            }
        }
    } catch (Throwable $e) {
        // DB ไม่พร้อม — fallback ด้านล่าง
    }
    if (empty($courses)) {
        $path = __DIR__ . '/data/courses.json';
        if (file_exists($path)) {
            $data = json_decode(file_get_contents($path), true);
            if (is_array($data)) {
                $courses = $data;
            }
        }
    }
    return $courses;
}

/** หาคอร์สตาม id */
function member_course(int $id): ?array {
    foreach (member_courses() as $c) {
        if ((int)$c['id'] === $id) {
            return $c;
        }
    }
    return null;
}

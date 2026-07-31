<?php
/**
 * ระบบสมาชิก — Config & Auth
 * Login ด้วยรหัส 18 หลัก (ไม่มีฐานข้อมูล — ใช้ session อย่างเดียว)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

/** ตรวจรหัส 18 หลัก */
function member_check_code(string $code): bool {
    global $MEMBER_CODES;
    if (!preg_match('/^[0-9]{18}$/', $code)) {
        return false;
    }
    return in_array($code, $MEMBER_CODES, true);
}

/** โหลดข้อมูลคอร์สจาก JSON */
function member_courses(): array {
    $path = __DIR__ . '/data/courses.json';
    if (!file_exists($path)) {
        return [];
    }
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : [];
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

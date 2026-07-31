<?php
/**
 * Admin — Authentication & Helpers
 */
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** ตรวจว่า login แล้ว */
function admin_logged_in(): bool {
    return !empty($_SESSION['admin_id']);
}

/** กันหน้า admin — ยังไม่ login → ไปหน้า login */
function admin_guard(): void {
    if (!admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/** login ด้วย username/password */
function admin_login(string $username, string $password): bool {
    $db = admin_db();
    $stmt = $db->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$user['id'];
        $_SESSION['admin_name'] = $user['display_name'] ?: $user['username'];
        $_SESSION['admin_role'] = $user['role'];
        $db->prepare('UPDATE admin_users SET last_login = NOW() WHERE id = ?')->execute([$user['id']]);
        return true;
    }
    return false;
}

/** logout */
function admin_logout(): void {
    $_SESSION = [];
    session_destroy();
}

/** ชื่อ admin ปัจจุบัน */
function admin_name(): string {
    return $_SESSION['admin_name'] ?? 'Admin';
}

/** Flash message */
function admin_flash(string $msg, string $type = 'success'): void {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

/** อ่าน + ล้าง flash */
function admin_get_flash(): ?array {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

/** escape HTML */
function admin_e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

/** redirect กลับ */
function admin_back(string $fallback = 'index.php'): void {
    $ref = $_SERVER['HTTP_REFERER'] ?? '';
    header('Location: ' . ($ref ?: $fallback));
    exit;
}

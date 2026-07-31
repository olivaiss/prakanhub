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

/** login ด้วย username/password — มี rate-limit กันเดารหัส */
function admin_login(string $username, string $password): bool {
    // Rate limit: 5 ครั้งผิด / 15 นาที ต่อ IP
    if (admin_login_blocked()) {
        return false;
    }
    $db = admin_db();
    $stmt = $db->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        unset($_SESSION['admin_login_fails']);
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$user['id'];
        $_SESSION['admin_name'] = $user['display_name'] ?: $user['username'];
        $_SESSION['admin_role'] = $user['role'];
        $db->prepare('UPDATE admin_users SET last_login = NOW() WHERE id = ?')->execute([$user['id']]);
        return true;
    }
    $_SESSION['admin_login_fails'][] = time();
    $_SESSION['admin_login_fails'] = array_values(array_filter($_SESSION['admin_login_fails'], fn($t) => $t > time() - 900));
    return false;
}

/** ตรวจว่าโดน lock login หรือยัง (5 ครั้งผิดใน 15 นาที) */
function admin_login_blocked(): bool {
    $fails = $_SESSION['admin_login_fails'] ?? [];
    $fails = array_values(array_filter($fails, fn($t) => $t > time() - 900));
    $_SESSION['admin_login_fails'] = $fails;
    return count($fails) >= 5;
}

/** เหลือเวลากี่วินาทีถึงจะลองใหม่ได้ */
function admin_login_lock_seconds(): int {
    $fails = $_SESSION['admin_login_fails'] ?? [];
    if (count($fails) < 5) return 0;
    $oldest = min($fails);
    return max(0, ($oldest + 900) - time());
}

/** CSRF token (ต่อ session) */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

/** ตรวจ CSRF token จาก POST — fail → 404 (กัน CSRF) */
function csrf_verify(): void {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sent = $_POST['csrf'] ?? '';
        if (!hash_equals(csrf_token(), (string)$sent)) {
            http_response_code(404);
            exit('Invalid request.');
        }
    }
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

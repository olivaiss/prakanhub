<?php
/**
 * Admin — Database Connection (SAMPLE)
 * ⚠️ คัดลอกไฟล์นี้เป็น db.php แล้วใส่ค่าจริง (db.php ถูกตัดออกจาก git)
 * ตัวอย่าง: cp admin/includes/db.sample.php admin/includes/db.php
 */

define('ADMIN_DB_HOST', 'localhost');
define('ADMIN_DB_NAME', 'your_database_name');
define('ADMIN_DB_USER', 'your_database_user');
define('ADMIN_DB_PASS', 'your_database_password');

function admin_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=' . ADMIN_DB_HOST . ';dbname=' . ADMIN_DB_NAME . ';charset=utf8mb4',
                ADMIN_DB_USER,
                ADMIN_DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            die('Database connection failed: ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}

/** ดึงค่าจากตาราง settings */
function admin_setting(string $key, string $default = ''): string {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            foreach (admin_db()->query('SELECT setting_key, setting_value FROM settings') as $row) {
                $cache[$row['setting_key']] = (string)$row['setting_value'];
            }
        } catch (Exception $e) {
            return $default;
        }
    }
    return $cache[$key] ?? $default;
}

/** บันทึกค่า settings */
function admin_set_setting(string $key, string $value): void {
    $db = admin_db();
    $stmt = $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                          ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
    $stmt->execute([$key, $value]);
}

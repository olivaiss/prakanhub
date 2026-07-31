<?php
/**
 * Database Configuration — SAMPLE FILE
 * ⚠️ คัดลอกไฟล์นี้เป็น db.php แล้วใส่ค่าจริง (db.php ถูกตัดออกจาก git)
 * ตัวอย่าง: cp includes/db.sample.php includes/db.php
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_CHARSET', 'utf8mb4');

/**
 * Get PDO database connection
 */
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // Only show error details in development
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    return $pdo;
}

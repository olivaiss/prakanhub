<?php
/**
 * Backup DB — ดาวน์โหลด SQL dump ทั้งหมด (ไม่มี mysqldump ก็ใช้ PHP dump)
 */
require_once __DIR__ . '/includes/auth.php';
admin_guard();

if (($_GET['do'] ?? '') !== 'dump') {
    header('Location: index.php');
    exit;
}

$db = admin_db();
$tables = $db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

$sql = "-- prakanhub.com database backup\n-- Generated: " . date('Y-m-d H:i:s') . "\n-- DB: " . ADMIN_DB_NAME . "\n\n";
$sql .= "SET NAMES utf8mb4;\n\n";

foreach ($tables as $t) {
    $sql .= "-- ----------------------------\n-- Table: `$t`\n-- ----------------------------\n";
    $sql .= "DROP TABLE IF EXISTS `$t`;\n";

    $create = $db->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_NUM);
    $sql .= $create[1] . ";\n\n";

    $rows = $db->query("SELECT * FROM `$t`")->fetchAll();
    if ($rows) {
        foreach ($rows as $row) {
            $vals = array_map(function ($v) use ($db) {
                if ($v === null) return 'NULL';
                return $db->quote((string)$v);
            }, array_values($row));
            $sql .= "INSERT INTO `$t` VALUES (" . implode(', ', $vals) . ");\n";
        }
        $sql .= "\n";
    }
}

$filename = 'prakanhub-backup-' . date('Ymd-His') . '.sql';
header('Content-Type: application/sql; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($sql));
echo $sql;
exit;

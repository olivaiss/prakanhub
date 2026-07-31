<?php
/**
 * Member Progress API — บันทึก/อ่านความคืบหน้าการเรียน (DB)
 * POST: course_id, lesson_idx, done (0/1) → upsert
 * GET: ?course_id=N → JSON [lesson_idx, ...]
 */
require_once __DIR__ . '/config.php';
member_guard();
header('Content-Type: application/json; charset=utf-8');

$code = $_SESSION['member_code'] ?? '';
if ($code === '') { echo json_encode(['ok' => false, 'error' => 'no session']); exit; }

try {
    if (!function_exists('getDB')) {
        throw new Exception('db unavailable');
    }
    $db = getDB();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $courseId = (int)($_POST['course_id'] ?? 0);
        $lessonIdx = (int)($_POST['lesson_idx'] ?? 0);
        $done = !empty($_POST['done']) ? 1 : 0;
        $db->prepare('INSERT INTO member_progress (member_code, course_id, lesson_idx, done) VALUES (?,?,?,?)
                      ON DUPLICATE KEY UPDATE done = VALUES(done)')
           ->execute([$code, $courseId, $lessonIdx, $done]);
        echo json_encode(['ok' => true]);
        exit;
    }

    $courseId = (int)($_GET['course_id'] ?? 0);
    $stmt = $db->prepare('SELECT lesson_idx FROM member_progress WHERE member_code = ? AND course_id = ? AND done = 1');
    $stmt->execute([$code, $courseId]);
    $list = array_map('intval', array_column($stmt->fetchAll(), 'lesson_idx'));
    echo json_encode(['ok' => true, 'done' => $list]);
    exit;
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
    exit;
}

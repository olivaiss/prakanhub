<?php
/**
 * ⚠️ ONE-TIME INSTALLER — รัน schema บน production DB แล้วลบไฟล์นี้ทิ้ง
 * เข้าถึงได้ผ่าน HTTP: /admin/_install.php?go=1
 */
header('Content-Type: text/plain; charset=utf-8');
if (($_GET['go'] ?? '') !== '1') { echo "ไปที่ /admin/_install.php?go=1"; exit; }

$dbFile = __DIR__ . '/includes/db.php';
if (!file_exists($dbFile)) { echo "missing db.php"; exit; }
require_once $dbFile;

$pdo = admin_db();
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

function run_sql_file(PDO $pdo, string $path): array {
    $sql = file_get_contents($path);
    $sql = preg_replace('/^--.*$/m', '', $sql);          // ตัด comment
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    $done = 0;
    foreach ($statements as $stmt) {
        if ($stmt === '') continue;
        try {
            $pdo->exec($stmt);
            $done++;
        } catch (Throwable $e) {
            echo "  SKIP: " . mb_substr($e->getMessage(), 0, 100) . "\n";
        }
    }
    return ['done' => $done];
}

echo "== schema.sql ==\n";
run_sql_file($pdo, __DIR__ . '/schema.sql');

echo "== courses_table.sql ==\n";
run_sql_file($pdo, __DIR__ . '/courses_table.sql');

// ─── Seed เฉพาะตารางว่าง ───
$tables = ['admin_users', 'settings', 'categories', 'pages', 'products', 'articles', 'testimonials', 'seminars', 'faqs', 'menus', 'members', 'courses'];
foreach ($tables as $t) {
    try {
        $cnt = (int)$pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
        echo "$t: $cnt rows\n";
        if ($cnt > 0) continue;

        if ($t === 'admin_users') {
            $hash = password_hash('admin123', PASSWORD_BCRYPT);
            $pdo->prepare("INSERT INTO admin_users (username, password_hash, display_name, role) VALUES ('admin', ?, 'ผู้ดูแลระบบ', 'admin')")->execute([$hash]);
            echo "  seeded admin/admin123\n";
        } elseif ($t === 'settings') {
            // ค่าเริ่มต้นสำคัญ (SEO/ติดต่อ) — sync จาก local ค่าเดียวกับ header fallback
            $defaults = [
                'title' => 'ที่ปรึกษาประกันชีวิตและการเงิน',
                'description' => 'ที่ปรึกษาประกันชีวิตและการเงิน Allianz Ayudhya วางแผนอนาคตอย่างมั่นใจ ครบทุกความคุ้มครอง ด้วยประสบการณ์และความจริงใจ',
                'keywords' => 'ประกันชีวิต, ประกันสุขภาพ, ประกันโรคร้ายแรง, ที่ปรึกษาประกัน, Allianz',
                'og_image' => '/assets/image/hero-portrait.webp',
                'phone' => '092-515-9991',
                'line_id' => '@945ampel',
                'line_url' => 'https://line.me/R/ti/p/@945ampel',
                'facebook_url' => 'https://www.facebook.com/pp.insure168',
                'youtube_url' => 'https://www.youtube.com/',
                'instagram_url' => 'https://www.instagram.com/',
                'tiktok_url' => 'https://www.tiktok.com/',
                'address' => 'กรุงเทพมหานคร ประเทศไทย',
                'hero_title' => 'วางแผนอนาคต<br>อย่างมั่นใจ',
                'hero_subtitle' => 'พร้อมดูแลทุกเป้าหมายชีวิต',
                'hero_desc' => "ครบทุกความคุ้มครอง วางแผนให้เหมาะกับคุณ\nด้วยประสบการณ์และความจริงใจ",
                'career_title' => 'ร่วมงานกับเรา สร้างรายได้ ไม่จำกัด',
                'career_desc' => 'เติบโตไปพร้อมกัน กับทีมคุณภาพ',
                'stat_years' => '10+ ปี',
                'stat_clients' => '1,000+ คน',
                'stat_qualification' => 'MDRT',
            ];
            $ins = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?,?)");
            foreach ($defaults as $k => $v) $ins->execute([$k, $v]);
            echo "  seeded settings (" . count($defaults) . ")\n";
        }
    } catch (Throwable $e) {
        echo "  $t ERROR: " . mb_substr($e->getMessage(), 0, 120) . "\n";
    }
}

echo "\nDONE\n";

<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'หน้าเกี่ยวกับผม';
$adminMenu = 'about';

// ═══ ข้อมูล default (ใช้เมื่อกด "โหลดค่าเริ่มต้น" — แสดงตัวอย่าง) ═══
$DEFAULTS = [
    'hero' => [
        'badge' => 'Insurance Advisor — Allianz Ayudhya',
        'title' => 'ประกันจริงใจ by ปกป้อง',
        'subtitle' => 'ที่ปรึกษาประกันชีวิตมืออาชีพ',
        'items' => json_encode([
            ['stat' => '1,000+', 'label' => 'ครอบครัวที่ไว้วางใจ', 'icon' => 'users'],
            ['stat' => 'MDRT', 'label' => 'รางวัลระดับโลก', 'icon' => 'award'],
            ['stat' => '10+', 'label' => 'ปีประสบการณ์', 'icon' => 'clock'],
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        'quote' => 'เพราะทุกครอบครัวสมควรได้รับความคุ้มครองที่ดีที่สุด — ผมพร้อมเป็นที่ปรึกษาที่คุณวางใจได้',
    ],
    'experience' => [
        'badge' => 'ประสบการณ์',
        'title' => 'ประสบการณ์กว่า 10 ปี',
        'subtitle' => 'ความเชี่ยวชาญที่ผ่านการพิสูจน์ พร้อมดูแลคุณและครอบครัวอย่างมืออาชีพ',
        'items' => json_encode([
            'stats' => [
                ['icon' => 'users', 'title' => 'ลูกค้า 1,000+', 'desc' => 'ครอบครัวที่ไว้วางใจให้ผมดูแลแผนประกันและความคุ้มครอง'],
                ['icon' => 'file-text', 'title' => 'กรมธรรม์ 2,500+ ฉบับ', 'desc' => 'จำนวนกรมธรรม์ที่ดูแลและให้บริการครบวงจร'],
                ['icon' => 'landmark', 'title' => 'สินเชื่อ 500+ รายการ', 'desc' => 'ช่วยเหลือด้านสินเชื่อเพื่อให้ลูกค้าบรรลุเป้าหมาย'],
                ['icon' => 'award', 'title' => 'รางวัล MDRT', 'desc' => 'มาตรฐานระดับโลกด้านการเป็นที่ปรึกษาประกันมืออาชีพ'],
            ],
            'expertise' => [
                ['icon' => 'heart', 'title' => 'ประกันชีวิต', 'desc' => 'วางแผนความคุ้มครองชีวิตที่เหมาะสมกับทุกช่วงวัย'],
                ['icon' => 'activity', 'title' => 'ประกันสุขภาพ', 'desc' => 'ค่ารักษาพยาบาลเหมาจ่าย โรคร้ายแรง คุ้มครองสูงสุด'],
                ['icon' => 'shield', 'title' => 'วางแผนการเงิน', 'desc' => 'ประกันออมทรัพย์ ประกันบำนาญ ลดหย่อนภาษี'],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        'quote' => '',
    ],
    'philosophy' => [
        'badge' => 'ปรัชญาการทำงาน',
        'title' => 'ความเชื่อในการทำงานของผม',
        'subtitle' => 'หัวใจสำคัญที่ทำให้ผมเป็นที่ปรึกษาที่แตกต่าง',
        'items' => json_encode([
            ['icon' => 'ear', 'title' => 'ฟังอย่างเข้าใจ', 'desc' => 'ทุกความต้องการเริ่มจากการฟัง ผมฟังลูกค้าอย่างตั้งใจ เพื่อเข้าใจความต้องการที่แท้จริง ไม่ใช่แค่ขายประกัน', 'color' => 'blue'],
            ['icon' => 'search', 'title' => 'วิเคราะห์อย่างรอบคอบ', 'desc' => 'เปรียบเทียบทุกทางเลือกอย่างละเอียด เพื่อให้ได้แผนประกันที่เหมาะสม คุ้มค่า และตรงกับความต้องการมากที่สุด', 'color' => 'green'],
            ['icon' => 'heart-handshake', 'title' => 'แนะนำด้วยความจริงใจ', 'desc' => 'ไม่แนะนำอะไรที่ไม่จำเป็น ผมให้คำแนะนำที่ตรงไปตรงมา เหมาะสมกับงบประมาณและเป้าหมายของลูกค้า', 'color' => 'red'],
            ['icon' => 'clock', 'title' => 'ดูแลอย่างต่อเนื่อง', 'desc' => 'ความสัมพันธ์ไม่จบแค่การขาย ผมดูแลลูกค้าทุกคนอย่างต่อเนื่อง ตลอดอายุกรมธรรม์', 'color' => 'purple'],
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        'quote' => '"ผมไม่ได้ขายประกัน — ผมช่วยให้คุณและครอบครัวมีอนาคตที่มั่นคง"',
    ],
    'cta' => [
        'badge' => 'ปรึกษาวันนี้ รับคำแนะนำดีๆ ฟรี!',
        'title' => 'พร้อมดูแลคุณและครอบครัว',
        'subtitle' => 'ไม่ว่าคุณจะสนใจประกันชีวิต สุขภาพ ออมทรัพย์ หรือวางแผนการเงิน ผมพร้อมให้คำปรึกษาฟรี ไม่มีค่าใช้จ่าย ไม่มีข้อผูกมัด',
        'items' => json_encode([
            ['icon' => 'clock', 'text' => 'ปรึกษาฟรี ไม่มีค่าใช้จ่าย'],
            ['icon' => 'shield', 'text' => 'ข้อมูลถูกต้อง ครบถ้วน'],
            ['icon' => 'heart', 'text' => 'ไม่มีข้อผูกมัดใดๆ'],
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        'quote' => '',
    ],
];

// ─── Save ───
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $key = $_POST['key'] ?? '';
    if (!isset($DEFAULTS[$key])) { header('Location: about.php'); exit; }
    try {
        $items = trim($_POST['items'] ?? '');
        if ($items !== '') {
            json_decode($items, true);
            if (json_last_error() !== JSON_ERROR_NONE) throw new Exception('JSON ผิดรูปแบบ: ' . json_last_error_msg());
        }
        $stmt = $db->prepare('UPDATE about_sections SET badge=?, title=?, subtitle=?, items=?, quote=?, is_active=? WHERE section_key=?');
        $stmt->execute([
            trim($_POST['badge'] ?? ''),
            trim($_POST['title'] ?? ''),
            trim($_POST['subtitle'] ?? ''),
            $items,
            trim($_POST['quote'] ?? ''),
            !empty($_POST['is_active']) ? 1 : 0,
            $key,
        ]);
        admin_flash('บันทึก section "' . $DEFAULTS[$key]['title'] . '" เรียบร้อย');
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: about.php?edit=' . $key);
    exit;
}

$edit = $_GET['edit'] ?? 'hero';
if (!isset($DEFAULTS[$edit])) $edit = 'hero';

$rows = [];
foreach ($DEFAULTS as $k => $d) {
    $stmt = $db->prepare('SELECT * FROM about_sections WHERE section_key=?');
    $stmt->execute([$k]);
    $r = $stmt->fetch();
    $rows[$k] = $r ?: array_merge(['badge' => $d['badge'], 'title' => $d['title'], 'subtitle' => $d['subtitle'], 'items' => $d['items'], 'quote' => $d['quote'], 'is_active' => 1], []);
}

include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">หน้าเกี่ยวกับผม (about.php)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">หน้าเกี่ยวกับผม</li></ol>
        </div>
        <div class="col-md-4"><div class="float-end"><a href="../about.php" target="_blank" class="btn btn-primary waves-effect"><i class="fa fa-external-link me-1"></i> ดูหน้าเว็บ</a></div></div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3">
    <?php foreach ($DEFAULTS as $k => $d): ?>
    <li class="nav-item"><a class="nav-link <?= $edit === $k ? 'active' : '' ?>" href="about.php?edit=<?= $k ?>"><?= admin_e($d['title']) ?> <?= $rows[$k]['is_active'] ? '' : '<span class="badge bg-secondary ms-1">ปิด</span>' ?></a></li>
    <?php endforeach; ?>
</ul>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">แก้ไข: <?= admin_e($DEFAULTS[$edit]['title']) ?></h4>
                <p class="card-title-desc mb-3">ข้อมูลทั้งหมดบนหน้า about.php แก้ไขได้ที่นี่ — เปิด/ปิดแสดงผลได้</p>
                <form method="post">
                    <input type="hidden" name="key" value="<?= admin_e($edit) ?>">
                    <div class="row g-3">
                        <div class="col-md-8"><label class="form-label">ป้ายหัว section (badge)</label><input type="text" class="form-control" name="badge" value="<?= admin_e($rows[$edit]['badge']) ?>"></div>
                        <div class="col-md-4"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="e_active" value="1" <?= $rows[$edit]['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="e_active">แสดงผล</label></div></div>
                        <div class="col-md-12"><label class="form-label">หัวข้อหลัก (title)</label><input type="text" class="form-control" name="title" value="<?= admin_e($rows[$edit]['title']) ?>"></div>
                        <div class="col-12"><label class="form-label">คำอธิบาย (subtitle)</label><textarea class="form-control" name="subtitle" rows="2"><?= admin_e($rows[$edit]['subtitle']) ?></textarea></div>
                        <div class="col-12">
                            <label class="form-label">รายการ (items — JSON)</label>
                            <textarea class="form-control" name="items" rows="12" style="font-family:monospace;font-size:12px"><?= admin_e($rows[$edit]['items']) ?></textarea>
                            <div class="form-text">
                                <button type="button" class="btn btn-sm btn-outline-info mt-1 me-1" onclick="document.getElementsByName('items')[0].value = document.getElementById('default-items').textContent.trim()">โหลดค่าเริ่มต้น</button>
                                ใช้ JSON — ตัวอย่างด้านล่าง (คัดลอกได้)
                            </div>
                        </div>
                        <div class="col-12"><label class="form-label">Quote (สำหรับ hero / philosophy)</label><textarea class="form-control" name="quote" rows="2"><?= admin_e($rows[$edit]['quote']) ?></textarea></div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        </div>
                    </div>
                </form>
                <pre id="default-items" class="d-none"><?= admin_e($DEFAULTS[$edit]['items']) ?></pre>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">💡 โครงสร้าง items (JSON)</h4>
                <p class="card-title-desc">แต่ละ section ใช้โครงสร้างต่างกัน:</p>
                <ul class="text-muted small" style="line-height:1.8">
                    <li><b>hero</b>: array ของ {stat, label, icon}</li>
                    <li><b>experience</b>: object {stats: [...], expertise: [...]} — แต่ละ {icon, title, desc}</li>
                    <li><b>philosophy</b>: array ของ {icon, title, desc, color} — color: blue/green/red/purple</li>
                    <li><b>cta</b>: array ของ {icon, text}</li>
                </ul>
                <hr>
                <h6>ไอคอนที่ใช้ได้ (lucide)</h6>
                <p class="text-muted small">users, award, clock, file-text, landmark, heart, activity, shield, search, ear, heart-handshake, quote, smartphone, phone, check-circle-2</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

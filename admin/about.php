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

// ═══ Schema ของ item editor (ฟอร์มง่าย ไม่ต้องแตะ JSON) ═══
$itemSchema = [
    'main' => [
        ['name' => 'stat', 'label' => 'ตัวเลข / ข้อความเด่น', 'def' => '1,000+'],
        ['name' => 'label', 'label' => 'คำอธิบาย', 'def' => ''],
        ['name' => 'icon', 'label' => 'ไอคอน', 'type' => 'select', 'def' => 'users'],
    ],
    'stats' => [
        ['name' => 'icon', 'label' => 'ไอคอน', 'type' => 'select', 'def' => 'users'],
        ['name' => 'title', 'label' => 'หัวข้อ', 'def' => ''],
        ['name' => 'desc', 'label' => 'รายละเอียด', 'def' => ''],
    ],
    'expertise' => [
        ['name' => 'icon', 'label' => 'ไอคอน', 'type' => 'select', 'def' => 'shield'],
        ['name' => 'title', 'label' => 'หัวข้อ', 'def' => ''],
        ['name' => 'desc', 'label' => 'รายละเอียด', 'def' => ''],
    ],
];
if ($edit === 'hero') {
    $itemSchema['main'] = [
        ['name' => 'stat', 'label' => 'ตัวเลข / ข้อความเด่น (เช่น 1,000+, MDRT, 10+)', 'def' => ''],
        ['name' => 'label', 'label' => 'คำอธิบายสั้น (เช่น ครอบครัวที่ไว้วางใจ)', 'def' => ''],
        ['name' => 'icon', 'label' => 'ไอคอน', 'type' => 'select', 'def' => 'users'],
    ];
} elseif ($edit === 'philosophy') {
    $itemSchema['main'] = [
        ['name' => 'icon', 'label' => 'ไอคอน', 'type' => 'select', 'def' => 'search'],
        ['name' => 'title', 'label' => 'หัวข้อ', 'def' => ''],
        ['name' => 'desc', 'label' => 'รายละเอียด', 'def' => ''],
        ['name' => 'color', 'label' => 'สี', 'type' => 'select', 'options' => ['blue', 'green', 'red', 'purple'], 'def' => 'blue'],
    ];
} elseif ($edit === 'cta') {
    $itemSchema['main'] = [
        ['name' => 'icon', 'label' => 'ไอคอน', 'type' => 'select', 'def' => 'clock'],
        ['name' => 'text', 'label' => 'ข้อความ', 'def' => ''],
    ];
}
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
                            <label class="form-label">รายการ <span class="text-muted fw-normal">(เพิ่ม/แก้ไข/ลบ ได้ง่ายๆ ด้านล่าง)</span></label>
                            <div id="items-editor"></div>
                            <textarea class="d-none" name="items" id="items-json"><?= admin_e($rows[$edit]['items']) ?></textarea>
                            <div class="form-text">
                                <button type="button" class="btn btn-sm btn-outline-info mt-2 me-1" onclick="loadDefaults()">↺ โหลดค่าเริ่มต้น</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2 me-1" onclick="addRow()">＋ เพิ่มรายการ</button>
                            </div>
                        </div>
                        <div class="col-12"><label class="form-label">Quote (สำหรับ hero / philosophy)</label><textarea class="form-control" name="quote" rows="2"><?= admin_e($rows[$edit]['quote']) ?></textarea></div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        </div>
                    </div>
                </form>
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

<script>
// ═══════ Item Editor แบบง่าย (ไม่ต้องแตะ JSON) ═══════
var ITEM_SCHEMA = <?= json_encode($itemSchema) ?>;
var ITEM_DEFAULTS = <?= json_encode($DEFAULTS) ?>;
var CUR_EDIT = <?= json_encode($edit) ?>;

// ไอคอนที่เลือกได้
var ICONS = ['users', 'award', 'clock', 'file-text', 'landmark', 'heart', 'activity', 'shield', 'search', 'ear', 'heart-handshake', 'quote', 'smartphone', 'phone', 'check-circle-2', 'briefcase', 'graduation-cap'];
var COLORS = ['blue', 'green', 'red', 'purple'];

function esc(s) {
    return String(s == null ? '' : s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

// ฟอร์มต่อ field
function fieldHtml(spec, val, grp, idx) {
    var v = val != null ? val : (spec.def != null ? spec.def : '');
    if (spec.type === 'select') {
        var opts = spec.options || ICONS;
        var html = '<select class="form-select form-select-sm" data-f="' + spec.name + '" data-g="' + grp + '" data-i="' + idx + '">';
        opts.forEach(function (o) {
            html += '<option value="' + o + '"' + (String(v) === String(o) ? ' selected' : '') + '>' + o + '</option>';
        });
        return html + '</select>';
    }
    return '<input type="text" class="form-control form-control-sm" data-f="' + spec.name + '" data-g="' + grp + '" data-i="' + idx + '" value="' + esc(v) + '">';
}

function renderItems() {
    var box = document.getElementById('items-editor');
    var html = '';
    var data;
    try { data = JSON.parse(document.getElementById('items-json').value || '[]'); } catch (e) { data = []; }
    if (!Array.isArray(data)) data = [];

    // experience = 2 กลุ่ม (stats + expertise)
    if (CUR_EDIT === 'experience') {
        var groups = { stats: 'สถิติหลัก', expertise: 'ความเชี่ยวชาญ' };
        ['stats', 'expertise'].forEach(function (g) {
            var items = (data && data[g]) ? data[g] : [];
            html += '<div class="border rounded p-2 mb-2 bg-light"><div class="fw-bold small text-muted mb-1">' + groups[g] + ' (' + items.length + ')</div>';
            items.forEach(function (it, i) {
                html += '<div class="item-row border rounded p-2 mb-2 bg-white" data-g="' + g + '">';
                ITEM_SCHEMA[g].forEach(function (s) { html += '<div class="mb-1"><label class="form-label small text-muted mb-0">' + s.label + '</label>' + fieldHtml(s, it[s.name], g, i) + '</div>'; });
                html += '<button type="button" class="btn btn-sm btn-soft-danger mt-1" onclick="removeRow(this)"><i class="ti ti-trash"></i> ลบ</button></div>';
            });
            html += '<button type="button" class="btn btn-sm btn-outline-primary mb-2" onclick="addRow(\'' + g + '\')">＋ เพิ่ม ' + groups[g] + '</button></div>';
        });
    } else {
        // section อื่น = array ธรรมดา
        data.forEach(function (it, i) {
            html += '<div class="item-row border rounded p-2 mb-2 bg-white">';
            ITEM_SCHEMA.main.forEach(function (s) { html += '<div class="mb-1"><label class="form-label small text-muted mb-0">' + s.label + '</label>' + fieldHtml(s, it[s.name], 'main', i) + '</div>'; });
            html += '<button type="button" class="btn btn-sm btn-soft-danger mt-1" onclick="removeRow(this)"><i class="ti ti-trash"></i> ลบ</button></div>';
        });
        if (data.length === 0) {
            html += '<p class="text-muted small">ยังไม่มีรายการ — กด "เพิ่มรายการ" ด้านล่าง</p>';
        }
    }
    box.innerHTML = html;
}

function addRow(grp) {
    var data;
    try { data = JSON.parse(document.getElementById('items-json').value || '[]'); } catch (e) { data = []; }
    if (CUR_EDIT === 'experience') {
        if (!data || !Array.isArray(data)) data = {};
        if (!data[grp]) data[grp] = [];
        var blank = {};
        (ITEM_SCHEMA[grp] || []).forEach(function (s) { blank[s.name] = s.def != null ? s.def : ''; });
        data[grp].push(blank);
    } else {
        if (!Array.isArray(data)) data = [];
        var b2 = {};
        (ITEM_SCHEMA.main || []).forEach(function (s) { b2[s.name] = s.def != null ? s.def : ''; });
        data.push(b2);
    }
    document.getElementById('items-json').value = JSON.stringify(data);
    renderItems();
}

function removeRow(btn) {
    var row = btn.closest('.item-row');
    var grp = row.getAttribute('data-g') || 'main';
    var idx = parseInt(row.querySelector('[data-i]').getAttribute('data-i'), 10);
    var data;
    try { data = JSON.parse(document.getElementById('items-json').value || '[]'); } catch (e) { data = []; }
    if (CUR_EDIT === 'experience') {
        data[grp].splice(idx, 1);
    } else {
        data.splice(idx, 1);
    }
    document.getElementById('items-json').value = JSON.stringify(data);
    renderItems();
}

function loadDefaults() {
    var def = ITEM_DEFAULTS[CUR_EDIT];
    if (def && def.items) {
        document.getElementById('items-json').value = def.items;
        renderItems();
    }
}

// ฟัง input → อัปเดต JSON ทันที
document.addEventListener('input', function (e) {
    if (!e.target.dataset || !e.target.dataset.f) return;
    var f = e.target.dataset.f, grp = e.target.dataset.g, idx = parseInt(e.target.dataset.i, 10);
    var data;
    try { data = JSON.parse(document.getElementById('items-json').value || '[]'); } catch (err) { data = []; }
    if (CUR_EDIT === 'experience') {
        if (!data[grp] || !data[grp][idx]) return;
        data[grp][idx][f] = e.target.value;
    } else {
        if (!data[idx]) return;
        data[idx][f] = e.target.value;
    }
    document.getElementById('items-json').value = JSON.stringify(data);
});

renderItems();
</script>

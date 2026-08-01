<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'แผนประกัน';
$adminMenu = 'products';

// ─── Import จาก insurance-data.json (ครั้งแรก) ───
if (isset($_GET['import'])) {
    $jsonPath = __DIR__ . '/../assets/data/insurance-data.json';
    $count = 0;
    if (file_exists($jsonPath)) {
        $data = json_decode(file_get_contents($jsonPath), true);
        $db->query('TRUNCATE TABLE products');
        $ins = $db->prepare('INSERT INTO products (category, title, desc_text, badge, sort_order) VALUES (?,?,?,?,?)');
        foreach (['life', 'health', 'general'] as $cat) {
            $groups = $data[$cat] ?? [];
            if (!is_array($groups)) continue;
            // รองรับทั้ง dict (group => plans[]) และ list
            if (isset($groups[0]['name']) && !isset($groups[0]['plans'])) {
                $groups = [['name' => $cat, 'plans' => $groups]];
            }
            $sort = 1;
            foreach ($groups as $gName => $g) {
                $plans = is_array($g) && isset($g['plans']) ? $g['plans'] : $g;
                if (!is_array($plans)) continue;
                $gLabel = is_array($g) && isset($g['name']) ? $g['name'] : $gName;
                foreach ($plans as $p) {
                    if (!is_array($p) || !isset($p['name'])) continue;
                    $desc = '';
                    if (!empty($p['coverage'])) $desc .= 'ความคุ้มครอง: ' . $p['coverage'] . ' | ';
                    if (!empty($p['plans'])) $desc .= 'แผน: ' . $p['plans'] . ' | ';
                    if (!empty($p['room_rate'])) $desc .= 'ห้อง: ' . $p['room_rate'] . ' | ';
                    if (!empty($p['area'])) $desc .= 'พื้นที่: ' . $p['area'];
                    if ($desc === '' && !empty($p['highlights'])) $desc = implode(', ', array_slice($p['highlights'], 0, 3));
                    $desc = mb_substr($desc, 0, 490);
                    $ins->execute([$cat, $p['name'], $desc, $gLabel, $sort++]);
                    $count++;
                }
            }
        }
        admin_flash("Import เรียบร้อย: $count แผน จาก insurance-data.json");
    } else {
        admin_flash('ไม่พบไฟล์ insurance-data.json', 'error');
    }
    header('Location: products.php');
    exit;
}

// ─── CRUD ───
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    if (($_POST['action'] ?? '') === 'import') { header('Location: products.php?import=1'); exit; }
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $cat = in_array($_POST['category'] ?? '', ['life', 'health', 'general'], true) ? $_POST['category'] : 'life';
            $title = trim($_POST['title'] ?? '');
            if ($title === '') throw new Exception('กรุณากรอกชื่อแผน');
            $data = [$cat, $title, trim($_POST['desc_text'] ?? ''), trim($_POST['img'] ?? ''), trim($_POST['link_url'] ?? ''), trim($_POST['badge'] ?? ''), (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0,
                trim($_POST['company'] ?? ''), trim($_POST['premium_from'] ?? ''), trim($_POST['coverage'] ?? ''), trim($_POST['plans'] ?? ''),
                trim($_POST['room_rate'] ?? ''), trim($_POST['area'] ?? ''), trim($_POST['key_benefits'] ?? ''), trim($_POST['age_range'] ?? ''), trim($_POST['details_url'] ?? '')];
            if ($id > 0) {
                $data[] = $id;
                $db->prepare('UPDATE products SET category=?, title=?, desc_text=?, img=?, link_url=?, badge=?, sort_order=?, is_active=?, company=?, premium_from=?, coverage=?, plans=?, room_rate=?, area=?, key_benefits=?, age_range=?, details_url=? WHERE id=?')->execute($data);
                admin_flash('บันทึกแผนเรียบร้อย');
            } else {
                $db->prepare('INSERT INTO products (category, title, desc_text, img, link_url, badge, sort_order, is_active, company, premium_from, coverage, plans, room_rate, area, key_benefits, age_range, details_url) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')->execute($data);
                admin_flash('เพิ่มแผนเรียบร้อย');
            }
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM products WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('ลบแผนเรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE products SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: products.php');
    exit;
}

$cat = $_GET['cat'] ?? '';
$catClause = in_array($cat, ['life', 'health', 'general'], true) ? ' WHERE category = ' . $db->quote($cat) : '';
// ─── Edit mode (หน้าเต็ม ไม่มี popup) ───
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: products.php'); exit; }
}

$rows = $db->query("SELECT * FROM products$catClause ORDER BY category, sort_order, id")->fetchAll();
$counts = [];
foreach ($db->query('SELECT category, COUNT(*) c FROM products GROUP BY category') as $r) {
    $counts[$r['category']] = (int)$r['c'];
}
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: [
    'title' => '',
    'badge' => '',
    'category' => '',
    'desc_text' => '',
    'img' => '',
    'link_url' => '',
    'sort_order' => '',
    'is_active' => 1,
];
$__isEdit = $edit ? 'แก้ไข' : 'เพิ่ม';
?>
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title"><?= $__isEdit ?>แผนประกัน</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item"><a href="products.php">แผนประกัน</a></li><li class="breadcrumb-item active"><?= $__isEdit ?></li></ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
<div class="col-md-6"><label class="form-label">ชื่อแผน</label><input type="text" class="form-control" name="title" value="<?= admin_e($e['title']) ?>" required></div>
<div class="col-md-6"><label class="form-label">กลุ่ม (badge)</label><input type="text" class="form-control" name="badge" value="<?= admin_e($e['badge']) ?>" ></div>
<div class="col-md-4"><label class="form-label">หมวดหมู่</label><select class="form-select" name="category"><option value="life" <?= $e['category'] == "life" ? 'selected' : '' ?>>life</option><option value="health" <?= $e['category'] == "health" ? 'selected' : '' ?>>health</option><option value="general" <?= $e['category'] == "general" ? 'selected' : '' ?>>general</option></select></div>
<div class="col-12"><label class="form-label">คำอธิบาย/จุดเด่น</label><textarea class="form-control" name="desc_text" rows="4"><?= admin_e($e['desc_text']) ?></textarea></div>
<div class="col-md-6"><label class="form-label">URL รูปภาพ</label><input type="text" class="form-control" name="img" value="<?= admin_e($e['img']) ?>" ></div>
<div class="col-md-6"><label class="form-label">ลิงก์</label><input type="text" class="form-control" name="link_url" value="<?= admin_e($e['link_url']) ?>" ></div>
<div class="col-md-4"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" value="<?= (int)$e['sort_order'] ?>"></div>
<div class="col-md-4"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="e_is_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="e_is_active">แสดงผล</label></div></div>

<h6 class="mt-4 mb-3 text-muted fw-bold border-bottom pb-2">📋 รายละเอียดแผน (หน้า plan.php)</h6>
<div class="col-md-4"><label class="form-label">บริษัทประกัน</label><input type="text" class="form-control" name="company" value="<?= admin_e($e['company']) ?>" placeholder="อลิอันซ์ อยุธยา"></div>
<div class="col-md-4"><label class="form-label">เบี้ยเริ่มต้น</label><input type="text" class="form-control" name="premium_from" value="<?= admin_e($e['premium_from']) ?>" placeholder="เช่น เริ่มต้น 12,000 บาท/ปี"></div>
<div class="col-md-4"><label class="form-label">อายุรับประกัน</label><input type="text" class="form-control" name="age_range" value="<?= admin_e($e['age_range']) ?>" placeholder="เช่น รับอายุ 1-60 ปี"></div>
<div class="col-md-4"><label class="form-label">ค่าห้องรายวัน</label><input type="text" class="form-control" name="room_rate" value="<?= admin_e($e['room_rate']) ?>" placeholder="เช่น 4,000 บาท/วัน"></div>
<div class="col-md-4"><label class="form-label">พื้นที่ให้บริการ</label><input type="text" class="form-control" name="area" value="<?= admin_e($e['area']) ?>" placeholder="เช่น ทั่วประเทศ"></div>
<div class="col-md-4"><label class="form-label">URL ข้อมูลบริษัท</label><input type="text" class="form-control" name="details_url" value="<?= admin_e($e['details_url']) ?>" placeholder="https://www.allianz-ayudhya.co.th/..."></div>
<div class="col-12"><label class="form-label">ความคุ้มครองหลัก (ทีละบรรทัด)</label><textarea class="form-control" name="key_benefits" rows="5"><?= admin_e($e['key_benefits']) ?></textarea></div>
<div class="col-12"><label class="form-label">รายละเอียดความคุ้มครอง (ทีละบรรทัด)</label><textarea class="form-control" name="coverage" rows="6"><?= admin_e($e['coverage']) ?></textarea></div>
<div class="col-12"><label class="form-label">แบบแผน/ระดับความคุ้มครอง (ทีละบรรทัด)</label><textarea class="form-control" name="plans" rows="4"><?= admin_e($e['plans']) ?></textarea></div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        <a href="products.php" class="btn btn-secondary waves-effect">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php else: ?>

    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">แผนประกัน (<?= count($rows) ?>)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">แผนประกัน</li></ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <form method="post" class="d-inline me-1"><input type="hidden" name="action" value="import"><button type="submit" class="btn btn-outline-info waves-effect btn-del"><i class="ti ti-download me-1"></i>Import JSON</button></form>
                <a href="products.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i> เพิ่มแผน</a>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <a href="products.php" class="btn btn-sm <?= $cat === '' ? 'btn-dark' : 'btn-light' ?>">ทั้งหมด (<?= array_sum($counts) ?>)</a>
    <a href="products.php?cat=life" class="btn btn-sm <?= $cat === 'life' ? 'btn-dark' : 'btn-light' ?>">Life (<?= $counts['life'] ?? 0 ?>)</a>
    <a href="products.php?cat=health" class="btn btn-sm <?= $cat === 'health' ? 'btn-dark' : 'btn-light' ?>">Health (<?= $counts['health'] ?? 0 ?>)</a>
    <a href="products.php?cat=general" class="btn btn-sm <?= $cat === 'general' ? 'btn-dark' : 'btn-light' ?>">General (<?= $counts['general'] ?? 0 ?>)</a>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>หมวด</th><th>ชื่อแผน</th><th>กลุ่ม</th><th>รายละเอียด</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td><span class="badge bg-soft-primary text-primary"><?= admin_e($r['category']) ?></span></td>
                                <td class="fw-semibold"><?= admin_e($r['title']) ?></td>
                                <td><?= admin_e($r['badge']) ?></td>
                                <td class="text-truncate" style="max-width:280px"><?= admin_e($r['desc_text']) ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-soft-primary" onclick='editRow(<?= json_encode($r, JSON_UNESCAPED_UNICODE) ?>)'><i class="ti ti-pencil"></i></button>
                                    <form method="post" class="d-inline" >
                                        <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-soft-danger btn-del"><i class="ti ti-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>

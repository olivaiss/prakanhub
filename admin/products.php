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
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $cat = in_array($_POST['category'] ?? '', ['life', 'health', 'general'], true) ? $_POST['category'] : 'life';
            $title = trim($_POST['title'] ?? '');
            if ($title === '') throw new Exception('กรุณากรอกชื่อแผน');
            $data = [$cat, $title, trim($_POST['desc_text'] ?? ''), trim($_POST['img'] ?? ''), trim($_POST['link_url'] ?? ''), trim($_POST['badge'] ?? ''), (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0];
            if ($id > 0) {
                $data[] = $id;
                $db->prepare('UPDATE products SET category=?, title=?, desc_text=?, img=?, link_url=?, badge=?, sort_order=?, is_active=? WHERE id=?')->execute($data);
                admin_flash('บันทึกแผนเรียบร้อย');
            } else {
                $db->prepare('INSERT INTO products (category, title, desc_text, img, link_url, badge, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)')->execute($data);
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
$rows = $db->query("SELECT * FROM products$catClause ORDER BY category, sort_order, id")->fetchAll();
$counts = [];
foreach ($db->query('SELECT category, COUNT(*) c FROM products GROUP BY category') as $r) {
    $counts[$r['category']] = (int)$r['c'];
}
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">แผนประกัน (<?= count($rows) ?>)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">แผนประกัน</li></ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <a href="products.php?import=1" class="btn btn-outline-info waves-effect me-1" onclick="return confirm('Import ใหม่จาก insurance-data.json (ล้างข้อมูลเดิม)?')"><i class="ti ti-download me-1"></i> Import JSON</a>
                <button class="btn btn-primary waves-effect" data-bs-toggle="modal" data-bs-target="#pModal" onclick="resetForm()"><i class="ti ti-plus me-1"></i> เพิ่มแผน</button>
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
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบแผนนี้?')">
                                        <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-soft-danger"><i class="ti ti-trash"></i></button>
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

<div class="modal fade" id="pModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <input type="hidden" name="action" value="save"><input type="hidden" name="id" id="f_id" value="0">
                <div class="modal-header"><h5 class="modal-title" id="modalTitle">เพิ่มแผนประกัน</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3"><label class="form-label">หมวด</label><select class="form-select" name="category" id="f_cat"><option value="life">Life</option><option value="health">Health</option><option value="general">General</option></select></div>
                        <div class="col-md-4 mb-3"><label class="form-label">กลุ่ม (badge)</label><input type="text" class="form-control" name="badge" id="f_badge"></div>
                        <div class="col-md-4 mb-3"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" id="f_sort" value="0"></div>
                        <div class="col-12 mb-3"><label class="form-label">ชื่อแผน <span class="required-flag">*</span></label><input type="text" class="form-control" name="title" id="f_title" required></div>
                        <div class="col-12 mb-3"><label class="form-label">รายละเอียด</label><textarea class="form-control" name="desc_text" id="f_desc" rows="3"></textarea></div>
                        <div class="col-md-6 mb-3"><label class="form-label">รูป (URL)</label><input type="text" class="form-control" name="img" id="f_img"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">ลิงก์</label><input type="text" class="form-control" name="link_url" id="f_link"></div>
                        <div class="col-12 mb-3"><div class="form-check form-switch"><input type="checkbox" class="form-check-input" name="is_active" id="f_active" value="1" checked><label class="form-check-label" for="f_active">แสดงผล</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button><button type="submit" class="btn btn-primary">บันทึก</button></div>
            </form>
        </div>
    </div>
</div>
<script>
function resetForm(){document.getElementById('modalTitle').textContent='เพิ่มแผนประกัน';document.getElementById('f_id').value=0;['f_badge','f_title','f_desc','f_img','f_link'].forEach(function(i){document.getElementById(i).value='';});document.getElementById('f_cat').value='life';document.getElementById('f_sort').value=0;document.getElementById('f_active').checked=true;}
function editRow(r){document.getElementById('modalTitle').textContent='แก้ไขแผน';document.getElementById('f_id').value=r.id;document.getElementById('f_cat').value=r.category;document.getElementById('f_badge').value=r.badge;document.getElementById('f_title').value=r.title;document.getElementById('f_desc').value=r.desc_text;document.getElementById('f_img').value=r.img;document.getElementById('f_link').value=r.link_url;document.getElementById('f_sort').value=r.sort_order;document.getElementById('f_active').checked=r.is_active==1;new bootstrap.Modal(document.getElementById('pModal')).show();}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'เมนู';
$adminMenu = 'menus';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $loc = $_POST['location'] === 'header' ? 'header' : 'footer';
            $label = trim($_POST['label'] ?? '');
            $url = trim($_POST['link_url'] ?? '');
            $target = $_POST['target'] === '_blank' ? '_blank' : '_self';
            if ($label === '' || $url === '') throw new Exception('กรุณากรอกชื่อเมนูและลิงก์');
            if ($id > 0) {
                $db->prepare('UPDATE menus SET location=?, label=?, link_url=?, target=?, sort_order=?, is_active=? WHERE id=?')
                   ->execute([$loc, $label, $url, $target, (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0, $id]);
                admin_flash('บันทึกเมนูเรียบร้อย');
            } else {
                $db->prepare('INSERT INTO menus (location, label, link_url, target, sort_order, is_active) VALUES (?,?,?,?,?,?)')
                   ->execute([$loc, $label, $url, $target, (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0]);
                admin_flash('เพิ่มเมนูเรียบร้อย');
            }
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM menus WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('ลบเมนูเรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE menus SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: menus.php');
    exit;
}

$rows = $db->query('SELECT * FROM menus ORDER BY location, sort_order, id')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">เมนู</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">เมนู</li></ol>
        </div>
        <div class="col-md-4"><div class="float-end"><button class="btn btn-primary waves-effect" data-bs-toggle="modal" data-bs-target="#menuModal" onclick="resetForm()"><i class="ti ti-plus me-1"></i> เพิ่มเมนู</button></div></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการเมนู (<?= count($rows) ?>)</h4>
                <p class="card-title-desc">เมนู footer / header ของเว็บ</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>ตำแหน่ง</th><th>ชื่อเมนู</th><th>ลิงก์</th><th>เปิด</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td><span class="badge bg-soft-<?= $r['location'] === 'header' ? 'primary' : 'info' ?> text-<?= $r['location'] === 'header' ? 'primary' : 'info' ?>"><?= $r['location'] === 'header' ? 'Header' : 'Footer' ?></span></td>
                                <td class="fw-semibold"><?= admin_e($r['label']) ?></td>
                                <td class="font-monospace small"><?= admin_e($r['link_url']) ?></td>
                                <td><?= $r['target'] === '_blank' ? 'แท็บใหม่' : 'หน้าเดิม' ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-soft-primary" onclick='editRow(<?= json_encode($r, JSON_UNESCAPED_UNICODE) ?>)'><i class="ti ti-pencil"></i></button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบเมนูนี้?')">
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

<div class="modal fade" id="menuModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <input type="hidden" name="action" value="save"><input type="hidden" name="id" id="f_id" value="0">
                <div class="modal-header"><h5 class="modal-title" id="modalTitle">เพิ่มเมนู</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">ชื่อเมนู <span class="required-flag">*</span></label><input type="text" class="form-control" name="label" id="f_label" required></div>
                    <div class="mb-3"><label class="form-label">ลิงก์ <span class="required-flag">*</span></label><input type="text" class="form-control" name="link_url" id="f_url" placeholder="/about.php หรือ https://..." required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">ตำแหน่ง</label><select class="form-select" name="location" id="f_loc"><option value="footer">Footer</option><option value="header">Header</option></select></div>
                        <div class="col-md-6 mb-3"><label class="form-label">เปิดใน</label><select class="form-select" name="target" id="f_target"><option value="_self">หน้าเดิม</option><option value="_blank">แท็บใหม่</option></select></div>
                        <div class="col-md-6 mb-3"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" id="f_sort" value="0"></div>
                        <div class="col-md-6 mb-3"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="f_active" value="1" checked><label class="form-check-label" for="f_active">แสดงผล</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button><button type="submit" class="btn btn-primary">บันทึก</button></div>
            </form>
        </div>
    </div>
</div>
<script>
function resetForm(){document.getElementById('modalTitle').textContent='เพิ่มเมนู';document.getElementById('f_id').value=0;document.getElementById('f_label').value='';document.getElementById('f_url').value='';document.getElementById('f_loc').value='footer';document.getElementById('f_target').value='_self';document.getElementById('f_sort').value=0;document.getElementById('f_active').checked=true;}
function editRow(r){document.getElementById('modalTitle').textContent='แก้ไขเมนู';document.getElementById('f_id').value=r.id;document.getElementById('f_label').value=r.label;document.getElementById('f_url').value=r.link_url;document.getElementById('f_loc').value=r.location;document.getElementById('f_target').value=r.target;document.getElementById('f_sort').value=r.sort_order;document.getElementById('f_active').checked=r.is_active==1;new bootstrap.Modal(document.getElementById('menuModal')).show();}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

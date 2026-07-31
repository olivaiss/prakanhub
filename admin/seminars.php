<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'สัมมนา';
$adminMenu = 'seminars';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $img = trim($_POST['img'] ?? '');
            $date = trim($_POST['event_date'] ?? '');
            $loc = trim($_POST['location'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            if ($title === '') throw new Exception('กรุณากรอกชื่อสัมมนา');
            if ($id > 0) {
                $db->prepare('UPDATE seminars SET title=?, img=?, event_date=?, location=?, description=?, sort_order=?, is_active=? WHERE id=?')
                   ->execute([$title, $img, $date ?: null, $loc, $desc, (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0, $id]);
                admin_flash('บันทึกสัมมนาเรียบร้อย');
            } else {
                $db->prepare('INSERT INTO seminars (title, img, event_date, location, description, sort_order, is_active) VALUES (?,?,?,?,?,?,?)')
                   ->execute([$title, $img, $date ?: null, $loc, $desc, (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0]);
                admin_flash('เพิ่มสัมมนาเรียบร้อย');
            }
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM seminars WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('ลบสัมมนาเรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE seminars SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: seminars.php');
    exit;
}

$rows = $db->query('SELECT * FROM seminars ORDER BY sort_order, id')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">สัมมนา / คอร์ส</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">สัมมนา</li></ol>
        </div>
        <div class="col-md-4"><div class="float-end"><button class="btn btn-primary waves-effect" data-bs-toggle="modal" data-bs-target="#sModal" onclick="resetForm()"><i class="ti ti-plus me-1"></i> เพิ่มสัมมนา</button></div></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการสัมมนา (<?= count($rows) ?>)</h4>
                <p class="card-title-desc">แกลเลอรีรูปสัมมนา (seminar.php)</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>รูป</th><th>ชื่อ</th><th>วันที่</th><th>สถานที่</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td>
                                    <?php if ($r['img']): ?><img src="<?= admin_e($r['img']) ?>" class="thumb-xs" alt=""><?php else: ?><span class="text-muted">-</span><?php endif; ?>
                                </td>
                                <td class="fw-semibold"><?= admin_e($r['title']) ?></td>
                                <td><?= $r['event_date'] ? date('d/m/Y', strtotime($r['event_date'])) : '-' ?></td>
                                <td><?= admin_e($r['location']) ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-soft-primary" onclick='editRow(<?= json_encode($r, JSON_UNESCAPED_UNICODE) ?>)'><i class="ti ti-pencil"></i></button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบสัมมนานี้?')">
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

<div class="modal fade" id="sModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <input type="hidden" name="action" value="save"><input type="hidden" name="id" id="f_id" value="0">
                <div class="modal-header"><h5 class="modal-title" id="modalTitle">เพิ่มสัมมนา</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">ชื่อ <span class="required-flag">*</span></label><input type="text" class="form-control" name="title" id="f_title" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">วันที่</label><input type="date" class="form-control" name="event_date" id="f_date"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">URL รูปภาพ</label><input type="text" class="form-control" name="img" id="f_img" placeholder="/assets/image/seminar/xxx.webp"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">สถานที่</label><input type="text" class="form-control" name="location" id="f_loc"></div>
                        <div class="col-12 mb-3"><label class="form-label">คำอธิบาย</label><textarea class="form-control" name="description" id="f_desc" rows="3"></textarea></div>
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
function resetForm(){document.getElementById('modalTitle').textContent='เพิ่มสัมมนา';document.getElementById('f_id').value=0;['f_title','f_img','f_loc','f_desc'].forEach(function(i){document.getElementById(i).value='';});document.getElementById('f_date').value='';document.getElementById('f_sort').value=0;document.getElementById('f_active').checked=true;}
function editRow(r){document.getElementById('modalTitle').textContent='แก้ไขสัมมนา';document.getElementById('f_id').value=r.id;document.getElementById('f_title').value=r.title;document.getElementById('f_img').value=r.img;document.getElementById('f_date').value=r.event_date||'';document.getElementById('f_loc').value=r.location;document.getElementById('f_desc').value=r.description;document.getElementById('f_sort').value=r.sort_order;document.getElementById('f_active').checked=r.is_active==1;new bootstrap.Modal(document.getElementById('sModal')).show();}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

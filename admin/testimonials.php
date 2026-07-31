<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'รีวิวลูกค้า';
$adminMenu = 'testimonials';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $role = trim($_POST['role'] ?? '');
            $rating = min(5, max(1, (int)($_POST['rating'] ?? 5)));
            $message = trim($_POST['message'] ?? '');
            if ($name === '') throw new Exception('กรุณากรอกชื่อ');
            if ($id > 0) {
                $db->prepare('UPDATE testimonials SET name=?, role=?, rating=?, message=?, sort_order=?, is_active=? WHERE id=?')
                   ->execute([$name, $role, $rating, $message, (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0, $id]);
                admin_flash('บันทึกรีวิวเรียบร้อย');
            } else {
                $db->prepare('INSERT INTO testimonials (name, role, rating, message, sort_order, is_active) VALUES (?,?,?,?,?,?)')
                   ->execute([$name, $role, $rating, $message, (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0]);
                admin_flash('เพิ่มรีวิวเรียบร้อย');
            }
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM testimonials WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('ลบรีวิวเรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE testimonials SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: testimonials.php');
    exit;
}

$rows = $db->query('SELECT * FROM testimonials ORDER BY sort_order, id')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">รีวิวลูกค้า</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">รีวิวลูกค้า</li></ol>
        </div>
        <div class="col-md-4"><div class="float-end"><button class="btn btn-primary waves-effect" data-bs-toggle="modal" data-bs-target="#tModal" onclick="resetForm()"><i class="ti ti-plus me-1"></i> เพิ่มรีวิว</button></div></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการรีวิว (<?= count($rows) ?>)</h4>
                <p class="card-title-desc">แสดงบนหน้าแรก + หน้ารีวิว (testimonials.php)</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>ชื่อ</th><th>ตำแหน่ง/รายละเอียด</th><th>ดาว</th><th>ข้อความ</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td class="fw-semibold"><?= admin_e($r['name']) ?></td>
                                <td><?= admin_e($r['role']) ?></td>
                                <td><span class="text-warning"><?= str_repeat('★', (int)$r['rating']) ?></span></td>
                                <td class="text-truncate" style="max-width:280px"><?= admin_e($r['message']) ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-soft-primary" onclick='editRow(<?= json_encode($r, JSON_UNESCAPED_UNICODE) ?>)'><i class="ti ti-pencil"></i></button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบรีวิวนี้?')">
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

<div class="modal fade" id="tModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <input type="hidden" name="action" value="save"><input type="hidden" name="id" id="f_id" value="0">
                <div class="modal-header"><h5 class="modal-title" id="modalTitle">เพิ่มรีวิว</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">ชื่อ <span class="required-flag">*</span></label><input type="text" class="form-control" name="name" id="f_name" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">ตำแหน่ง/รายละเอียด</label><input type="text" class="form-control" name="role" id="f_role" placeholder="เช่น เจ้าของธุรกิจ"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">ดาว (1-5)</label><input type="number" class="form-control" name="rating" id="f_rating" min="1" max="5" value="5"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" id="f_sort" value="0"></div>
                        <div class="col-12 mb-3"><label class="form-label">ข้อความรีวิว</label><textarea class="form-control" name="message" id="f_msg" rows="4"></textarea></div>
                        <div class="col-12 mb-3"><div class="form-check form-switch"><input type="checkbox" class="form-check-input" name="is_active" id="f_active" value="1" checked><label class="form-check-label" for="f_active">แสดงผล</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button><button type="submit" class="btn btn-primary">บันทึก</button></div>
            </form>
        </div>
    </div>
</div>
<script>
function resetForm(){document.getElementById('modalTitle').textContent='เพิ่มรีวิว';document.getElementById('f_id').value=0;['f_name','f_role','f_msg'].forEach(function(i){document.getElementById(i).value='';});document.getElementById('f_rating').value=5;document.getElementById('f_sort').value=0;document.getElementById('f_active').checked=true;}
function editRow(r){document.getElementById('modalTitle').textContent='แก้ไขรีวิว';document.getElementById('f_id').value=r.id;document.getElementById('f_name').value=r.name;document.getElementById('f_role').value=r.role;document.getElementById('f_rating').value=r.rating;document.getElementById('f_msg').value=r.message;document.getElementById('f_sort').value=r.sort_order;document.getElementById('f_active').checked=r.is_active==1;new bootstrap.Modal(document.getElementById('tModal')).show();}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

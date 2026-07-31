<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'รหัสสมาชิก';
$adminMenu = 'members';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $code = trim($_POST['member_code'] ?? '');
            $name = trim($_POST['display_name'] ?? '');
            if (!preg_match('/^[0-9]{18}$/', $code)) throw new Exception('รหัสสมาชิกต้องเป็นตัวเลข 18 หลัก');
            $active = !empty($_POST['is_active']) ? 1 : 0;
            if ($id > 0) {
                $db->prepare('UPDATE members SET member_code=?, display_name=?, is_active=? WHERE id=?')->execute([$code, $name, $active, $id]);
                admin_flash('บันทึกรหัสสมาชิกเรียบร้อย');
            } else {
                $db->prepare('INSERT INTO members (member_code, display_name, is_active) VALUES (?,?,?)')->execute([$code, $name, $active]);
                admin_flash('เพิ่มรหัสสมาชิกเรียบร้อย');
            }
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM members WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('ลบรหัสสมาชิกเรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE members SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: members.php');
    exit;
}

$rows = $db->query('SELECT * FROM members ORDER BY id DESC')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">รหัสสมาชิก (ระบบ member)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">รหัสสมาชิก</li></ol>
        </div>
        <div class="col-md-4"><div class="float-end"><button class="btn btn-primary waves-effect" data-bs-toggle="modal" data-bs-target="#mModal" onclick="resetForm()"><i class="ti ti-plus me-1"></i> เพิ่มรหัส</button></div></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการรหัสสมาชิก (<?= count($rows) ?>)</h4>
                <p class="card-title-desc">รหัส 18 หลักนี้ใช้ login เข้าระบบ member ที่ /member/ (จะ sync กับ member/config.php)</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>รหัส 18 หลัก</th><th>ชื่อ</th><th>สร้างเมื่อ</th><th>สถานะ</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['id'] ?></td>
                                <td class="font-monospace fw-semibold"><?= admin_e($r['member_code']) ?></td>
                                <td><?= admin_e($r['display_name'] ?: '-') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'ใช้งาน' : 'ปิด' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-soft-primary" onclick='editRow(<?= json_encode($r, JSON_UNESCAPED_UNICODE) ?>)'><i class="ti ti-pencil"></i></button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบรหัสนี้?')">
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

<div class="modal fade" id="mModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <input type="hidden" name="action" value="save"><input type="hidden" name="id" id="f_id" value="0">
                <div class="modal-header"><h5 class="modal-title" id="modalTitle">เพิ่มรหัสสมาชิก</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">รหัส 18 หลัก <span class="required-flag">*</span></label><input type="text" class="form-control" name="member_code" id="f_code" maxlength="18" pattern="[0-9]{18}" inputmode="numeric" required></div>
                    <div class="mb-3"><label class="form-label">ชื่อ (optional)</label><input type="text" class="form-control" name="display_name" id="f_name"></div>
                    <div class="mb-3"><div class="form-check form-switch"><input type="checkbox" class="form-check-input" name="is_active" id="f_active" value="1" checked><label class="form-check-label" for="f_active">ใช้งานได้</label></div></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button><button type="submit" class="btn btn-primary">บันทึก</button></div>
            </form>
        </div>
    </div>
</div>
<script>
function resetForm(){document.getElementById('modalTitle').textContent='เพิ่มรหัสสมาชิก';document.getElementById('f_id').value=0;document.getElementById('f_code').value='';document.getElementById('f_name').value='';document.getElementById('f_active').checked=true;}
function editRow(r){document.getElementById('modalTitle').textContent='แก้ไขรหัสสมาชิก';document.getElementById('f_id').value=r.id;document.getElementById('f_code').value=r.member_code;document.getElementById('f_name').value=r.display_name;document.getElementById('f_active').checked=r.is_active==1;new bootstrap.Modal(document.getElementById('mModal')).show();}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

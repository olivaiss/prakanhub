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

// ─── Edit mode (หน้าเต็ม ไม่มี popup) ───
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM members WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: members.php'); exit; }
}

$rows = $db->query('SELECT * FROM members ORDER BY id DESC')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: ['id' => 0, 'member_code' => '', 'display_name' => '', 'is_active' => 1]; ?>
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title"><?= $edit ? 'แก้ไขรหัสสมาชิก' : 'เพิ่มรหัสสมาชิก' ?></h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item"><a href="members.php">รหัสสมาชิก</a></li><li class="breadcrumb-item active"><?= $edit ? 'แก้ไข' : 'เพิ่ม' ?></li></ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
                    <div class="col-12"><label class="form-label">รหัสสมาชิก (18 หลัก) <span class="required-flag">*</span></label>
                        <input type="text" class="form-control" name="member_code" value="<?= admin_e($e['member_code']) ?>" required maxlength="18" pattern="[0-9]{18}" inputmode="numeric" placeholder="123456789012345678">
                    </div>
                    <div class="col-12"><label class="form-label">ชื่อสมาชิก</label><input type="text" class="form-control" name="display_name" value="<?= admin_e($e['display_name']) ?>" placeholder="ชื่อเล่น/ชื่อทีม"></div>
                    <div class="col-md-6"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="e_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="e_active">ใช้งานได้</label></div></div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        <a href="members.php" class="btn btn-secondary waves-effect">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">รหัสสมาชิก (<?= count($rows) ?>)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">รหัสสมาชิก</li></ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <a href="members.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i> เพิ่มรหัส</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการรหัสสมาชิก</h4>
                <p class="card-title-desc">รหัส 18 หลักนี้ใช้ login เข้าระบบ member ที่ /member/ — คลิกชื่อเพื่อแก้ไข</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>รหัส 18 หลัก</th><th>ชื่อ</th><th>สร้างเมื่อ</th><th>สถานะ</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['id'] ?></td>
                                <td class="font-monospace fw-semibold"><?= admin_e($r['member_code']) ?></td>
                                <td><a href="members.php?edit=<?= (int)$r['id'] ?>"><?= admin_e($r['display_name'] ?: '-') ?></a></td>
                                <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'ใช้งาน' : 'ปิด' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <a href="members.php?edit=<?= (int)$r['id'] ?>" class="btn btn-sm btn-soft-primary"><i class="ti ti-pencil"></i></a>
                                    <form method="post" class="d-inline">
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

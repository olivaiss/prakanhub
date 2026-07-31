<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'รีวิวลูกค้า';
$adminMenu = 'testimonials';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
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

// ─── Edit mode (หน้าเต็ม ไม่มี popup) ───
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM testimonials WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: testimonials.php'); exit; }
}

$rows = $db->query('SELECT * FROM testimonials ORDER BY sort_order, id')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: [
    'name' => '',
    'role' => '',
    'rating' => '',
    'message' => '',
    'sort_order' => '',
    'is_active' => 1,
];
$__isEdit = $edit ? 'แก้ไข' : 'เพิ่ม';
?>
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title"><?= $__isEdit ?>รีวิวลูกค้า</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item"><a href="testimonials.php">รีวิวลูกค้า</a></li><li class="breadcrumb-item active"><?= $__isEdit ?></li></ol>
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
<div class="col-md-6"><label class="form-label">ชื่อ</label><input type="text" class="form-control" name="name" value="<?= admin_e($e['name']) ?>" required></div>
<div class="col-md-6"><label class="form-label">ตำแหน่ง/อาชีพ</label><input type="text" class="form-control" name="role" value="<?= admin_e($e['role']) ?>" ></div>
<div class="col-md-4"><label class="form-label">ดาว (1-5)</label><input type="number" class="form-control" name="rating" value="<?= (int)$e['rating'] ?>"></div>
<div class="col-12"><label class="form-label">ข้อความรีวิว</label><textarea class="form-control" name="message" rows="4"><?= admin_e($e['message']) ?></textarea></div>
<div class="col-md-4"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" value="<?= (int)$e['sort_order'] ?>"></div>
<div class="col-md-4"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="e_is_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="e_is_active">แสดงผล</label></div></div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        <a href="testimonials.php" class="btn btn-secondary waves-effect">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php else: ?>

    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">รีวิวลูกค้า</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">รีวิวลูกค้า</li></ol>
        </div>
        <div class="col-md-4"><div class="float-end"><a href="testimonials.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i></a></div></div>
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

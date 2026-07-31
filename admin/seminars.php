<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'สัมมนา';
$adminMenu = 'seminars';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
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

// ─── Edit mode (หน้าเต็ม ไม่มี popup) ───
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM seminars WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: seminars.php'); exit; }
}

$rows = $db->query('SELECT * FROM seminars ORDER BY sort_order, id')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: [
    'title' => '',
    'img' => '',
    'event_date' => '',
    'location' => '',
    'description' => '',
    'sort_order' => '',
    'is_active' => 1,
];
$__isEdit = $edit ? 'แก้ไข' : 'เพิ่ม';
?>
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title"><?= $__isEdit ?>สัมมนา</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item"><a href="seminars.php">สัมมนา</a></li><li class="breadcrumb-item active"><?= $__isEdit ?></li></ol>
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
<div class="col-md-6"><label class="form-label">ชื่อกิจกรรม</label><input type="text" class="form-control" name="title" value="<?= admin_e($e['title']) ?>" required></div>
<div class="col-md-6"><label class="form-label">URL รูปภาพ</label><input type="text" class="form-control" name="img" value="<?= admin_e($e['img']) ?>" ></div>
<div class="col-md-6"><label class="form-label">วันที่จัด (YYYY-MM-DD)</label><input type="text" class="form-control" name="event_date" value="<?= admin_e($e['event_date']) ?>" ></div>
<div class="col-md-6"><label class="form-label">สถานที่</label><input type="text" class="form-control" name="location" value="<?= admin_e($e['location']) ?>" ></div>
<div class="col-12"><label class="form-label">คำอธิบาย</label><textarea class="form-control" name="description" rows="4"><?= admin_e($e['description']) ?></textarea></div>
<div class="col-md-4"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" value="<?= (int)$e['sort_order'] ?>"></div>
<div class="col-md-4"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="e_is_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="e_is_active">แสดงผล</label></div></div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        <a href="seminars.php" class="btn btn-secondary waves-effect">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php else: ?>

    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">สัมมนา / คอร์ส</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">สัมมนา</li></ol>
        </div>
        <div class="col-md-4"><div class="float-end"><a href="seminars.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i></a></div></div>
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

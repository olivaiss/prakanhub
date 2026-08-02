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

// ─── Edit mode (หน้าเต็ม ไม่มี popup) ───
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM menus WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: menus.php'); exit; }
}

$rows = $db->query('SELECT * FROM menus ORDER BY location, sort_order, id')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: [
    'label' => '',
    'link_url' => '',
    'location' => '',
    'target' => '',
    'sort_order' => '',
    'is_active' => 1,
];
$__isEdit = $edit ? 'แก้ไข' : 'เพิ่ม';
?>
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title"><?= $__isEdit ?>เมนู</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item"><a href="menus.php">เมนู</a></li><li class="breadcrumb-item active"><?= $__isEdit ?></li></ol>
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
<div class="col-md-6"><label class="form-label">ชื่อเมนู</label><input type="text" class="form-control" name="label" value="<?= admin_e($e['label']) ?>" required></div>
<div class="col-md-6"><label class="form-label">ลิงก์ (URL)</label><input type="text" class="form-control" name="link_url" value="<?= admin_e($e['link_url']) ?>" ></div>
<div class="col-md-4"><label class="form-label">ตำแหน่ง</label><select class="form-select" name="location"><option value="header" <?= $e['location'] == "header" ? 'selected' : '' ?>>header</option><option value="footer" <?= $e['location'] == "footer" ? 'selected' : '' ?>>footer</option></select></div>
<div class="col-md-4"><label class="form-label">เปิดในแท็บใหม่</label><select class="form-select" name="target"><option value="_self" <?= $e['target'] == "_self" ? 'selected' : '' ?>>_self</option><option value="_blank" <?= $e['target'] == "_blank" ? 'selected' : '' ?>>_blank</option></select></div>
<div class="col-md-4"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" value="<?= (int)$e['sort_order'] ?>"></div>
<div class="col-md-4"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="e_is_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="e_is_active">แสดงผล</label></div></div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        <a href="menus.php" class="btn btn-secondary waves-effect">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php else: ?>

    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">เมนู</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">เมนู</li></ol>
        </div>
        <div class="col-md-4"><div class="float-end"><a href="menus.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i> เพิ่มเมนู</a></div></div>
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
                                    <a href="menus.php?edit=<?= (int)$r['id'] ?>" class="btn btn-sm btn-soft-primary" title="แก้ไข"><i class="ti ti-pencil"></i></a>
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

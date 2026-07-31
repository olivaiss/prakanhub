<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'หมวดหมู่ประกัน';
$adminMenu = 'categories';

// ─── Actions ───
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $data = [
                trim($_POST['title'] ?? ''),
                trim($_POST['slug'] ?? ''),
                trim($_POST['icon'] ?? 'shield'),
                trim($_POST['description'] ?? ''),
                trim($_POST['link_url'] ?? ''),
                !empty($_POST['is_dark']) ? 1 : 0,
                (int)($_POST['sort_order'] ?? 0),
                !empty($_POST['is_active']) ? 1 : 0,
            ];
            if ($data[0] === '') throw new Exception('กรุณากรอกชื่อหมวดหมู่');
            if ($id > 0) {
                $data[] = $id;
                $stmt = $db->prepare('UPDATE categories SET title=?, slug=?, icon=?, description=?, link_url=?, is_dark=?, sort_order=?, is_active=? WHERE id=?');
                $stmt->execute($data);
                admin_flash('บันทึกหมวดหมู่เรียบร้อย');
            } else {
                $stmt = $db->prepare('INSERT INTO categories (title, slug, icon, description, link_url, is_dark, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)');
                $stmt->execute($data);
                admin_flash('เพิ่มหมวดหมู่เรียบร้อย');
            }
            header('Location: categories.php');
            exit;
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM categories WHERE id = ?')->execute([(int)$_POST['id']]);
            admin_flash('ลบหมวดหมู่เรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE categories SET is_active = 1 - is_active WHERE id = ?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: categories.php');
    exit;
}

// ─── Edit mode (หน้าเต็ม ไม่มี popup) ───
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: categories.php'); exit; }
}

include __DIR__ . '/includes/header.php';
?>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: ['id' => 0, 'title' => '', 'slug' => '', 'icon' => 'shield', 'description' => '', 'link_url' => '', 'is_dark' => 0, 'sort_order' => 0, 'is_active' => 1]; ?>
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title"><?= $edit ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่ใหม่' ?></h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item"><a href="categories.php">หมวดหมู่ประกัน</a></li><li class="breadcrumb-item active"><?= $edit ? 'แก้ไข' : 'เพิ่ม' ?></li></ol>
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
                    <div class="col-md-6"><label class="form-label">ชื่อหมวดหมู่ <span class="required-flag">*</span></label><input type="text" class="form-control" name="title" value="<?= admin_e($e['title']) ?>" required></div>
                    <div class="col-md-6"><label class="form-label">Slug</label><input type="text" class="form-control" name="slug" value="<?= admin_e($e['slug']) ?>" placeholder="life-insurance"></div>
                    <div class="col-md-6"><label class="form-label">ไอคอน (Lucide)</label><input type="text" class="form-control" name="icon" value="<?= admin_e($e['icon']) ?>" placeholder="heart"></div>
                    <div class="col-md-6"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" value="<?= (int)$e['sort_order'] ?>"></div>
                    <div class="col-12"><label class="form-label">คำอธิบาย</label><input type="text" class="form-control" name="description" value="<?= admin_e($e['description']) ?>" placeholder="คุ้มครองชีวิต<br>และคนที่คุณรัก"></div>
                    <div class="col-12"><label class="form-label">ลิงก์ (URL)</label><input type="text" class="form-control" name="link_url" value="<?= admin_e($e['link_url']) ?>" placeholder="/life.php"></div>
                    <div class="col-md-6"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_dark" id="e_dark" value="1" <?= $e['is_dark'] ? 'checked' : '' ?>><label class="form-check-label" for="e_dark">การ์ดสีเข้ม</label></div></div>
                    <div class="col-md-6"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="e_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="e_active">แสดงผล</label></div></div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        <a href="categories.php" class="btn btn-secondary waves-effect">ย้อนกลับ</a>
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
            <h6 class="page-title">หมวดหมู่ประกัน (<?= $db->query('SELECT COUNT(*) FROM categories')->fetchColumn() ?>)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">หมวดหมู่ประกัน</li></ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <a href="categories.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i> เพิ่มหมวดหมู่</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการหมวดหมู่</h4>
                <p class="card-title-desc">แสดงบนหน้าแรก grid การ์ด 12 หมวด — คลิกชื่อเพื่อแก้ไข</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead>
                        <tr><th>#</th><th>ชื่อ</th><th>Slug</th><th>ไอคอน</th><th>คำอธิบาย</th><th>ลิงก์</th><th>มืด</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($db->query('SELECT * FROM categories ORDER BY sort_order, id') as $r): ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td class="fw-semibold"><a href="categories.php?edit=<?= (int)$r['id'] ?>"><?= admin_e($r['title']) ?></a></td>
                                <td class="font-monospace"><?= admin_e($r['slug']) ?></td>
                                <td><code><?= admin_e($r['icon']) ?></code></td>
                                <td class="text-truncate" style="max-width:220px"><?= admin_e($r['description']) ?></td>
                                <td class="font-monospace small"><?= admin_e($r['link_url']) ?></td>
                                <td><?= $r['is_dark'] ? '<span class="badge bg-dark">มืด</span>' : '<span class="badge bg-light text-dark">สว่าง</span>' ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <a href="categories.php?edit=<?= (int)$r['id'] ?>" class="btn btn-sm btn-soft-primary"><i class="ti ti-pencil"></i></a>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
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

<?php $adminDataTable = true; include __DIR__ . '/includes/footer.php'; ?>

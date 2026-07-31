<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'หน้าเนื้อหา';
$adminMenu = 'pages';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $slug = trim($_POST['slug'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $content = $_POST['content'] ?? '';
            $seo_title = trim($_POST['seo_title'] ?? '');
            $seo_desc = trim($_POST['seo_desc'] ?? '');
            $active = !empty($_POST['is_active']) ? 1 : 0;
            if ($slug === '' || $title === '') throw new Exception('กรุณากรอก slug และ title');
            if ($id > 0) {
                $db->prepare('UPDATE pages SET slug=?, title=?, content=?, seo_title=?, seo_desc=?, is_active=? WHERE id=?')
                   ->execute([$slug, $title, $content, $seo_title, $seo_desc, $active, $id]);
                admin_flash('บันทึกหน้าเรียบร้อย');
            } else {
                $db->prepare('INSERT INTO pages (slug, title, content, seo_title, seo_desc, is_active) VALUES (?,?,?,?,?,?)')
                   ->execute([$slug, $title, $content, $seo_title, $seo_desc, $active]);
                admin_flash('เพิ่มหน้าเรียบร้อย');
            }
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM pages WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('ลบหน้าเรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE pages SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: pages.php');
    exit;
}

// Edit mode (single page edit form)
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM pages WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: pages.php'); exit; }
}

$rows = $db->query('SELECT * FROM pages ORDER BY slug')->fetchAll();

if ($edit) {
    $adminTinyMCE = true;
}
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">หน้าเนื้อหา</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">หน้าเนื้อหา</li></ol>
        </div>
        <div class="col-md-4"><div class="float-end"><a href="pages.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i> เพิ่มหน้า</a></div></div>
    </div>
</div>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: ['id' => 0, 'slug' => '', 'title' => '', 'content' => '', 'seo_title' => '', 'seo_desc' => '', 'is_active' => 1]; ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= $edit ? 'แก้ไขหน้า: ' . admin_e($edit['title']) : 'เพิ่มหน้าใหม่' ?></h4>
                <form method="post">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Slug <span class="required-flag">*</span></label><input type="text" class="form-control" name="slug" value="<?= admin_e($e['slug']) ?>" required placeholder="about, career, claim..."></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Title <span class="required-flag">*</span></label><input type="text" class="form-control" name="title" value="<?= admin_e($e['title']) ?>" required></div>
                        <div class="col-12 mb-3">
                            <label class="form-label">เนื้อหา (HTML)</label>
                            <textarea class="form-control tinymce" name="content" rows="12"><?= admin_e($e['content']) ?></textarea>
                        </div>
                        <div class="col-md-6 mb-3"><label class="form-label">SEO Title</label><input type="text" class="form-control" name="seo_title" value="<?= admin_e($e['seo_title']) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">SEO Description</label><input type="text" class="form-control" name="seo_desc" value="<?= admin_e($e['seo_desc']) ?>"></div>
                        <div class="col-12 mb-3"><div class="form-check form-switch"><input type="checkbox" class="form-check-input" name="is_active" id="p_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="p_active">แสดงผล</label></div></div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        <a href="pages.php" class="btn btn-secondary waves-effect">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการหน้า (<?= count($rows) ?>)</h4>
                <p class="card-title-desc">about / career / claim / privacy / terms — แก้เนื้อหาได้ด้วย HTML editor</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>Slug</th><th>Title</th><th>SEO Title</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td class="font-monospace fw-semibold"><?= admin_e($r['slug']) ?></td>
                                <td><?= admin_e($r['title']) ?></td>
                                <td class="text-truncate" style="max-width:240px"><?= admin_e($r['seo_title']) ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <a href="pages.php?edit=<?= (int)$r['id'] ?>" class="btn btn-sm btn-soft-primary"><i class="ti ti-pencil"></i></a>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบหน้านี้?')">
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
<?php endif; ?>

<?php
if (!$edit && !isset($_GET['new'])) {
    $adminDataTable = true;
}
include __DIR__ . '/includes/footer.php';
?>

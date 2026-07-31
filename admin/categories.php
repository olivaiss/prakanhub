<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'หมวดหมู่ประกัน';
$adminMenu = 'categories';

// ─── Actions ───
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'slug' => trim($_POST['slug'] ?? ''),
                'icon' => trim($_POST['icon'] ?? 'shield'),
                'description' => trim($_POST['description'] ?? ''),
                'link_url' => trim($_POST['link_url'] ?? ''),
                'is_dark' => !empty($_POST['is_dark']) ? 1 : 0,
                'sort_order' => (int)($_POST['sort_order'] ?? 0),
                'is_active' => !empty($_POST['is_active']) ? 1 : 0,
            ];
            if ($data['title'] === '' || $data['slug'] === '') {
                throw new Exception('กรุณากรอก ชื่อ และ slug');
            }
            if ($id > 0) {
                $stmt = $db->prepare('UPDATE categories SET title=?, slug=?, icon=?, description=?, link_url=?, is_dark=?, sort_order=?, is_active=? WHERE id=?');
                $stmt->execute([$data['title'], $data['slug'], $data['icon'], $data['description'], $data['link_url'], $data['is_dark'], $data['sort_order'], $data['is_active'], $id]);
                admin_flash('บันทึกหมวดหมู่เรียบร้อย');
            } else {
                $stmt = $db->prepare('INSERT INTO categories (title, slug, icon, description, link_url, is_dark, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)');
                $stmt->execute([$data['title'], $data['slug'], $data['icon'], $data['description'], $data['link_url'], $data['is_dark'], $data['sort_order'], $data['is_active']]);
                admin_flash('เพิ่มหมวดหมู่เรียบร้อย');
            }
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

// ─── Edit mode ───
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$rows = $db->query('SELECT * FROM categories ORDER BY sort_order, id')->fetchAll();

$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">หมวดหมู่ประกัน</h6>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Admin</a></li>
                <li class="breadcrumb-item active">หมวดหมู่ประกัน</li>
            </ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <button class="btn btn-primary waves-effect" data-bs-toggle="modal" data-bs-target="#catModal" onclick="resetForm()"><i class="ti ti-plus me-1"></i> เพิ่มหมวดหมู่</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการหมวดหมู่ (<?= count($rows) ?>)</h4>
                <p class="card-title-desc">แสดงบนหน้าแรก grid การ์ด 12 หมวด</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th><th>ชื่อ</th><th>Slug</th><th>ไอคอน</th><th>คำอธิบาย</th><th>ลิงก์</th><th>มืด</th><th>แสดง</th><th style="width:140px">จัดการ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td class="fw-semibold"><?= admin_e($r['title']) ?></td>
                                <td class="font-monospace"><?= admin_e($r['slug']) ?></td>
                                <td><code><?= admin_e($r['icon']) ?></code></td>
                                <td class="text-truncate" style="max-width:220px"><?= admin_e($r['description']) ?></td>
                                <td class="font-monospace small"><?= admin_e($r['link_url']) ?></td>
                                <td><?= $r['is_dark'] ? '<span class="badge bg-dark">มืด</span>' : '<span class="badge bg-light text-dark">สว่าง</span>' ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle">
                                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?> waves-effect"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-soft-primary waves-effect" onclick='editRow(<?= json_encode($r, JSON_UNESCAPED_UNICODE) ?>)'><i class="ti ti-pencil"></i></button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบหมวดหมู่นี้?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-soft-danger waves-effect"><i class="ti ti-trash"></i></button>
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

<!-- Modal Add/Edit -->
<div class="modal fade" id="catModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="id" id="f_id" value="0">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">เพิ่มหมวดหมู่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ชื่อหมวดหมู่ <span class="required-flag">*</span></label>
                            <input type="text" class="form-control" name="title" id="f_title" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Slug <span class="required-flag">*</span></label>
                            <input type="text" class="form-control" name="slug" id="f_slug" required placeholder="เช่น life">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ไอคอน (Lucide)</label>
                            <input type="text" class="form-control" name="icon" id="f_icon" placeholder="heart, shield, activity...">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">เรียงลำดับ</label>
                            <input type="number" class="form-control" name="sort_order" id="f_sort" value="0">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">คำอธิบาย</label>
                            <input type="text" class="form-control" name="description" id="f_desc" maxlength="255">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">ลิงก์ (URL)</label>
                            <input type="text" class="form-control" name="link_url" id="f_link" placeholder="/life.php">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="is_dark" id="f_dark" value="1">
                                <label class="form-check-label" for="f_dark">การ์ดสีเข้ม</label>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" name="is_active" id="f_active" value="1" checked>
                                <label class="form-check-label" for="f_active">แสดงผล</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('modalTitle').textContent = 'เพิ่มหมวดหมู่';
    document.getElementById('f_id').value = 0;
    ['f_title','f_slug','f_icon','f_sort','f_desc','f_link'].forEach(function (i) { document.getElementById(i).value = ''; });
    document.getElementById('f_sort').value = 0;
    document.getElementById('f_dark').checked = false;
    document.getElementById('f_active').checked = true;
}
function editRow(r) {
    document.getElementById('modalTitle').textContent = 'แก้ไข: ' + r.title;
    document.getElementById('f_id').value = r.id;
    document.getElementById('f_title').value = r.title;
    document.getElementById('f_slug').value = r.slug;
    document.getElementById('f_icon').value = r.icon;
    document.getElementById('f_sort').value = r.sort_order;
    document.getElementById('f_desc').value = r.description;
    document.getElementById('f_link').value = r.link_url;
    document.getElementById('f_dark').checked = r.is_dark == 1;
    document.getElementById('f_active').checked = r.is_active == 1;
    new bootstrap.Modal(document.getElementById('catModal')).show();
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

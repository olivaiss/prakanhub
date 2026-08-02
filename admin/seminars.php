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

            // ═══ อัปโหลดรูปภาพ → แปลง webp + ขนาด ═══
            $imgNote = '';
            if (!empty($_FILES['img_file']['name']) && $_FILES['img_file']['error'] === UPLOAD_ERR_OK) {
                $f = $_FILES['img_file'];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($ext, $allowed, true)) throw new Exception('อนุญาตเฉพาะไฟล์รูปภาพ (jpg, png, gif, webp)');
                if ($f['size'] > 5 * 1024 * 1024) throw new Exception('ไฟล์ใหญ่เกิน 5MB');
                $im = @imagecreatefromstring(file_get_contents($f['tmp_name']));
                if (!$im) throw new Exception('อ่านไฟล์รูปไม่สำเร็จ — กรุณาใช้ไฟล์รูปจริง');
                $w = imagesx($im);
                $h = imagesy($im);
                if ($w > 1200) {
                    $nw = 1200;
                    $nh = (int)round($h * $nw / $w);
                    $im2 = imagecreatetruecolor($nw, $nh);
                    imagealphablending($im2, false);
                    imagesavealpha($im2, true);
                    imagecopyresampled($im2, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
                    imagedestroy($im);
                    $im = $im2;
                    $w = $nw;
                    $h = $nh;
                }
                if (in_array($ext, ['png', 'gif'], true)) {
                    imagealphablending($im, false);
                    imagesavealpha($im, true);
                }
                $imgDir = __DIR__ . '/../assets/seminars';
                if (!is_dir($imgDir)) @mkdir($imgDir, 0775, true);
                $imgName = 'sem-' . ($id > 0 ? $id : date('His')) . '-' . preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($f['name'], PATHINFO_FILENAME)) . '.webp';
                if (imagewebp($im, $imgDir . '/' . $imgName, 82)) {
                    $img = '/assets/seminars/' . $imgName;
                    $imgNote = " รูปถูกแปลงเป็น WebP ({$w}x{$h}px)";
                } else {
                    throw new Exception('แปลงเป็น WebP ไม่สำเร็จ');
                }
                imagedestroy($im);
            }

            if ($id > 0) {
                $db->prepare('UPDATE seminars SET title=?, img=?, event_date=?, location=?, description=?, sort_order=?, is_active=? WHERE id=?')
                   ->execute([$title, $img, $date ?: null, $loc, $desc, (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0, $id]);
                admin_flash('บันทึกสัมมนาเรียบร้อย' . $imgNote);
            } else {
                $db->prepare('INSERT INTO seminars (title, img, event_date, location, description, sort_order, is_active) VALUES (?,?,?,?,?,?,?)')
                   ->execute([$title, $img, $date ?: null, $loc, $desc, (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0]);
                admin_flash('เพิ่มสัมมนาเรียบร้อย' . $imgNote);
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
    'id' => 0,
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
                <form method="post" class="row g-3" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
<div class="col-md-6"><label class="form-label">ชื่อกิจกรรม</label><input type="text" class="form-control" name="title" value="<?= admin_e($e['title']) ?>" required></div>
<div class="col-md-6"><label class="form-label">รูปภาพ (อัปโหลด หรือวาง URL)</label>
    <input type="file" class="form-control" name="img_file" accept="image/*" onchange="previewSemImg(this)">
    <input type="text" class="form-control mt-2" name="img" id="sem_img_url" value="<?= admin_e($e['img']) ?>" placeholder="/assets/seminars/... หรือ https://...">
    <?php
    $__semImgDim = '';
    if (!empty($e['img']) && strpos($e['img'], '/assets/') === 0) {
        $__semImgPath = __DIR__ . '/..' . $e['img'];
        if (file_exists($__semImgPath)) {
            $__dim = @getimagesize($__semImgPath);
            if ($__dim) $__semImgDim = " — ไฟล์ปัจจุบัน: {$__dim[0]} x {$__dim[1]} px (" . round(filesize($__semImgPath) / 1024) . " KB)";
        }
    }
    ?>
    <div class="form-text">เลือกไฟล์ (jpg/png/gif/webp ≤5MB) — <strong>แปลงเป็น WebP อัตโนมัติ</strong> (ย่อกว้าง 1200px)<?= $__semImgDim ?></div>
    <div id="sem-img-preview" class="mt-2">
        <?php if (!empty($e['img'])): ?>
        <img src="<?= admin_e($e['img']) ?>" alt="รูปปัจจุบัน" style="max-height:100px;max-width:220px;object-fit:contain" class="border rounded" onerror="this.style.display='none'">
        <?php endif; ?>
    </div>
</div>
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
        <div class="col-md-4"><div class="float-end"><a href="seminars.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i> เพิ่มสัมมนา</a></div></div>
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
                                    <a href="seminars.php?edit=<?= (int)$r['id'] ?>" class="btn btn-sm btn-soft-primary" title="แก้ไข"><i class="ti ti-pencil"></i></a>
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

<script>
// ═══ Preview รูปสัมมนาก่อนอัปโหลด + ขนาด px ═══
function previewSemImg(input) {
    var file = input.files && input.files[0];
    if (!file) return;
    var img = new Image();
    img.onload = function () {
        var box = document.getElementById('sem-img-preview');
        if (!box) return;
        box.innerHTML = '<img src="' + img.src + '" style="max-height:100px;max-width:220px;object-fit:contain" class="border rounded">' +
            '<div class="text-success small fw-bold mt-1">' + file.name + ' — ' + img.width + ' x ' + img.height + ' px</div>';
    };
    img.src = URL.createObjectURL(file);
}
</script>

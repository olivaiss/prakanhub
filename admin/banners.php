<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'แบนเนอร์';
$adminMenu = 'banners';

$banerDir = __DIR__ . '/../assets/image/baner';
if (!is_dir($banerDir)) { @mkdir($banerDir, 0775, true); }

// ─── Actions ───
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'upload' && !empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $f = $_FILES['image'];
            $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ext, $allowed, true)) throw new Exception('อนุญาตเฉพาะไฟล์รูปภาพ (jpg, png, gif, webp)');
            if ($f['size'] > 5 * 1024 * 1024) throw new Exception('ไฟล์ใหญ่เกิน 5MB');
            $img = @imagecreatefromstring(file_get_contents($f['tmp_name']));
            if (!$img) throw new Exception('อ่านไฟล์รูปไม่สำเร็จ — กรุณาใช้ไฟล์รูปจริง');
            $w = imagesx($img);
            $h = imagesy($img);
            if ($w > 1200) {
                // ย่อความกว้างไม่เกิน 1200px (ไม่จำกัดความสูง — คงสัดส่วน)
                $nw = 1200;
                $nh = (int)round($h * $nw / $w);
                $img2 = imagecreatetruecolor($nw, $nh);
                imagealphablending($img2, false);
                imagesavealpha($img2, true);
                imagecopyresampled($img2, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
                imagedestroy($img);
                $img = $img2;
            }
            if (in_array($ext, ['png', 'gif'], true)) {
                imagealphablending($img, false);
                imagesavealpha($img, true);
            }
            $name = 'baner-' . date('Ymd-His') . '-' . preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($f['name'], PATHINFO_FILENAME)) . '.webp';
            if (!imagewebp($img, $banerDir . '/' . $name, 82)) throw new Exception('แปลงเป็น WebP ไม่สำเร็จ');
            imagedestroy($img);
            $db->prepare('INSERT INTO banners (filename, is_active, sort_order) VALUES (?,1,?)')->execute([$name, (int)($_POST['sort_order'] ?? 0)]);
            admin_flash('อัปโหลดแบนเนอร์เรียบร้อย — แปลงเป็น WebP (' . $w . 'x' . $h . 'px)');
        } elseif ($action === 'delete') {
            $stmt = $db->prepare('SELECT filename FROM banners WHERE id=?');
            $stmt->execute([(int)$_POST['id']]);
            $row = $stmt->fetch();
            if ($row) {
                @unlink($banerDir . '/' . $row['filename']);
                $db->prepare('DELETE FROM banners WHERE id=?')->execute([(int)$_POST['id']]);
                admin_flash('ลบแบนเนอร์เรียบร้อย');
            }
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE banners SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: banners.php');
    exit;
}

$rows = $db->query('SELECT * FROM banners ORDER BY sort_order, id DESC')->fetchAll();
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">แบนเนอร์ (<?= count($rows) ?>)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">แบนเนอร์</li></ol>
        </div>
    </div>
</div>

<div class="row">
    <!-- อัปโหลด -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">📤 เพิ่มแบนเนอร์</h4>
                <p class="card-title-desc mb-3">สุ่มแสดง 1 รูปบนหน้ารายละเอียดแผน (การ์ดบริษัทประกัน) — รูปจะถูกแปลงเป็น WebP อัตโนมัติ</p>
                <form method="post" enctype="multipart/form-data" class="space-y-3">
                    <div class="mb-3">
                        <label class="form-label">เลือกไฟล์รูป (jpg/png/gif/webp — สูงสุด 5MB)</label>
                        <input type="file" class="form-control" name="image" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">เรียงลำดับ</label>
                        <input type="number" class="form-control" name="sort_order" value="0">
                    </div>
                    <button type="submit" class="btn btn-primary waves-effect" name="action" value="upload"><i class="ti ti-upload me-1"></i> อัปโหลด (แปลง WebP)</button>
                </form>
            </div>
        </div>
    </div>

    <!-- รายการ -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead>
                            <tr><th>#</th><th>ภาพ</th><th>ขนาด</th><th>แสดง</th><th style="width:120px">จัดการ</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rows as $r):
                            $__file = $banerDir . '/' . $r['filename'];
                            $__dim = file_exists($__file) ? @getimagesize($__file) : null;
                        ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="/assets/image/baner/<?= rawurlencode($r['filename']) ?>" alt="" style="max-height:52px;max-width:140px;object-fit:contain" class="border rounded">
                                        <span class="text-muted small" style="max-width:150px;word-break:break-all"><?= admin_e($r['filename']) ?></span>
                                    </div>
                                </td>
                                <td class="text-nowrap"><?= $__dim ? $__dim[0] . ' x ' . $__dim[1] . ' px' : '-' ?><br><small class="text-muted"><?= file_exists($__file) ? round(filesize($__file) / 1024) . ' KB' : '' ?></small></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'เปิด' : 'ปิด' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-soft-danger btn-del" title="ลบ"><i class="ti ti-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($rows)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">ยังไม่มีแบนเนอร์ — อัปโหลดทางซ้าย</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

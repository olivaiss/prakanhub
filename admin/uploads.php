<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'อัปโหลดรูปภาพ';
$adminMenu = 'uploads';

$uploadDir = __DIR__ . '/../assets/uploads';
if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }

// ─── Upload ───
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    if ($action === 'upload' && !empty($_FILES['image']['name'])) {
        $f = $_FILES['image'];
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        if (!in_array($ext, $allowed, true)) {
            admin_flash('อนุญาตเฉพาะไฟล์รูปภาพ (jpg, png, gif, webp, svg)', 'error');
        } elseif ($f['size'] > 5 * 1024 * 1024) {
            admin_flash('ไฟล์ใหญ่เกิน 5MB', 'error');
        } elseif ($f['error'] !== UPLOAD_ERR_OK) {
            admin_flash('อัปโหลดไม่สำเร็จ (error ' . $f['error'] . ')', 'error');
        } else {
            $name = date('Ymd-His') . '-' . preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($f['name'], PATHINFO_FILENAME)) . '.' . $ext;
            if (move_uploaded_file($f['tmp_name'], $uploadDir . '/' . $name)) {
                admin_flash('อัปโหลดเรียบร้อย — คัดลอก URL ไปใช้ในเนื้อหาได้');
                $_SESSION['last_upload'] = '/assets/uploads/' . $name;
            } else {
                admin_flash('ย้ายไฟล์ไม่สำเร็จ — ตรวจสิทธิ์โฟลเดอร์', 'error');
            }
        }
    } elseif ($action === 'delete') {
        $name = basename($_POST['file'] ?? '');
        if ($name !== '' && strpos($name, '..') === false) {
            @unlink($uploadDir . '/' . $name);
            admin_flash('ลบไฟล์เรียบร้อย');
        }
    }
    header('Location: uploads.php');
    exit;
}

$files = glob($uploadDir . '/*');
usort($files, fn($a, $b) => filemtime($b) - filemtime($a));

include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">อัปโหลดรูปภาพ (<?= count($files) ?>)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">อัปโหลดรูป</li></ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($_SESSION['last_upload'])): ?>
                <div class="alert alert-success py-2">
                    URL รูป: <code><?= admin_e($_SESSION['last_upload']) ?></code>
                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="navigator.clipboard.writeText('<?= admin_e($_SESSION['last_upload']) ?>');this.textContent='คัดลอกแล้ว ✓'">คัดลอก URL</button>
                    <?php unset($_SESSION['last_upload']); ?>
                </div>
                <?php endif; ?>
                <form method="post" enctype="multipart/form-data" class="row g-2 align-items-center mb-4">
                    <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                    <input type="hidden" name="action" value="upload">
                    <div class="col-md-6">
                        <input type="file" name="image" accept="image/*" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-upload me-1"></i> อัปโหลด</button>
                    </div>
                    <div class="col-md-3 text-muted small">jpg/png/gif/webp/svg — สูงสุด 5MB</div>
                </form>

                <div class="row g-3">
                    <?php foreach ($files as $file): $name = basename($file); $url = '/assets/uploads/' . $name; ?>
                    <div class="col-6 col-md-3 col-xl-2">
                        <div class="card border shadow-none h-100">
                            <img src="<?= admin_e($url) ?>" class="card-img-top" style="height:110px;object-fit:cover" onerror="this.style.display='none'">
                            <div class="card-body p-2">
                                <p class="text-truncate small mb-2" title="<?= admin_e($name) ?>"><?= admin_e($name) ?></p>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-soft-primary" onclick="navigator.clipboard.writeText('<?= admin_e($url) ?>');this.textContent='✓'">URL</button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบไฟล์นี้?')">
                                        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="file" value="<?= admin_e($name) ?>">
                                        <button type="submit" class="btn btn-sm btn-soft-danger"><i class="ti ti-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if (empty($files)): ?>
                    <p class="text-muted text-center py-4">ยังไม่มีรูป — อัปโหลดรูปแรกด้านบน</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

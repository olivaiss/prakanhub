<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'บทความ';
$adminMenu = 'articles';

// ─── Import บทความจาก articles.php / article.php (โครงสร้าง PHP array) ───
if (isset($_GET['import'])) {
    // อ่านจาก article.php โดย regex จับ array $articles
    $src = file_get_contents(__DIR__ . '/../article.php');
    $count = 0;
    if (preg_match('/\$articles\s*=\s*\[(.*?)\];/s', $src, $m)) {
        $db->query('TRUNCATE TABLE articles');
        // parse แต่ละรายการ id => [...]
        if (preg_match_all("/'id'\s*=>\s*(\d+),.*?'tag'\s*=>\s*'([^']*)',.*?'title'\s*=>\s*'([^']*)',.*?'excerpt'\s*=>\s*'([^']*)',.*?'date'\s*=>\s*'([^']*)',.*?'cover'\s*=>\s*'([^']*)',.*?'content'\s*=>\s*'(.*?)',\s*\]/s", $m[1], $mm, PREG_SET_ORDER)) {
            $ins = $db->prepare('INSERT INTO articles (id, title, tag, excerpt, img, content, publish_date, is_active) VALUES (?,?,?,?,?,?,?,1)');
            foreach ($mm as $a) {
                $title = str_replace(["\\'", '\\"', "\\n"], ["'", '"', ' '], $a[3]);
                $excerpt = str_replace(["\\'", '\\"', "\\n"], ["'", '"', ' '], $a[4]);
                $content = str_replace(["\\'", '\\"', "\\n"], ["'", '"', "\n"], $a[7]);
                $date = date('Y-m-d', strtotime($a[5]));
                $ins->execute([(int)$a[1], $title, $a[2], $excerpt, $a[6], $content, $date]);
                $count++;
            }
        }
    }
    if ($count === 0) {
        // fallback: import จาก main.js (4 บทความ)
        $js = file_get_contents(__DIR__ . '/../assets/js/main.js');
        if (preg_match_all("/id:\s*(\d+),\s*img:\s*\"([^\"]*)\",\s*tag:\s*\"([^\"]*)\",\s*title:\s*\"([^\"]*)\"/", $js, $mm, PREG_SET_ORDER)) {
            $db->query('TRUNCATE TABLE articles');
            $ins = $db->prepare('INSERT INTO articles (id, title, tag, img, is_active) VALUES (?,?,?,?,1)');
            foreach ($mm as $a) {
                $ins->execute([(int)$a[1], $a[4], $a[3], $a[2]]);
                $count++;
            }
        }
    }
    admin_flash($count > 0 ? "Import เรียบร้อย: $count บทความ" : 'Import ไม่สำเร็จ (ไม่พบข้อมูลบทความ)', $count > 0 ? 'success' : 'error');
    header('Location: articles.php');
    exit;
}

// ─── CRUD ───
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    if (($_POST['action'] ?? '') === 'import') { header('Location: articles.php?import=1'); exit; }
    if (($_POST['action'] ?? '') === 'import') { header('Location: articles.php?import=1'); exit; }
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            if ($title === '') throw new Exception('กรุณากรอกชื่อบทความ');

            // ═══ อัปโหลดรูปปก → แปลง webp + ขนาด ═══
            $coverUrl = trim($_POST['img'] ?? '');
            $coverNote = '';
            if (!empty($_FILES['cover_file']['name']) && $_FILES['cover_file']['error'] === UPLOAD_ERR_OK) {
                $f = $_FILES['cover_file'];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($ext, $allowed, true)) throw new Exception('อนุญาตเฉพาะไฟล์รูปภาพ (jpg, png, gif, webp)');
                if ($f['size'] > 5 * 1024 * 1024) throw new Exception('ไฟล์ใหญ่เกิน 5MB');
                $img = @imagecreatefromstring(file_get_contents($f['tmp_name']));
                if (!$img) throw new Exception('อ่านไฟล์รูปไม่สำเร็จ — กรุณาใช้ไฟล์รูปจริง');
                $w = imagesx($img);
                $h = imagesy($img);
                // ย่อความกว้างไม่เกิน 1200px (คงสัดส่วน)
                if ($w > 1200) {
                    $nw = 1200;
                    $nh = (int)round($h * $nw / $w);
                    $img2 = imagecreatetruecolor($nw, $nh);
                    imagealphablending($img2, false);
                    imagesavealpha($img2, true);
                    imagecopyresampled($img2, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
                    imagedestroy($img);
                    $img = $img2;
                    $w = $nw;
                    $h = $nh;
                }
                if (in_array($ext, ['png', 'gif'], true)) {
                    imagealphablending($img, false);
                    imagesavealpha($img, true);
                }
                $coverDir = __DIR__ . '/../assets/covers';
                if (!is_dir($coverDir)) @mkdir($coverDir, 0775, true);
                $coverName = 'cover-' . ($id > 0 ? $id : date('His')) . '-' . preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($f['name'], PATHINFO_FILENAME)) . '.webp';
                if (imagewebp($img, $coverDir . '/' . $coverName, 82)) {
                    $coverUrl = '/assets/covers/' . $coverName;
                    $coverNote = " รูปปกถูกแปลงเป็น WebP ({$w}x{$h}px)";
                } else {
                    throw new Exception('แปลงเป็น WebP ไม่สำเร็จ');
                }
                imagedestroy($img);
            }

            $data = [$title, trim($_POST['tag'] ?? ''), $coverUrl, trim($_POST['excerpt'] ?? ''), $_POST['content'] ?? '', trim($_POST['author'] ?? ''), trim($_POST['publish_date'] ?? '') ?: null, trim($_POST['seo_title'] ?? ''), trim($_POST['seo_desc'] ?? ''), (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0];
            if ($id > 0) {
                $data[] = $id;
                $db->prepare('UPDATE articles SET title=?, tag=?, img=?, excerpt=?, content=?, author=?, publish_date=?, seo_title=?, seo_desc=?, sort_order=?, is_active=? WHERE id=?')->execute($data);
                admin_flash('บันทึกบทความเรียบร้อย' . $coverNote);
            } else {
                $db->prepare('INSERT INTO articles (title, tag, img, excerpt, content, author, publish_date, seo_title, seo_desc, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?)')->execute($data);
                admin_flash('เพิ่มบทความเรียบร้อย' . $coverNote);
            }
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM articles WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('ลบบทความเรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE articles SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: articles.php');
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM articles WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: articles.php'); exit; }
}

$rows = $db->query('SELECT * FROM articles ORDER BY id DESC')->fetchAll();
if ($edit) { $adminTinyMCE = true; }
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">บทความ (<?= count($rows) ?>)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">บทความ</li></ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <form method="post" class="d-inline me-1"><input type="hidden" name="action" value="import"><button type="submit" class="btn btn-outline-info waves-effect btn-del"><i class="ti ti-download me-1"></i>Import</button></form>
                <a href="articles.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i> เพิ่มบทความ</a>
            </div>
        </div>
    </div>
</div>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: ['id' => 0, 'title' => '', 'tag' => '', 'img' => '', 'excerpt' => '', 'content' => '', 'author' => '', 'publish_date' => '', 'seo_title' => '', 'seo_desc' => '', 'sort_order' => 0, 'is_active' => 1]; ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= $edit ? 'แก้ไขบทความ' : 'เพิ่มบทความ' ?></h4>
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label class="form-label">ชื่อบทความ <span class="required-flag">*</span></label><input type="text" class="form-control" name="title" value="<?= admin_e($e['title']) ?>" required></div>
                        <div class="col-md-4 mb-3"><label class="form-label">หมวด (tag)</label><input type="text" class="form-control" name="tag" value="<?= admin_e($e['tag']) ?>" placeholder="ประกันชีวิต"></div>
                        <div class="col-md-4 mb-3"><label class="form-label">รูปปก (อัปโหลด หรือวาง URL)</label>
                            <input type="file" class="form-control" name="cover_file" accept="image/*" onchange="previewCover(this)">
                            <input type="text" class="form-control mt-2" name="img" id="cover_url" value="<?= admin_e($e['img']) ?>" placeholder="/assets/covers/... หรือ https://...">
                            <?php
                            $__coverDim = '';
                            if (!empty($e['img']) && strpos($e['img'], '/assets/') === 0) {
                                $__coverPath = __DIR__ . '/..' . $e['img'];
                                if (file_exists($__coverPath)) {
                                    $__dim = @getimagesize($__coverPath);
                                    if ($__dim) $__coverDim = " — ไฟล์ปัจจุบัน: {$__dim[0]} x {$__dim[1]} px (" . round(filesize($__coverPath) / 1024) . " KB)";
                                }
                            }
                            ?>
                            <div class="form-text">เลือกไฟล์ (jpg/png/gif/webp ≤5MB) — <strong>แปลงเป็น WebP อัตโนมัติ</strong> (ย่อกว้าง 1200px)<?= $__coverDim ?></div>
                            <div id="cover-preview" class="mt-2">
                                <?php if (!empty($e['img'])): ?>
                                <img src="<?= admin_e($e['img']) ?>" alt="รูปปกปัจจุบัน" style="max-height:90px;max-width:220px;object-fit:contain" class="border rounded" onerror="this.style.display='none'">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3"><label class="form-label">วันที่</label><input type="date" class="form-control" name="publish_date" value="<?= admin_e($e['publish_date']) ?>"></div>
                        <div class="col-md-4 mb-3"><label class="form-label">ผู้เขียน</label><input type="text" class="form-control" name="author" value="<?= admin_e($e['author']) ?>"></div>
                        <div class="col-12 mb-3"><label class="form-label">คำโปรย (excerpt)</label><textarea class="form-control" name="excerpt" rows="2"><?= admin_e($e['excerpt']) ?></textarea></div>
                        <div class="col-12 mb-3"><label class="form-label">เนื้อหา (HTML)</label><textarea class="form-control tinymce" name="content" rows="14"><?= admin_e($e['content']) ?></textarea></div>
                        <div class="col-md-6 mb-3"><label class="form-label">SEO Title</label><input type="text" class="form-control" name="seo_title" value="<?= admin_e($e['seo_title']) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">SEO Description</label><input type="text" class="form-control" name="seo_desc" value="<?= admin_e($e['seo_desc']) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" value="<?= (int)$e['sort_order'] ?>"></div>
                        <div class="col-md-6 mb-3"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="a_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="a_active">แสดงผล</label></div></div>
                    </div>
                    <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                    <a href="articles.php" class="btn btn-secondary waves-effect">ย้อนกลับ</a>
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
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>รูป</th><th>ชื่อ</th><th>หมวด</th><th>วันที่</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['id'] ?></td>
                                <td><?php if ($r['img']): ?><img src="<?= admin_e($r['img']) ?>" class="thumb-xs" onerror="this.style.display='none'"><?php endif; ?></td>
                                <td class="fw-semibold"><?= admin_e(mb_substr($r['title'], 0, 60)) ?></td>
                                <td><span class="badge bg-soft-info text-info"><?= admin_e($r['tag']) ?></span></td>
                                <td><?= $r['publish_date'] ? date('d/m/Y', strtotime($r['publish_date'])) : '-' ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <a href="articles.php?edit=<?= (int)$r['id'] ?>" class="btn btn-sm btn-soft-primary"><i class="ti ti-pencil"></i></a>
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

<?php
if (!$edit && !isset($_GET['new'])) {
    $adminDataTable = true;
}
include __DIR__ . '/includes/footer.php';
?>

<script>
// ═══ Preview รูปปกก่อนอัปโหลด + แสดงขนาด px ═══
function previewCover(input) {
    var file = input.files && input.files[0];
    if (!file) return;
    var img = new Image();
    img.onload = function () {
        var box = document.getElementById('cover-preview');
        if (!box) return;
        box.innerHTML = '<img src="' + img.src + '" style="max-height:90px;max-width:220px;object-fit:contain" class="border rounded">' +
            '<div class="text-success small fw-bold mt-1">' + file.name + ' — ' + img.width + ' x ' + img.height + ' px</div>';
    };
    img.src = URL.createObjectURL(file);
}
</script>

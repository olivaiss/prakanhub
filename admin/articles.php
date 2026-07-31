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
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            if ($title === '') throw new Exception('กรุณากรอกชื่อบทความ');
            $data = [$title, trim($_POST['tag'] ?? ''), trim($_POST['img'] ?? ''), trim($_POST['excerpt'] ?? ''), $_POST['content'] ?? '', trim($_POST['author'] ?? ''), trim($_POST['publish_date'] ?? '') ?: null, trim($_POST['seo_title'] ?? ''), trim($_POST['seo_desc'] ?? ''), (int)($_POST['sort_order'] ?? 0), !empty($_POST['is_active']) ? 1 : 0];
            if ($id > 0) {
                $data[] = $id;
                $db->prepare('UPDATE articles SET title=?, tag=?, img=?, excerpt=?, content=?, author=?, publish_date=?, seo_title=?, seo_desc=?, sort_order=?, is_active=? WHERE id=?')->execute($data);
                admin_flash('บันทึกบทความเรียบร้อย');
            } else {
                $db->prepare('INSERT INTO articles (title, tag, img, excerpt, content, author, publish_date, seo_title, seo_desc, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?)')->execute($data);
                admin_flash('เพิ่มบทความเรียบร้อย');
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
                <a href="articles.php?import=1" class="btn btn-outline-info waves-effect me-1" onclick="return confirm('Import บทความจากโค้ดเดิม (ล้างข้อมูลเดิม)?')"><i class="ti ti-download me-1"></i> Import</a>
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
                <form method="post">
                    <input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label class="form-label">ชื่อบทความ <span class="required-flag">*</span></label><input type="text" class="form-control" name="title" value="<?= admin_e($e['title']) ?>" required></div>
                        <div class="col-md-4 mb-3"><label class="form-label">หมวด (tag)</label><input type="text" class="form-control" name="tag" value="<?= admin_e($e['tag']) ?>" placeholder="ประกันชีวิต"></div>
                        <div class="col-md-4 mb-3"><label class="form-label">รูปปก (URL)</label><input type="text" class="form-control" name="img" value="<?= admin_e($e['img']) ?>"></div>
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
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบบทความนี้?')">
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

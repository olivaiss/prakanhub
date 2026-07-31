<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'FAQ';
$adminMenu = 'faqs';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $q = trim($_POST['question'] ?? '');
            $a = trim($_POST['answer'] ?? '');
            $g = trim($_POST['group_name'] ?? '') ?: 'ทั่วไป';
            if ($q === '') throw new Exception('กรุณากรอกคำถาม');
            if ($id > 0) {
                $db->prepare('UPDATE faqs SET group_name=?, question=?, answer=?, sort_order=? WHERE id=?')
                   ->execute([$g, $q, $a, (int)($_POST['sort_order'] ?? 0), $id]);
                admin_flash('บันทึก FAQ เรียบร้อย');
            } else {
                $db->prepare('INSERT INTO faqs (group_name, question, answer, sort_order) VALUES (?,?,?,?)')
                   ->execute([$g, $q, $a, (int)($_POST['sort_order'] ?? 0)]);
                admin_flash('เพิ่ม FAQ เรียบร้อย');
            }
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM faqs WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('ลบ FAQ เรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE faqs SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: faqs.php');
    exit;
}

// ─── Edit mode (หน้าเต็ม ไม่มี popup) ───
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM faqs WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: faqs.php'); exit; }
}

$rows = $db->query('SELECT * FROM faqs ORDER BY sort_order, id')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: ['id' => 0, 'group_name' => 'ทั่วไป', 'question' => '', 'answer' => '', 'sort_order' => 0, 'is_active' => 1]; ?>
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title"><?= $edit ? 'แก้ไข FAQ' : 'เพิ่ม FAQ ใหม่' ?></h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item"><a href="faqs.php">FAQ</a></li><li class="breadcrumb-item active"><?= $edit ? 'แก้ไข' : 'เพิ่ม' ?></li></ol>
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
                    <div class="col-md-6"><label class="form-label">กลุ่มหัวข้อ</label>
                        <input type="text" class="form-control" name="group_name" value="<?= admin_e($e['group_name']) ?>" list="faqGroups" placeholder="ทั่วไป">
                        <datalist id="faqGroups"><option>ทั่วไป</option><option>การเคลม</option><option>ผลิตภัณฑ์</option><option>ตัวแทน/การบริการ</option></datalist>
                    </div>
                    <div class="col-md-6"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" value="<?= (int)$e['sort_order'] ?>"></div>
                    <div class="col-12"><label class="form-label">คำถาม <span class="required-flag">*</span></label><input type="text" class="form-control" name="question" value="<?= admin_e($e['question']) ?>" required></div>
                    <div class="col-12"><label class="form-label">คำตอบ</label><textarea class="form-control" name="answer" rows="6"><?= admin_e($e['answer']) ?></textarea></div>
                    <div class="col-md-6"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="e_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="e_active">แสดงผล</label></div></div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึก</button>
                        <a href="faqs.php" class="btn btn-secondary waves-effect">ย้อนกลับ</a>
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
            <h6 class="page-title">FAQ (<?= count($rows) ?>)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">FAQ</li></ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <a href="faqs.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i> เพิ่ม FAQ</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการ FAQ</h4>
                <p class="card-title-desc">แสดงบนหน้า faq.php (กลุ่มหัวข้ออยู่ที่หน้าเว็บ) — คลิกชื่อเพื่อแก้ไข</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>กลุ่ม</th><th>คำถาม</th><th>คำตอบ</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td><span class="badge bg-soft-info text-info"><?= admin_e($r['group_name']) ?></span></td>
                                <td class="fw-semibold"><a href="faqs.php?edit=<?= (int)$r['id'] ?>"><?= admin_e($r['question']) ?></a></td>
                                <td class="text-truncate" style="max-width:320px"><?= admin_e(mb_substr((string)$r['answer'], 0, 90)) ?>…</td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <a href="faqs.php?edit=<?= (int)$r['id'] ?>" class="btn btn-sm btn-soft-primary"><i class="ti ti-pencil"></i></a>
                                    <form method="post" class="d-inline">
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

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

$rows = $db->query('SELECT * FROM faqs ORDER BY sort_order, id')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">FAQ</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">FAQ</li></ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <button class="btn btn-primary waves-effect" data-bs-toggle="modal" data-bs-target="#faqModal" onclick="resetForm()"><i class="ti ti-plus me-1"></i> เพิ่ม FAQ</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รายการ FAQ (<?= count($rows) ?>)</h4>
                <p class="card-title-desc">แสดงบนหน้า faq.php (กลุ่มหัวข้ออยู่ที่หน้าเว็บ)</p>
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>กลุ่ม</th><th>คำถาม</th><th>คำตอบ</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td><span class="badge bg-soft-primary text-primary"><?= admin_e($r['group_name']) ?></span></td>
                                <td class="fw-semibold"><?= admin_e($r['question']) ?></td>
                                <td class="text-truncate" style="max-width:300px"><?= admin_e(mb_substr($r['answer'] ?? '', 0, 120)) ?></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-soft-primary" onclick='editRow(<?= json_encode($r, JSON_UNESCAPED_UNICODE) ?>)'><i class="ti ti-pencil"></i></button>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบ FAQ นี้?')">
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

<div class="modal fade" id="faqModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post">
                <input type="hidden" name="action" value="save"><input type="hidden" name="id" id="f_id" value="0">
                <div class="modal-header"><h5 class="modal-title" id="modalTitle">เพิ่ม FAQ</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">กลุ่มหัวข้อ</label><input type="text" class="form-control" name="group_name" id="f_group" placeholder="ทั่วไป, การเคลม, ผลิตภัณฑ์..." list="faq-groups"><datalist id="faq-groups"><option>ทั่วไป</option><option>การเคลม</option><option>ผลิตภัณฑ์</option><option>ตัวแทน/การบริการ</option></datalist></div>
                    <div class="mb-3"><label class="form-label">คำถาม <span class="required-flag">*</span></label><input type="text" class="form-control" name="question" id="f_q" required></div>
                    <div class="mb-3"><label class="form-label">คำตอบ</label><textarea class="form-control" name="answer" id="f_a" rows="4"></textarea></div>
                    <div class="mb-3"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" id="f_sort" value="0"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button><button type="submit" class="btn btn-primary">บันทึก</button></div>
            </form>
        </div>
    </div>
</div>
<script>
function resetForm(){document.getElementById('modalTitle').textContent='เพิ่ม FAQ';document.getElementById('f_id').value=0;document.getElementById('f_group').value='ทั่วไป';document.getElementById('f_q').value='';document.getElementById('f_a').value='';document.getElementById('f_sort').value=0;}
function editRow(r){document.getElementById('modalTitle').textContent='แก้ไข FAQ';document.getElementById('f_id').value=r.id;document.getElementById('f_group').value=r.group_name;document.getElementById('f_q').value=r.question;document.getElementById('f_a').value=r.answer;document.getElementById('f_sort').value=r.sort_order;new bootstrap.Modal(document.getElementById('faqModal')).show();}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

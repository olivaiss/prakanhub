<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'กล่องข้อความ';
$adminMenu = 'inbox';

$tab = $_GET['tab'] ?? 'contacts';
if (!in_array($tab, ['contacts', 'applications', 'form_submissions'], true)) $tab = 'contacts';

// ═══ แมปแท็บ → ชื่อตารางจริง ═══
$__tables = [
    'contacts' => 'contacts',
    'applications' => 'agent_applications',
    'form_submissions' => 'form_submissions',
];
$__table = $__tables[$tab];

// ─── Actions ───
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($action === 'delete' && $id > 0) {
        $db->prepare("DELETE FROM `$__table` WHERE id = ?")->execute([$id]);
        admin_flash('ลบรายการเรียบร้อย');
    } elseif ($action === 'read' && $id > 0) {
        $db->prepare("UPDATE `$__table` SET is_read = 1 WHERE id = ?")->execute([$id]);
    } elseif ($action === 'delete_all') {
        $db->query("TRUNCATE TABLE `$__table`");
        admin_flash('ล้างทั้งหมดเรียบร้อย');
    }
    header('Location: inbox.php?tab=' . $tab);
    exit;
}

// ─── ข้อมูล ───
$rows = $db->query("SELECT * FROM `$__table` ORDER BY id DESC LIMIT 200")->fetchAll();
$unread = [
    'contacts' => (int)$db->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn(),
    'applications' => (int)$db->query("SELECT COUNT(*) FROM agent_applications WHERE is_read = 0")->fetchColumn(),
    'form_submissions' => (int)$db->query("SELECT COUNT(*) FROM form_submissions WHERE is_read = 0")->fetchColumn(),
];

// ─── View mode (หน้าเต็ม ไม่มี popup) ───
$viewRow = null;
if (isset($_GET['view'])) {
    $__v = $db->prepare("SELECT * FROM `$__table` WHERE id = ?");
    $__v->execute([(int)$_GET['view']]);
    $viewRow = $__v->fetch();
    if ($viewRow) {
        $db->prepare("UPDATE `$__table` SET is_read = 1 WHERE id = ?")->execute([(int)$_GET['view']]);
    }
}

$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">กล่องข้อความ</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">กล่องข้อความ</li></ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <form method="post" class="d-inline" >
                    <input type="hidden" name="action" value="delete_all">
                    <button type="submit" class="btn btn-outline-danger waves-effect btn-del"><i class="ti ti-trash me-1"></i> ล้างทั้งหมด</button>
                </form>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link <?= $tab === 'contacts' ? 'active' : '' ?>" href="inbox.php?tab=contacts">ข้อความติดต่อ <?php if ($unread['contacts'] > 0): ?><span class="badge bg-danger ms-1"><?= $unread['contacts'] ?></span><?php endif; ?></a></li>
    <li class="nav-item"><a class="nav-link <?= $tab === 'applications' ? 'active' : '' ?>" href="inbox.php?tab=applications">สมัครตัวแทน <?php if ($unread['applications'] > 0): ?><span class="badge bg-danger ms-1"><?= $unread['applications'] ?></span><?php endif; ?></a></li>
    <li class="nav-item"><a class="nav-link <?= $tab === 'form_submissions' ? 'active' : '' ?>" href="inbox.php?tab=form_submissions">ใบสมัครทำประกัน <?php if ($unread['form_submissions'] > 0): ?><span class="badge bg-danger ms-1"><?= $unread['form_submissions'] ?></span><?php endif; ?></a></li>
</ul>

<?php if ($viewRow): ?>
<!-- ═══ View mode: ข้อมูลเต็ม (ไม่มี popup) ═══ -->
<?php
// ═══ แปลง payload → ฟอร์ม label ไทย (form_submissions) ═══
$__payloadArr = null;
$__labels = [
    'plan_detail' => 'แผนประกันที่สนใจ (เฉพาะ)', 'plans' => 'แผนประกันที่สนใจ', 'budget' => 'งบประมาณเบี้ยประกันต่อปี',
    'goals' => 'เป้าหมายในการทำประกัน', 'prefix_th' => 'คำนำหน้า (ไทย)', 'firstname_th' => 'ชื่อ (ไทย)',
    'lastname_th' => 'นามสกุล (ไทย)', 'prefix_en' => 'คำนำหน้า (อังกฤษ)', 'firstname_en' => 'ชื่อ (อังกฤษ)',
    'lastname_en' => 'นามสกุล (อังกฤษ)', 'birthdate' => 'วันเกิด', 'id_card' => 'เลขบัตรประชาชน',
    'id_laser' => 'รหัสหลังบัตร', 'id_expiry' => 'วันที่บัตรหมดอายุ', 'marital_status' => 'สถานภาพ',
    'nationality' => 'สัญชาติ', 'other_nationality_detail' => 'สัญชาติอื่น (ระบุ)', 'weight' => 'น้ำหนัก (กก.)',
    'height' => 'ส่วนสูง (ซม.)', 'mobile' => 'เบอร์โทรศัพท์มือถือ', 'email' => 'อีเมล',
    'workplace' => 'สถานที่ทำงาน', 'workplace_address' => 'ที่อยู่สถานที่ทำงาน',
    'policy_count' => 'ถือประกันกี่สัญญา', 'policy_companies' => 'บริษัทประกันที่ถืออยู่',
    'policy_life_sum' => 'ทุนชีวิต (บาท)', 'policy_accident_sum' => 'ทุนอุบัติเหตุ (บาท)',
    'tax_deduction' => 'ส่งข้อมูลให้สรรพากรลดหย่อนภาษี', 'spouse_relation' => 'สามี/ภรรยา',
    'spouse_name' => 'ชื่อ-สกุลผู้สมรส', 'beneficiary_name' => 'ชื่อ-สกุลผู้รับผลประโยชน์',
    'beneficiary_relation' => 'ความสัมพันธ์', 'beneficiary_relation_detail' => 'ความสัมพันธ์อื่น (ระบุ)',
    'contact_type' => 'สถานที่ติดต่อ', 'contact_address' => 'ที่อยู่ติดต่อ',
    'occupation' => 'ลักษณะอาชีพ', 'position' => 'ตำแหน่ง', 'work_detail' => 'ลักษณะงานที่ทำ',
    'business_detail' => 'ลักษณะธุรกิจของบริษัท', 'income' => 'รายได้ต่อปี (บาท)',
    'smoking' => 'การสูบบุหรี่', 'smoking_detail' => 'สูบ (ระบุจำนวน)', 'alcohol' => 'ดื่มแอลกอฮอล์เป็นประจำ',
    'rejected' => 'เคยถูกปฏิเสธการรับประกัน', 'rejected_detail' => 'รายละเอียด (ระบุ)',
    'name_changed' => 'เคยเปลี่ยนชื่อ/นามสกุล', 'old_name' => 'ชื่อ-นามสกุลเดิม', 'nickname' => 'ชื่อเล่น',
    'health_checks' => 'เคยตรวจร่างกาย 5 ปี', 'health_reason' => 'สาเหตุที่ไปตรวจ',
    'health_reason_detail' => 'สาเหตุอื่น (ระบุ)', 'hospital_stays' => 'เคยเข้าพักรักษา รพ.',
    'diseases' => 'โรคประจำตัว/โรคที่เคยเป็น',
];
if (!empty($viewRow['payload'])) {
    $__decoded = json_decode((string)$viewRow['payload'], true);
    if (is_array($__decoded)) $__payloadArr = $__decoded;
}
?>
<div class="card">
    <div class="card-body">
        <!-- หัวรายการ -->
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div class="d-flex align-items-center gap-2">
                <h4 class="card-title mb-0">รายละเอียด #<?= (int)$viewRow['id'] ?></h4>
                <?php if (!empty($viewRow['ref_code'])): ?><span class="badge bg-soft-dark text-dark font-monospace"><?= admin_e($viewRow['ref_code']) ?></span><?php endif; ?>
                <?= $viewRow['is_read'] ? '<span class="badge bg-soft-secondary text-secondary">อ่านแล้ว</span>' : '<span class="badge bg-danger">ใหม่</span>' ?>
            </div>
            <a href="inbox.php?tab=<?= $tab ?>" class="btn btn-secondary waves-effect btn-sm"><i class="ti ti-arrow-left me-1"></i> กลับ</a>
        </div>
        <!-- แถบข้อมูล -->
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge bg-light text-dark border"><i class="ti ti-calendar me-1"></i><?= date('d/m/Y H:i:s', strtotime((string)$viewRow['created_at'])) ?></span>
            <?php if (isset($viewRow['phone']) && $viewRow['phone'] !== ''): ?><span class="badge bg-light text-dark border"><i class="ti ti-phone me-1"></i><?= admin_e($viewRow['phone']) ?></span><?php endif; ?>
            <?php if (isset($viewRow['name']) && $viewRow['name'] !== ''): ?><span class="badge bg-light text-dark border"><i class="ti ti-user me-1"></i><?= admin_e($viewRow['name']) ?></span><?php endif; ?>
            <?php if (isset($viewRow['email']) && $viewRow['email'] !== ''): ?><span class="badge bg-light text-dark border"><i class="ti ti-mail me-1"></i><?= admin_e($viewRow['email']) ?></span><?php endif; ?>
        </div>

        <?php if ($__payloadArr !== null): ?>
        <!-- ═══ ข้อมูลจากฟอร์ม (label ไทย) ═══ -->
        <div class="row g-2">
            <?php foreach ($__payloadArr as $__pk => $__pv): ?>
            <?php if ($__pv === '' || $__pv === null || $__pv === []) continue; ?>
            <?php if (is_array($__pv)) $__pv = implode(', ', array_filter(array_map('strval', $__pv))); ?>
            <div class="col-md-6 col-xl-4">
                <div class="border rounded p-2 h-100">
                    <div class="text-muted small fw-bold"><?= admin_e($__labels[$__pk] ?? $__pk) ?></div>
                    <div class="text-break"><?= admin_e((string)$__pv) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <details class="mt-3">
            <summary class="text-muted small">ดู JSON ดิบ</summary>
            <pre style="max-height:300px;overflow:auto;font-size:11px"><?= admin_e(json_encode($__payloadArr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) ?></pre>
        </details>
        <?php else: ?>
        <!-- ═══ ฟอร์มทั่วไป (contacts/applications) ═══ -->
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <?php foreach ($viewRow as $k => $v): ?>
                <?php if ($k === 'id' || $k === 'payload') continue; ?>
                <tr>
                    <th style="width:180px" class="align-top"><?= admin_e($__labels[$k] ?? $k) ?></th>
                    <td class="text-break" style="white-space:pre-wrap"><?= admin_e($k === 'created_at' ? date('d/m/Y H:i:s', strtotime((string)$v)) : (string)$v) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php endif; ?>

        <!-- ปุ่มจัดการ -->
        <div class="mt-3 d-flex flex-wrap gap-2">
            <form method="post" class="d-inline">
                <input type="hidden" name="action" value="read"><input type="hidden" name="id" value="<?= (int)$viewRow['id'] ?>">
                <button type="submit" class="btn btn-soft-primary waves-effect"><i class="ti ti-check me-1"></i> ทำเครื่องหมายอ่านแล้ว</button>
            </form>
            <form method="post" class="d-inline">
                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$viewRow['id'] ?>">
                <button type="submit" class="btn btn-outline-danger waves-effect btn-del"><i class="ti ti-trash me-1"></i> ลบ</button>
            </form>
        </div>
    </div>
</div>

<?php else: ?>
<div class="card">
    <div class="card-body">
        <?php if (empty($rows)): ?>
            <p class="text-muted text-center py-4">ยังไม่มีข้อมูลในแท็บนี้</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                <thead>
                <?php if ($tab === 'contacts'): ?>
                    <tr><th>#</th><th>ชื่อ</th><th>เบอร์โทร</th><th>หัวข้อ</th><th>LINE</th><th>ข้อความ</th><th>สถานะ</th><th style="width:120px">จัดการ</th></tr>
                <?php elseif ($tab === 'applications'): ?>
                    <tr><th>#</th><th>ชื่อ</th><th>เบอร์โทร</th><th>อายุ</th><th>วุฒิ</th><th>ประสบการณ์</th><th>LINE</th><th>สถานะ</th><th style="width:120px">จัดการ</th></tr>
                <?php else: ?>
                    <tr><th>#</th><th>รหัสอ้างอิง</th><th>วันที่</th><th>ข้อมูล</th><th>สถานะ</th><th style="width:120px">จัดการ</th></tr>
                <?php endif; ?>
                </thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr class="<?= $r['is_read'] ? '' : 'table-primary' ?>" style="cursor:pointer" onclick="location.href='inbox.php?view=<?= (int)$r['id'] ?>&tab=<?= $tab ?>'">
                    <?php if ($tab === 'contacts'): ?>
                        <td><?= (int)$r['id'] ?></td>
                        <td class="fw-semibold"><?= admin_e($r['name']) ?></td>
                        <td><?= admin_e($r['phone']) ?></td>
                        <td><?= admin_e($r['subject']) ?></td>
                        <td><?= admin_e($r['line']) ?></td>
                        <td class="text-truncate" style="max-width:260px" title="<?= admin_e($r['message']) ?>"><?= admin_e(mb_substr((string)$r['message'], 0, 80)) ?></td>
                        <td><?= $r['is_read'] ? '<span class="badge bg-soft-secondary text-secondary">อ่านแล้ว</span>' : '<span class="badge bg-danger">ใหม่</span>' ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
                            <form method="post" class="d-inline" onclick="event.stopPropagation()">
                                <input type="hidden" name="action" value="read"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-primary" title="ทำเครื่องหมายอ่านแล้ว"><i class="ti ti-check"></i></button>
                            </form>
                            <form method="post" class="d-inline"  onclick="event.stopPropagation()">
                                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-danger btn-del"><i class="ti ti-trash"></i></button>
                            </form>
                        </td>
                    <?php elseif ($tab === 'applications'): ?>
                        <td><?= (int)$r['id'] ?></td>
                        <td class="fw-semibold"><?= admin_e($r['name']) ?></td>
                        <td><?= admin_e($r['phone']) ?></td>
                        <td><?= admin_e($r['age']) ?></td>
                        <td><?= admin_e($r['education']) ?></td>
                        <td class="text-truncate" style="max-width:200px"><?= admin_e($r['experience']) ?></td>
                        <td><?= admin_e($r['line']) ?></td>
                        <td><?= $r['is_read'] ? '<span class="badge bg-soft-secondary text-secondary">อ่านแล้ว</span>' : '<span class="badge bg-danger">ใหม่</span>' ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
                            <form method="post" class="d-inline" onclick="event.stopPropagation()">
                                <input type="hidden" name="action" value="read"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-primary" title="อ่านแล้ว"><i class="ti ti-check"></i></button>
                            </form>
                            <form method="post" class="d-inline"  onclick="event.stopPropagation()">
                                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-danger btn-del"><i class="ti ti-trash"></i></button>
                            </form>
                        </td>
                    <?php else: ?>
                        <td><?= (int)$r['id'] ?></td>
                        <td class="fw-semibold"><?= admin_e($r['ref_code']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                        <td class="text-truncate" style="max-width:340px" title="<?= admin_e((string)$r['payload']) ?>">
                            <?php
                            $__p = json_decode((string)$r['payload'], true);
                            // flatten — payload มีค่าเป็น array ได้ (plans[]/goals[]) → แปลงเป็นข้อความ
                            $__vals = [];
                            foreach (($__p ?: []) as $__v) {
                                if (is_array($__v)) $__v = implode(', ', array_filter(array_map('strval', $__v)));
                                $__vals[] = (string)$__v;
                            }
                            echo admin_e(mb_substr(implode(' | ', array_slice($__vals, 0, 6)), 0, 120));
                            ?>
                        </td>
                        <td><?= $r['is_read'] ? '<span class="badge bg-soft-secondary text-secondary">อ่านแล้ว</span>' : '<span class="badge bg-danger">ใหม่</span>' ?></td>
                        <td>
                            <form method="post" class="d-inline" onclick="event.stopPropagation()">
                                <input type="hidden" name="action" value="read"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-primary" title="อ่านแล้ว"><i class="ti ti-check"></i></button>
                            </form>
                            <form method="post" class="d-inline"  onclick="event.stopPropagation()">
                                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-danger btn-del"><i class="ti ti-trash"></i></button>
                            </form>
                        </td>
                    <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>

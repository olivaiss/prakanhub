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
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title mb-0">รายละเอียด #<?= (int)$viewRow['id'] ?> <?= $viewRow['is_read'] ? '' : '<span class="badge bg-danger">ใหม่</span>' ?></h4>
            <a href="inbox.php?tab=<?= $tab ?>" class="btn btn-secondary waves-effect btn-sm"><i class="ti ti-arrow-left me-1"></i> กลับ</a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <?php foreach ($viewRow as $k => $v): ?>
                <?php if ($k === 'id') continue; ?>
                <tr>
                    <th style="width:180px" class="align-top"><?= admin_e($k) ?></th>
                    <td class="text-break" style="white-space:pre-wrap">
                        <?php if ($k === 'payload' && $v): ?>
                            <?php
                            $__pp = json_decode((string)$v, true);
                            if (is_array($__pp)) {
                                echo '<pre style="max-height:420px;overflow:auto;font-size:12px">' . admin_e(json_encode($__pp, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) . '</pre>';
                            } else {
                                echo admin_e((string)$v);
                            }
                            ?>
                        <?php else: ?>
                            <?= admin_e($k === 'created_at' ? date('d/m/Y H:i:s', strtotime((string)$v)) : (string)$v) ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div class="mt-3 d-flex gap-2">
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

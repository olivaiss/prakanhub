<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'กล่องข้อความ';
$adminMenu = 'inbox';

$tab = $_GET['tab'] ?? 'contacts';
if (!in_array($tab, ['contacts', 'applications', 'form_submissions'], true)) $tab = 'contacts';

// ─── Actions ───
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($action === 'delete' && $id > 0) {
        $db->prepare("DELETE FROM `$tab` WHERE id = ?")->execute([$id]);
        admin_flash('ลบรายการเรียบร้อย');
    } elseif ($action === 'read' && $id > 0) {
        $db->prepare("UPDATE `$tab` SET is_read = 1 WHERE id = ?")->execute([$id]);
    } elseif ($action === 'delete_all') {
        $db->query("TRUNCATE TABLE `$tab`");
        admin_flash('ล้างทั้งหมดเรียบร้อย');
    }
    header('Location: inbox.php?tab=' . $tab);
    exit;
}

// ─── ข้อมูล ───
$rows = $db->query("SELECT * FROM `$tab` ORDER BY id DESC LIMIT 200")->fetchAll();
$unread = [
    'contacts' => (int)$db->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn(),
    'applications' => (int)$db->query("SELECT COUNT(*) FROM agent_applications WHERE is_read = 0")->fetchColumn(),
    'form_submissions' => (int)$db->query("SELECT COUNT(*) FROM form_submissions WHERE is_read = 0")->fetchColumn(),
];
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
                <form method="post" class="d-inline" onsubmit="return confirm('ล้างข้อมูลทั้งหมดในแท็บนี้?')">
                    <input type="hidden" name="action" value="delete_all">
                    <button type="submit" class="btn btn-outline-danger waves-effect"><i class="ti ti-trash me-1"></i> ล้างทั้งหมด</button>
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
                    <tr class="<?= $r['is_read'] ? '' : 'table-primary' ?>">
                    <?php if ($tab === 'contacts'): ?>
                        <td><?= (int)$r['id'] ?></td>
                        <td class="fw-semibold"><?= admin_e($r['name']) ?></td>
                        <td><?= admin_e($r['phone']) ?></td>
                        <td><?= admin_e($r['subject']) ?></td>
                        <td><?= admin_e($r['line']) ?></td>
                        <td class="text-truncate" style="max-width:260px" title="<?= admin_e($r['message']) ?>"><?= admin_e(mb_substr((string)$r['message'], 0, 80)) ?></td>
                        <td><?= $r['is_read'] ? '<span class="badge bg-soft-secondary text-secondary">อ่านแล้ว</span>' : '<span class="badge bg-danger">ใหม่</span>' ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="action" value="read"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-primary" title="ทำเครื่องหมายอ่านแล้ว"><i class="ti ti-check"></i></button>
                            </form>
                            <form method="post" class="d-inline" onsubmit="return confirm('ลบรายการนี้?')">
                                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-danger"><i class="ti ti-trash"></i></button>
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
                            <form method="post" class="d-inline">
                                <input type="hidden" name="action" value="read"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-primary" title="อ่านแล้ว"><i class="ti ti-check"></i></button>
                            </form>
                            <form method="post" class="d-inline" onsubmit="return confirm('ลบรายการนี้?')">
                                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-danger"><i class="ti ti-trash"></i></button>
                            </form>
                        </td>
                    <?php else: ?>
                        <td><?= (int)$r['id'] ?></td>
                        <td class="fw-semibold"><?= admin_e($r['ref_code']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                        <td class="text-truncate" style="max-width:340px" title="<?= admin_e($r['payload']) ?>">
                            <?php
                            $__p = json_decode((string)$r['payload'], true);
                            echo admin_e(mb_substr(implode(' | ', array_slice(array_values($__p ?: []), 0, 6)), 0, 120));
                            ?>
                        </td>
                        <td><?= $r['is_read'] ? '<span class="badge bg-soft-secondary text-secondary">อ่านแล้ว</span>' : '<span class="badge bg-danger">ใหม่</span>' ?></td>
                        <td>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="action" value="read"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-primary" title="อ่านแล้ว"><i class="ti ti-check"></i></button>
                            </form>
                            <form method="post" class="d-inline" onsubmit="return confirm('ลบรายการนี้?')">
                                <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-soft-danger"><i class="ti ti-trash"></i></button>
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

<?php include __DIR__ . '/includes/footer.php'; ?>

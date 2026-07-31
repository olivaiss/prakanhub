<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$adminPageTitle = 'แดชบอร์ด';
$adminMenu = 'dashboard';
include __DIR__ . '/includes/header.php';

$db = admin_db();

// Stats
$stats = [];
foreach ([
    'categories' => 'SELECT COUNT(*) FROM categories',
    'products'   => 'SELECT COUNT(*) FROM products',
    'articles'   => 'SELECT COUNT(*) FROM articles',
    'testimonials' => 'SELECT COUNT(*) FROM testimonials',
    'seminars'   => 'SELECT COUNT(*) FROM seminars',
    'faqs'       => 'SELECT COUNT(*) FROM faqs',
    'pages'      => 'SELECT COUNT(*) FROM pages',
    'members'    => 'SELECT COUNT(*) FROM members',
] as $k => $q) {
    $stats[$k] = (int)$db->query($q)->fetchColumn();
}

// รายการล่าสุด
$recentArticles = $db->query('SELECT id, title, tag, is_active FROM articles ORDER BY id DESC LIMIT 5')->fetchAll();
$recentTestimonials = $db->query('SELECT id, name, rating, is_active FROM testimonials ORDER BY id DESC LIMIT 5')->fetchAll();
$recentMembers = $db->query('SELECT id, member_code, display_name, is_active FROM members ORDER BY id DESC LIMIT 5')->fetchAll();
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">แดชบอร์ด</h6>
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="index.php">Admin</a></li>
                <li class="breadcrumb-item active">แดชบอร์ด</li>
            </ol>
        </div>
        <div class="col-md-4">
            <div class="float-end d-none d-md-block">
                <a href="../index.php" target="_blank" class="btn btn-primary waves-effect waves-light"><i class="fa fa-external-link me-1"></i> ดูเว็บไซต์</a>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <?php
    $cards = [
        ['หมวดหมู่ประกัน', $stats['categories'], 'categories.php', 'ti-layout-grid2', 'primary'],
        ['แผนประกัน', $stats['products'], 'products.php', 'ti-package', 'success'],
        ['บทความ', $stats['articles'], 'articles.php', 'ti-bookmark-alt', 'info'],
        ['รีวิวลูกค้า', $stats['testimonials'], 'testimonials.php', 'ti-face-smile', 'warning'],
        ['สัมมนา', $stats['seminars'], 'seminars.php', 'ti-camera', 'danger'],
        ['FAQ', $stats['faqs'], 'faqs.php', 'ti-help-alt', 'secondary'],
        ['หน้าเนื้อหา', $stats['pages'], 'pages.php', 'ti-file', 'dark'],
        ['รหัสสมาชิก', $stats['members'], 'members.php', 'ti-user', 'primary'],
    ];
    foreach ($cards as [$label, $count, $link, $icon, $color]):
    ?>
    <div class="col-md-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-sm rounded bg-<?= $color ?> bg-soft text-<?= $color ?> d-flex align-items-center justify-content-center">
                            <i class="ti <?= $icon ?> font-size-24"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1 font-size-13"><?= $label ?></p>
                        <h4 class="mb-0 fw-bold"><?= $count ?></h4>
                    </div>
                    <a href="<?= $link ?>" class="text-muted"><i class="ti ti-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Quick actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">ตั้งค่าด่วน</h4>
                <p class="card-title-desc">รายการที่ใช้บ่อย</p>
                <a href="settings.php" class="btn btn-outline-primary m-1 waves-effect"><i class="ti ti-settings me-1"></i> ตั้งค่าเว็บ & SEO</a>
                <a href="categories.php" class="btn btn-outline-primary m-1 waves-effect"><i class="ti ti-layout-grid2 me-1"></i> หมวดหมู่ประกัน</a>
                <a href="pages.php" class="btn btn-outline-primary m-1 waves-effect"><i class="ti ti-file me-1"></i> หน้าเนื้อหา</a>
                <a href="members.php" class="btn btn-outline-primary m-1 waves-effect"><i class="ti ti-user me-1"></i> รหัสสมาชิก</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent lists -->
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">บทความล่าสุด</h4>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                        <?php foreach ($recentArticles as $a): ?>
                            <tr>
                                <td class="text-truncate" style="max-width:180px"><?= admin_e($a['title']) ?></td>
                                <td><span class="badge bg-soft-info text-info"><?= admin_e($a['tag']) ?></span></td>
                                <td><?= $a['is_active'] ? '<span class="badge bg-success">แสดง</span>' : '<span class="badge bg-secondary">ซ่อน</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รีวิวล่าสุด</h4>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                        <?php foreach ($recentTestimonials as $t): ?>
                            <tr>
                                <td><?= admin_e($t['name']) ?></td>
                                <td><span class="text-warning"><?= str_repeat('★', (int)$t['rating']) ?></span></td>
                                <td><?= $t['is_active'] ? '<span class="badge bg-success">แสดง</span>' : '<span class="badge bg-secondary">ซ่อน</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">รหัสสมาชิกล่าสุด</h4>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <tbody>
                        <?php foreach ($recentMembers as $m): ?>
                            <tr>
                                <td class="font-monospace"><?= admin_e($m['member_code']) ?></td>
                                <td><?= admin_e($m['display_name'] ?: '-') ?></td>
                                <td><?= $m['is_active'] ? '<span class="badge bg-success">ใช้งาน</span>' : '<span class="badge bg-secondary">ปิด</span>' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

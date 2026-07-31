<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'ตั้งค่าเว็บ & SEO';
$adminMenu = 'settings';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['section'] ?? '';
    try {
        if ($section === 'seo') {
            foreach (['site_title', 'site_description', 'site_keywords', 'og_image'] as $k) {
                admin_set_setting($k, trim($_POST[$k] ?? ''));
            }
            admin_flash('บันทึก SEO เรียบร้อย');
        } elseif ($section === 'contact') {
            foreach (['phone', 'line_id', 'line_url', 'facebook_url', 'youtube_url', 'instagram_url', 'tiktok_url', 'address', 'working_hours', 'google_maps_embed'] as $k) {
                admin_set_setting($k, trim($_POST[$k] ?? ''));
            }
            admin_flash('บันทึกข้อมูลติดต่อเรียบร้อย');
        } elseif ($section === 'hero') {
            foreach (['hero_title', 'hero_subtitle', 'hero_desc', 'career_title', 'career_desc', 'stat_years', 'stat_clients', 'stat_qualification'] as $k) {
                admin_set_setting($k, trim($_POST[$k] ?? ''));
            }
            admin_flash('บันทึกเนื้อหาหน้าแรกเรียบร้อย');
        } elseif ($section === 'password') {
            $old = $_POST['old_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $stmt = $db->prepare('SELECT password_hash FROM admin_users WHERE id = ?');
            $stmt->execute([(int)$_SESSION['admin_id']]);
            $row = $stmt->fetch();
            if (!$row || !password_verify($old, $row['password_hash'])) {
                throw new Exception('รหัสผ่านเดิมไม่ถูกต้อง');
            }
            if (strlen($new) < 6) throw new Exception('รหัสผ่านใหม่ต้องอย่างน้อย 6 ตัว');
            if ($new !== $confirm) throw new Exception('รหัสผ่านใหม่ไม่ตรงกัน');
            $db->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')
               ->execute([password_hash($new, PASSWORD_BCRYPT), (int)$_SESSION['admin_id']]);
            admin_flash('เปลี่ยนรหัสผ่านเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: settings.php');
    exit;
}

include __DIR__ . '/includes/header.php';

function fld(string $k, string $ph = ''): string {
    $v = admin_setting($k);
    return 'name="' . $k . '" value="' . admin_e($v) . '" placeholder="' . admin_e($ph) . '"';
}
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">ตั้งค่าเว็บ & SEO</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">ตั้งค่า</li></ol>
        </div>
    </div>
</div>

<div class="row">
    <!-- SEO -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="ti ti-search me-1"></i> SEO (ทั้งเว็บ)</h4>
                <p class="card-title-desc">meta title / description / keywords / OG image</p>
                <form method="post">
                    <input type="hidden" name="section" value="seo">
                    <div class="mb-3"><label class="form-label">Site Title</label><input type="text" class="form-control" <?= fld('site_title') ?>></div>
                    <div class="mb-3"><label class="form-label">Meta Description</label><textarea class="form-control" name="site_description" rows="3"><?= admin_e(admin_setting('site_description')) ?></textarea></div>
                    <div class="mb-3"><label class="form-label">Keywords (คั่นด้วย ,)</label><input type="text" class="form-control" <?= fld('site_keywords') ?>></div>
                    <div class="mb-3"><label class="form-label">OG Image (URL)</label><input type="text" class="form-control" <?= fld('og_image') ?>></div>
                    <button type="submit" class="btn btn-primary waves-effect">บันทึก SEO</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Contact -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="ti ti-phone me-1"></i> ข้อมูลติดต่อ</h4>
                <p class="card-title-desc">ใช้ใน footer / contact / schema</p>
                <form method="post">
                    <input type="hidden" name="section" value="contact">
                    <div class="mb-3"><label class="form-label">เบอร์โทร</label><input type="text" class="form-control" <?= fld('phone') ?>></div>
                    <div class="mb-3"><label class="form-label">LINE ID</label><input type="text" class="form-control" <?= fld('line_id') ?>></div>
                    <div class="mb-3"><label class="form-label">LINE URL</label><input type="text" class="form-control" <?= fld('line_url') ?>></div>
                    <div class="mb-3"><label class="form-label">Facebook URL</label><input type="text" class="form-control" <?= fld('facebook_url') ?>></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">YouTube URL</label><input type="text" class="form-control" <?= fld('youtube_url') ?>></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Instagram URL</label><input type="text" class="form-control" <?= fld('instagram_url') ?>></div>
                    </div>
                    <div class="mb-3"><label class="form-label">TikTok URL</label><input type="text" class="form-control" <?= fld('tiktok_url') ?>></div>
                    <div class="mb-3"><label class="form-label">ที่อยู่</label><input type="text" class="form-control" <?= fld('address') ?>></div>
                    <div class="mb-3"><label class="form-label">เวลาทำการ</label><input type="text" class="form-control" <?= fld('working_hours') ?>></div>
                    <div class="mb-3"><label class="form-label">Google Maps iframe (URL ฝัง)</label><input type="text" class="form-control" <?= fld('google_maps_embed') ?>></div>
                    <button type="submit" class="btn btn-primary waves-effect">บันทึกข้อมูลติดต่อ</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Hero -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="ti ti-layout me-1"></i> หน้าแรก (Hero / Career Banner)</h4>
                <form method="post">
                    <input type="hidden" name="section" value="hero">
                    <div class="mb-3"><label class="form-label">Hero Title</label><input type="text" class="form-control" <?= fld('hero_title') ?>></div>
                    <div class="mb-3"><label class="form-label">Hero Subtitle</label><input type="text" class="form-control" <?= fld('hero_subtitle') ?>></div>
                    <div class="mb-3"><label class="form-label">Hero คำโปรย</label><textarea class="form-control" name="hero_desc" rows="2"><?= admin_e(admin_setting('hero_desc')) ?></textarea></div>
                    <hr>
                    <div class="mb-3"><label class="form-label">Career Banner หัวข้อ</label><input type="text" class="form-control" <?= fld('career_title') ?>></div>
                    <div class="mb-3"><label class="form-label">Career Banner คำโปรย</label><input type="text" class="form-control" <?= fld('career_desc') ?>></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 mb-3"><label class="form-label">Stat 1 (ปี)</label><input type="text" class="form-control" <?= fld('stat_years') ?>></div>
                        <div class="col-md-4 mb-3"><label class="form-label">Stat 2 (ลูกค้า)</label><input type="text" class="form-control" <?= fld('stat_clients') ?>></div>
                        <div class="col-md-4 mb-3"><label class="form-label">Stat 3 (คุณวุฒิ)</label><input type="text" class="form-control" <?= fld('stat_qualification') ?>></div>
                    </div>
                    <button type="submit" class="btn btn-primary waves-effect">บันทึกหน้าแรก</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Password -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><i class="ti ti-lock me-1"></i> เปลี่ยนรหัสผ่าน Admin</h4>
                <form method="post">
                    <input type="hidden" name="section" value="password">
                    <div class="mb-3"><label class="form-label">รหัสผ่านเดิม</label><input type="password" class="form-control" name="old_password" required></div>
                    <div class="mb-3"><label class="form-label">รหัสผ่านใหม่</label><input type="password" class="form-control" name="new_password" required minlength="6"></div>
                    <div class="mb-3"><label class="form-label">ยืนยันรหัสผ่านใหม่</label><input type="password" class="form-control" name="confirm_password" required></div>
                    <button type="submit" class="btn btn-warning waves-effect">เปลี่ยนรหัสผ่าน</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

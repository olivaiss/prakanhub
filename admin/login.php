<?php
require_once __DIR__ . '/includes/auth.php';

if (admin_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if ($u !== '' && admin_login($u, $p)) {
        header('Location: index.php');
        exit;
    }
    $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | Admin Prakanhub</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/icons.min.css">
    <link rel="stylesheet" href="assets/css/app.min.css">
    <style>body { font-family: 'Prompt','Sarabun',sans-serif; }</style>
</head>
<body>
<div class="account-pages my-5 pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary">
                        <div class="text-primary text-center p-4">
                            <h5 class="text-white font-size-20">📋 Admin Prakanhub</h5>
                            <p class="text-white-50 mb-0">ประกันจริงใจ by ปกป้อง — ระบบจัดการเว็บไซต์</p>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="p-3">
                            <?php if ($error): ?>
                                <div class="alert alert-danger py-2"><i class="fa fa-exclamation-circle me-1"></i> <?= admin_e($error) ?></div>
                            <?php endif; ?>
                            <form class="mt-2" method="post" autocomplete="off">
                                <div class="mb-3">
                                    <label class="form-label">ชื่อผู้ใช้</label>
                                    <input type="text" name="username" class="form-control" required autofocus>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">รหัสผ่าน</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">เข้าสู่ระบบ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="text-center text-muted">
                    <p><a href="../index.php" class="text-primary">← กลับหน้าเว็บไซต์</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

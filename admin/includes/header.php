<?php
/**
 * Admin Layout — Header (Veltrix Bootstrap 5)
 * ต้อง login ก่อนทุกหน้า (guard เรียกในแต่ละหน้าเอง)
 */
if (!isset($adminPageTitle)) $adminPageTitle = 'Admin';
$adminMenu = isset($adminMenu) ? $adminMenu : 'dashboard';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= admin_e($adminPageTitle) ?> | Admin Prakanhub</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" id="bootstrap-style">
    <link rel="stylesheet" href="assets/css/icons.min.css">
    <link rel="stylesheet" href="assets/css/app.min.css" id="app-style">
    <style>
        body { font-family: 'Prompt', 'Sarabun', sans-serif; }
        .card-title { font-weight: 700; }
        .table img { border-radius: .35rem; }
        .thumb-sm { width: 48px; height: 48px; object-fit: cover; border-radius: .35rem; }
        .thumb-xs { width: 36px; height: 36px; object-fit: cover; border-radius: .35rem; }
        .required-flag { color: #f46a6a; }
        textarea.form-control { min-height: 110px; }
        .sidebar-logo { padding: 1.2rem 1.5rem; font-weight: 800; letter-spacing: .5px; }
        .sidebar-logo span { color: #fff; }
        .sidebar-logo small { color: #8ba4c0; font-weight: 500; }

        /* ═══ Responsive ทุกโหมด (มือถือ/แท็บเล็ต/แล็ปท็อป/เดสก์ท็อป) ═══ */
        /* โลโก้ topbar: ใหญ่ + กึ่งกลางในกล่องแบรนด์ */
        .navbar-brand-box { display: flex; align-items: center; justify-content: center; min-width: 240px; padding: 0 .75rem; text-align: center; }
        .navbar-brand-box .logo { display: inline-flex; align-items: center; justify-content: center; margin: 0; }
        .navbar-brand-box .logo-sm img, .navbar-brand-box .logo-lg img { display: block; }
        /* ย่อตาม sidebar (vertical-collpsed): กล่อง 70px + แสดงเฉพาะ logo-sm */
        body.vertical-collpsed .navbar-brand-box { min-width: 0; width: 70px; padding: 0; }
        body.vertical-collpsed .navbar-brand-box .logo-lg { display: none !important; }
        body.vertical-collpsed .navbar-brand-box .logo-sm { display: inline-flex !important; }
        /* ทำให้ topbar + กล่องโลโก้ + sidebar เป็นสีเดียวกัน — ไม่มีเส้นแบ่ง/ขอบ */
        #page-topbar, .navbar-brand-box { background-color: var(--bs-sidebar-dark-bg) !important; }
        @media (max-width: 991.98px) {
            .navbar-brand-box { min-width: 0; padding: 0 1rem; }
        }
        @media (max-width: 575.98px) {
            .navbar-brand-box { padding: 0 .5rem; }
        }
        /* ฟอร์มฟิลด์ไม่เล็กเกินบนมือถือ */
        @media (max-width: 575.98px) {
            .form-control, .form-select { font-size: 16px; } /* กัน iOS zoom */
            .card-body { padding: 1rem; }
            .page-title-box { padding: 0.75rem 0; }
            h4.card-title { font-size: 1rem; }
            .btn { white-space: normal; }
            /* ปุ่มในแถวตารางไม่ล้นจอ */
            .table .btn { padding: .25rem .4rem; font-size: .72rem; }
            /* ฟอร์มโหมดแก้ไข: ปุ่มเต็มความกว้าง */
            .col-12 > .btn, .col-12 .d-flex .btn { font-size: .85rem; }
        }
        @media (min-width: 576px) and (max-width: 767.98px) {
            .card-body { padding: 1.1rem; }
        }
        /* ตารางบังคับเลื่อนแนวนอนใน card (เผื่อ wrapper ไม่มี) */
        .table-responsive { -webkit-overflow-scrolling: touch; }
        .table-responsive .table { min-width: 720px; }
        /* Datatable control บนมือถือ */
        @media (max-width: 767.98px) {
            .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
                width: 100%; text-align: left; margin-bottom: .5rem;
            }
            .dataTables_wrapper .dataTables_filter input { width: 100% !important; }
        }
        /* Uploads grid */
        .upload-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: .75rem; }
        @media (max-width: 575.98px) {
            .upload-grid { grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: .5rem; }
        }
        /* Topbar: ซ่อนข้อความยาวบนจอเล็ก */
        @media (max-width: 575.98px) {
            .navbar-brand-box .sidebar-logo small { display: none; }
            .navbar-brand-box .sidebar-logo { padding: 1rem .75rem; font-size: .9rem; }
        }
        /* Flash alert กระชับบนมือถือ */
        @media (max-width: 575.98px) {
            .alert { font-size: .85rem; padding: .65rem .9rem; }
        }
    </style>
</head>
<body data-sidebar="dark" data-layout-mode="light">

<div id="layout-wrapper">

    <!-- ═══════ TOPBAR ═══════ -->
    <header id="page-topbar">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO (ตาม template Veltrix) -->
                <div class="navbar-brand-box">
                    <a href="index.php" class="logo logo-dark">
                        <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="Prakanhub" height="42"></span>
                        <span class="logo-lg"><img src="assets/images/logo-dark.png" alt="Prakanhub Admin" height="38"></span>
                    </a>
                    <a href="index.php" class="logo logo-light">
                        <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="Prakanhub" height="42"></span>
                        <span class="logo-lg"><img src="assets/images/logo-light.png" alt="Prakanhub Admin" height="38"></span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                    <i class="mdi mdi-menu"></i>
                </button>
            </div>
            <div class="d-flex ms-auto align-items-center">
                <a href="../index.php" target="_blank" class="btn btn-sm btn-light me-3 d-none d-md-inline-flex align-items-center gap-1 waves-effect">
                    <i class="fa fa-external-link"></i> ดูเว็บไซต์
                </a>
                <div class="dropdown d-inline-block">
                    <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="true">
                        <i class="fa fa-user-circle font-size-22 text-muted"></i>
                        <span class="ms-1 d-none d-sm-inline-block text-muted font-size-13 fw-medium"><?= admin_e(admin_name()) ?></span>
                        <i class="mdi mdi-chevron-down d-none d-sm-inline-block text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out me-1"></i> ออกจากระบบ</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════ SIDEBAR ═══════ -->
    <div class="vertical-menu">
        <div data-simplebar class="h-100">
            <div id="sidebar-menu">
                <ul class="metismenu list-unstyled" id="side-menu">
                    <li class="menu-title">เมนูหลัก</li>
                    <li><a href="index.php" class="waves-effect <?= $adminMenu === 'dashboard' ? 'active' : '' ?>"><i class="ti ti-home"></i><span>แดชบอร์ด</span></a></li>

                    <li class="menu-title">จัดการเนื้อหา</li>
                    <li><a href="categories.php" class="waves-effect <?= $adminMenu === 'categories' ? 'active' : '' ?>"><i class="ti ti-layout-grid2"></i><span>หมวดหมู่ประกัน</span></a></li>
                    <li><a href="products.php" class="waves-effect <?= $adminMenu === 'products' ? 'active' : '' ?>"><i class="ti ti-package"></i><span>แผนประกัน (60)</span></a></li>
                    <li><a href="articles.php" class="waves-effect <?= $adminMenu === 'articles' ? 'active' : '' ?>"><i class="ti ti-bookmark-alt"></i><span>บทความ</span></a></li>
                    <li><a href="testimonials.php" class="waves-effect <?= $adminMenu === 'testimonials' ? 'active' : '' ?>"><i class="ti ti-face-smile"></i><span>รีวิวลูกค้า</span></a></li>
                    <li><a href="seminars.php" class="waves-effect <?= $adminMenu === 'seminars' ? 'active' : '' ?>"><i class="ti ti-camera"></i><span>สัมมนา</span></a></li>
                    <li><a href="faqs.php" class="waves-effect <?= $adminMenu === 'faqs' ? 'active' : '' ?>"><i class="ti ti-help-alt"></i><span>FAQ</span></a></li>
                    <li><a href="pages.php" class="waves-effect <?= $adminMenu === 'pages' ? 'active' : '' ?>"><i class="ti ti-file"></i><span>หน้าเนื้อหา</span></a></li>
                    <li><a href="menus.php" class="waves-effect <?= $adminMenu === 'menus' ? 'active' : '' ?>"><i class="ti ti-menu"></i><span>เมนู</span></a></li>

                    <li class="menu-title">ข้อมูลลูกค้า</li>
                    <li><a href="inbox.php" class="waves-effect <?= $adminMenu === 'inbox' ? 'active' : '' ?>"><i class="ti ti-inbox"></i><span>กล่องข้อความ</span></a></li>
                    <li><a href="banners.php" class="waves-effect <?= $adminMenu === 'banners' ? 'active' : '' ?>"><i class="ti ti-photo"></i><span>แบนเนอร์</span></a></li>
                    <li><a href="uploads.php" class="waves-effect <?= $adminMenu === 'uploads' ? 'active' : '' ?>"><i class="ti ti-upload"></i><span>อัปโหลดรูป</span></a></li>

                    <li class="menu-title">ระบบสมาชิก</li>
                    <li><a href="courses.php" class="waves-effect <?= $adminMenu === 'courses' ? 'active' : '' ?>"><i class="ti ti-video-clapper"></i><span>คอร์สเรียน</span></a></li>
                    <li><a href="members.php" class="waves-effect <?= $adminMenu === 'members' ? 'active' : '' ?>"><i class="ti ti-user"></i><span>รหัสสมาชิก</span></a></li>

                    <li class="menu-title">ตั้งค่า</li>
                    <li><a href="settings.php" class="waves-effect <?= $adminMenu === 'settings' ? 'active' : '' ?>"><i class="ti ti-settings"></i><span>ตั้งค่าเว็บ & SEO</span></a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- ═══════ CONTENT ═══════ -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php
                // Flash message
                $flash = admin_get_flash();
                if ($flash):
                    $flashCls = $flash['type'] === 'error' ? 'danger' : $flash['type'];
                ?>
                <div class="alert alert-<?= $flashCls ?> alert-dismissible fade show mt-2" role="alert">
                    <?= admin_e($flash['msg']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

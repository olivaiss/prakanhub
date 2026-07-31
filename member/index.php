<?php
require_once __DIR__ . '/config.php';

// ถ้า login อยู่แล้ว → เข้าหน้าเรียนเลย
if (member_logged_in()) {
    header('Location: /member/home.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['member_code'] ?? '');
    if (member_check_code($code)) {
        $_SESSION['member_logged_in'] = true;
        $_SESSION['member_code'] = $code;
        $_SESSION['member_login_at'] = date('Y-m-d H:i:s');
        $next = isset($_GET['next']) && preg_match('#^[a-z0-9_\-./?=&]+$#i', $_GET['next']) ? $_GET['next'] : '/member/home.php';
        header('Location: ' . $next);
        exit;
    }
    $error = 'รหัสสมาชิกไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง';
}

$pageTitle = 'เข้าสู่ระบบสมาชิก';
include __DIR__ . '/../includes/header.php';
?>

<style>
    .member-login-card {
        max-width: 440px;
        margin: 0 auto;
        background: #fff;
        border-radius: 1.25rem;
        border: 1px solid #E2E8F0;
        box-shadow: 0 10px 40px -12px rgba(0, 55, 129, 0.15);
    }
    .member-input {
        width: 100%;
        border: 1.5px solid #CBD5E1;
        border-radius: 0.75rem;
        padding: 0.85rem 1rem;
        font-size: 1.125rem;
        letter-spacing: 0.15em;
        text-align: center;
        transition: border-color .2s, box-shadow .2s;
    }
    .member-input:focus {
        outline: none;
        border-color: #003781;
        box-shadow: 0 0 0 3px rgba(0, 55, 129, 0.12);
    }
    .member-btn {
        width: 100%;
        background: #003781;
        color: #fff;
        font-weight: 700;
        padding: 0.85rem;
        border-radius: 0.75rem;
        transition: background .2s, transform .1s;
    }
    .member-btn:hover { background: #00265A; }
    .member-btn:active { transform: scale(0.98); }
</style>

<!-- HERO -->
<section class="bg-brand-light border-b border-gray-200">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-10 text-center">
        <div class="inline-flex items-center gap-2 bg-white border border-brand-navy/15 text-brand-navy text-xs font-bold px-3 py-1.5 rounded-full mb-4">
            <i data-lucide="graduation-cap" class="w-4 h-4"></i> ระบบเรียนรู้สมาชิก
        </div>
        <h1 class="text-2xl md:text-4xl font-bold text-brand-navy mb-2">เข้าสู่ระบบสมาชิก</h1>
        <p class="text-brand-gray max-w-xl mx-auto">กรอกรหัสสมาชิก 18 หลัก เพื่อเข้าเรียนคอร์สวิดีโอสำหรับสมาชิก</p>
    </div>
</section>

<!-- LOGIN CARD -->
<section class="py-12 md:py-16">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <div class="member-login-card p-8 md:p-10">
            <div class="flex flex-col items-center text-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-brand-light flex items-center justify-center mb-4">
                    <i data-lucide="lock-keyhole" class="w-8 h-8 text-brand-navy"></i>
                </div>
                <h2 class="text-lg font-bold text-brand-text">ระบบสมาชิกผู้เรียน</h2>
                <p class="text-xs text-brand-gray mt-1">รหัสอยู่ที่คู่มือสมาชิกที่แจกในงานอบรม</p>
            </div>

            <?php if ($error): ?>
                <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 text-sm font-medium rounded-xl px-4 py-3 mb-5">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" autocomplete="off">
                <label for="member_code" class="block text-xs font-bold text-brand-text mb-2">รหัสสมาชิก (18 หลัก)</label>
                <input type="text" id="member_code" name="member_code" required
                       pattern="[0-9]{18}" maxlength="18" inputmode="numeric"
                       class="member-input" placeholder="•••• •••• •••• •••• ••••"
                       autocomplete="off">
                <p class="text-[11px] text-brand-gray mt-2 flex items-center gap-1">
                    <i data-lucide="info" class="w-3 h-3"></i> ตัวเลข 18 หลักติดกันเท่านั้น
                </p>
                <button type="submit" class="member-btn mt-5 flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i> เข้าสู่ระบบ
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-center gap-2 text-[11px] text-brand-gray">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-brand-green"></i>
                ข้อมูลสมาชิกและความคืบหน้าการเรียนถูกเก็บเป็นความลับ
            </div>
        </div>
    </div>
</section>

<script>
    // auto ตัวเลขเท่านั้น
    document.getElementById('member_code').addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 18);
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>

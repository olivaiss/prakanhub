<?php
$pageTitle = 'แบบฟอร์มทำประกันออนไลน์';

// Dual-mode include: ใช้ได้ทั้งโหมดโฟลเดอร์ย่อย (prakanhub.com/form/) และโหมด subdomain (form.prakanhub.com)
if (file_exists(__DIR__ . '/../includes/header.php')) {
    include __DIR__ . '/../includes/header.php';
} else {
    include __DIR__ . '/includes/header.php';
}

$submitted = false;
$errors = [];
$summary = [];
$refCode = '';

$labels = [
    'plans' => 'แผนประกันที่สนใจ', 'budget' => 'งบประมาณเบี้ยประกันต่อปี', 'goals' => 'เป้าหมายในการทำประกัน',
    'prefix_th' => 'คำนำหน้า (ไทย)', 'firstname_th' => 'ชื่อ (ไทย)', 'lastname_th' => 'นามสกุล (ไทย)',
    'prefix_en' => 'คำนำหน้า (อังกฤษ)', 'firstname_en' => 'ชื่อ (อังกฤษ)', 'lastname_en' => 'นามสกุล (อังกฤษ)',
    'birthdate' => 'วันเกิด', 'id_card' => 'เลขบัตรประชาชน', 'id_laser' => 'รหัสหลังบัตร', 'id_expiry' => 'วันที่บัตรหมดอายุ',
    'marital_status' => 'สถานภาพ', 'nationality' => 'สัญชาติ', 'other_nationality' => 'มีสัญชาติอื่น', 'other_nationality_detail' => 'สัญชาติอื่น (ระบุ)',
    'weight' => 'น้ำหนัก (กก.)', 'height' => 'ส่วนสูง (ซม.)',
    'workplace' => 'สถานที่ทำงาน', 'workplace_address' => 'ที่อยู่สถานที่ทำงาน',
    'policy_count' => 'ถือประกันกี่สัญญา', 'policy_companies' => 'บริษัทประกันที่ถืออยู่', 'policy_life_sum' => 'ทุนชีวิต (บาท)', 'policy_accident_sum' => 'ทุนอุบัติเหตุ (บาท)',
    'tax_deduction' => 'ส่งข้อมูลให้สรรพากรลดหย่อนภาษี',
    'spouse_prefix' => 'คำนำหน้า (ผู้สมรส)', 'spouse_relation' => 'สามี/ภรรยา', 'spouse_name' => 'ชื่อ-สกุลผู้สมรส',
    'beneficiary_name' => 'ชื่อ-สกุลผู้รับผลประโยชน์', 'beneficiary_relation' => 'ความสัมพันธ์', 'beneficiary_relation_detail' => 'ความสัมพันธ์อื่น (ระบุ)',
    'contact_type' => 'สถานที่ติดต่อ', 'contact_address' => 'ที่อยู่ติดต่อ', 'mobile' => 'เบอร์โทรศัพท์มือถือ', 'email' => 'อีเมล',
    'occupation' => 'ลักษณะอาชีพ', 'position' => 'ตำแหน่ง', 'work_detail' => 'ลักษณะงานที่ทำ', 'business_detail' => 'ลักษณะธุรกิจของบริษัท',
    'income' => 'รายได้ต่อปี (บาท)', 'smoking' => 'การสูบบุหรี่', 'smoking_detail' => 'สูบ (ระบุจำนวน)', 'alcohol' => 'ดื่มแอลกอฮอล์เป็นประจำ',
    'rejected' => 'เคยถูกปฏิเสธการรับประกัน/เพิ่มเบี้ย', 'rejected_detail' => 'รายละเอียด (ระบุ)', 'name_changed' => 'เคยเปลี่ยนชื่อ/นามสกุล', 'old_name' => 'ชื่อ-นามสกุลเดิม',
    'nickname' => 'ชื่อเล่น/ชื่อที่เรียกอื่นๆ',
    'health_checks' => 'ในช่วง 5 ปี เคยตรวจร่างกาย', 'health_reason' => 'สาเหตุที่ไปตรวจ', 'health_reason_detail' => 'สาเหตุอื่น (ระบุ)',
    'hospital_stays' => 'เคยเข้าพักรักษาที่โรงพยาบาล (ครั้ง)', 'diseases' => 'โรคประจำตัว/โรคที่เคยรักษาหรือเคยเป็น',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Honeypot (anti-spam) — ถ้าถูกกรอกแสดงว่าเป็นบอท ไม่ต้องทำอะไร
    if (empty($_POST['website'])) {
        if (empty($_POST['consent'])) {
            $errors[] = 'กรุณายินยอมให้เก็บรวบรวมข้อมูลส่วนบุคคลก่อนส่งแบบฟอร์ม';
        } else {
            $required = ['prefix_th', 'firstname_th', 'lastname_th', 'birthdate', 'id_card', 'mobile', 'email', 'beneficiary_name'];
            foreach ($required as $f) {
                if (empty($_POST[$f])) {
                    $errors[] = 'กรุณากรอกข้อมูลให้ครบถ้วน (ช่องที่มีเครื่องหมาย * จำเป็นต้องกรอก)';
                    break;
                }
            }
            if (!$errors) {
                $submitted = true;
                $refCode = 'L-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 6));
                foreach ($labels as $key => $label) {
                    $val = $_POST[$key] ?? '';
                    if (is_array($val)) {
                        $val = implode(', ', array_filter($val));
                    }
                    $val = trim((string)$val);
                    if ($val !== '') {
                        $summary[] = ['label' => $label, 'value' => $val];
                    }
                }
                // บันทึกข้อมูลลงไฟล์ JSON (สำหรับตัวแทนตรวจสอบ)
                $dir = __DIR__ . '/submissions';
                if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
                $payload = $_POST;
                unset($payload['website']); // เอาค่า honeypot ออก
                $payload['ref'] = $refCode;
                $payload['submitted_at'] = date('Y-m-d H:i:s');
                @file_put_contents($dir . '/' . $refCode . '.json', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            }
        }
    }
}
?>

<style>
    /* ─── Form styles ─── */
    .form-input {
        width: 100%;
        padding: .75rem 1rem;
        border-radius: .75rem;
        border: 1px solid #e5e7eb;
        background: #fff;
        outline: none;
        font-size: .875rem;
        color: #1E293B;
        transition: all .2s;
    }
    .form-input:focus {
        border-color: transparent;
        box-shadow: 0 0 0 2px #003781;
    }
    .form-input::placeholder { color: #9ca3af; }
    select.form-input { appearance: auto; }
    .form-label {
        display: block;
        font-size: .875rem;
        font-weight: 500;
        color: #1E293B;
        margin-bottom: .35rem;
    }
    .req { color: #dc2626; }

    /* ─── Step indicator ─── */
    .step-dot {
        width: 42px; height: 42px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: .875rem;
        background: #fff; color: #64748B;
        border: 2px solid #e5e7eb;
        transition: all .25s;
        flex-shrink: 0;
    }
    .step-dot.active { background: #003781; border-color: #003781; color: #fff; box-shadow: 0 4px 12px rgba(0,55,129,.25); }
    .step-dot.done { background: #00C300; border-color: #00C300; color: #fff; }
    .step-line { height: 2px; flex: 1; background: #e5e7eb; border-radius: 2px; transition: background .3s; min-width: 6px; }
    .step-line.done { background: #00C300; }

    /* ─── Section title in card ─── */
    .sec-title {
        display: flex; align-items: center; gap: .6rem;
        font-size: 1.05rem; font-weight: 700; color: #003781;
        margin-bottom: 1.25rem;
    }
    .sec-title .num {
        width: 30px; height: 30px; border-radius: .6rem;
        background: #EEF4F9; color: #003781;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .8rem; font-weight: 700; flex-shrink: 0;
    }

    /* ─── Radio option rows ─── */
    .opt-row { display: flex; align-items: center; gap: .5rem; }
    .opt-row input[type="radio"], .opt-row input[type="checkbox"] {
        accent-color: #003781; width: 1.05rem; height: 1.05rem; flex-shrink: 0;
    }

    /* ─── Review table ─── */
    .review-row {
        display: grid; grid-template-columns: 1fr; gap: .15rem;
        padding: .7rem 0; border-bottom: 1px dashed #e5e7eb;
    }
    .review-row:last-child { border-bottom: none; }
    .review-label { font-size: .72rem; color: #64748B; }
    .review-value { font-size: .875rem; font-weight: 600; color: #1E293B; word-break: break-word; }
    @media (min-width: 768px) {
        .review-row { grid-template-columns: 260px 1fr; gap: 1rem; }
    }

    /* ─── หน้าแบบฟอร์ม: ซ่อน popup dev (บังการกรอกฟอร์ม) ─── */
    #dev-popup { display: none !important; }
</style>

<!-- PAGE HERO -->
<section class="bg-brand-navy text-white py-14">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-3 flex items-center justify-center gap-3">
            <i data-lucide="file-text" class="w-9 h-9"></i> แบบฟอร์มทำประกันออนไลน์
        </h1>
        <p class="text-blue-200 text-base md:text-lg max-w-2xl mx-auto">
            กรอกข้อมูลเพื่อให้ที่ปรึกษาจัดเตรียมแบบเสนอความคุ้มครอง Allianz Ayudhya ให้คุณ
        </p>
    </div>
</section>

<section class="py-12 md:py-16 bg-brand-light">
    <div class="max-w-[1000px] mx-auto px-4 md:px-8">

        <!-- Success view -->
        <?php if ($submitted): ?>
        <div class="bg-white rounded-3xl shadow-card border border-gray-100 p-6 md:p-10">
            <div class="text-center mb-8">
                <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-5">
                    <i data-lucide="check-circle-2" class="w-10 h-10 text-green-600"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-brand-navy mb-2">ส่งข้อมูลเรียบร้อยแล้ว!</h2>
                <p class="text-brand-gray">ขอบคุณที่กรอกข้อมูล เราจะติดต่อกลับโดยเร็วที่สุด</p>
                <div class="inline-flex items-center gap-2 bg-brand-light rounded-full px-4 py-1.5 mt-4 text-sm">
                    <span class="text-brand-gray">รหัสอ้างอิง:</span>
                    <span class="font-bold text-brand-navy"><?= htmlspecialchars($refCode) ?></span>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4 mb-6"><?= htmlspecialchars(implode('<br>', $errors)) ?></div>
            <?php endif; ?>

            <div class="bg-brand-light/60 rounded-2xl p-5 md:p-6">
                <h3 class="font-bold text-brand-navy mb-4 flex items-center gap-2">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i> สรุปข้อมูลที่ส่ง
                </h3>
                <?php foreach ($summary as $row): ?>
                <div class="review-row">
                    <div class="review-label"><?= htmlspecialchars($row['label']) ?></div>
                    <div class="review-value"><?= htmlspecialchars($row['value']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="bg-brand-light rounded-2xl p-5 mt-6 text-center">
                <p class="text-sm text-brand-text mb-4">มีคำถามเพิ่มเติม ติดต่อเราได้ทันที</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="https://lin.ee/QngrNQ3" target="_blank" class="inline-flex items-center gap-2 bg-brand-green hover:bg-brand-greenHover text-white px-5 py-2.5 rounded-full text-sm font-bold transition">
                        <img src="/assets/icon/line.svg" class="w-4 h-4" alt="LINE"> LINE @945ampel
                    </a>
                    <a href="tel:092-515-9991" class="inline-flex items-center gap-2 bg-brand-navy hover:bg-brand-navyHover text-white px-5 py-2.5 rounded-full text-sm font-bold transition">
                        <i data-lucide="phone" class="w-4 h-4"></i> 092-515-9991
                    </a>
                </div>
            </div>
        </div>

        <?php else: ?>

        <!-- Info bar -->
        <div class="flex items-center gap-3 bg-white border border-gray-100 rounded-2xl px-5 py-4 mb-6 shadow-sm">
            <i data-lucide="lock" class="w-5 h-5 text-brand-green shrink-0"></i>
            <p class="text-xs md:text-sm text-brand-text">
                ข้อมูลของคุณจะถูกเก็บเป็นความลับ และใช้สำหรับการจัดทำประกันเท่านั้น
                ช่องที่มีเครื่องหมาย <span class="req font-bold">*</span> จำเป็นต้องกรอก
            </p>
        </div>

        <form id="insurance-form" method="POST" action="" class="bg-white rounded-3xl shadow-card border border-gray-100 p-5 md:p-10" autocomplete="on">
            <!-- Honeypot (anti-spam) -->
            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

            <!-- Step indicator -->
            <div class="flex items-center gap-1 md:gap-2 mb-8">
                <div class="step-dot active" data-step="1"><i data-lucide="shield-check" class="w-4 h-4"></i></div>
                <div class="step-line"></div>
                <div class="step-dot" data-step="2"><i data-lucide="user" class="w-4 h-4"></i></div>
                <div class="step-line"></div>
                <div class="step-dot" data-step="3"><i data-lucide="users" class="w-4 h-4"></i></div>
                <div class="step-line"></div>
                <div class="step-dot" data-step="4"><i data-lucide="briefcase" class="w-4 h-4"></i></div>
                <div class="step-line"></div>
                <div class="step-dot" data-step="5"><i data-lucide="heart-pulse" class="w-4 h-4"></i></div>
                <div class="step-line"></div>
                <div class="step-dot" data-step="6"><i data-lucide="check" class="w-4 h-4"></i></div>
            </div>
            <div class="flex flex-wrap justify-between text-[10px] md:text-[11px] font-bold text-brand-gray mb-8 px-1 md:px-2">
                <span>แผนประกัน</span>
                <span>ผู้เอาประกัน</span>
                <span>ครอบครัว</span>
                <span>ติดต่อ/อาชีพ</span>
                <span>สุขภาพ</span>
                <span>ยืนยัน</span>
            </div>

            <!-- ══════════ STEP 1: แผนประกันที่สนใจ ══════════ -->
            <div class="form-step" data-step="1">
                <h2 class="sec-title"><span class="num">1</span> แผนประกันที่สนใจ</h2>
                <p class="text-sm text-brand-gray mb-5">เลือกได้มากกว่า 1 แผน เพื่อให้ที่ปรึกษาเตรียมแบบเสนอความคุ้มครองให้เหมาะสมกับคุณ</p>

                <fieldset class="mb-7">
                    <legend class="sr-only">แผนประกันที่สนใจ</legend>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        <?php
                        $planList = [
                            ['heart', 'ประกันชีวิต'],
                            ['activity', 'ประกันสุขภาพ'],
                            ['shield-alert', 'ประกันโรคร้ายแรง'],
                            ['footprints', 'ประกันอุบัติเหตุ'],
                            ['baby', 'ประกันเด็ก'],
                            ['landmark', 'ประกันออมทรัพย์'],
                            ['home', 'ประกันเกษียณ/บำนาญ'],
                            ['dollar-sign', 'ประกันชดเชยรายได้'],
                            ['users', 'ประกันกลุ่ม/องค์กร'],
                            ['car', 'ประกันรถยนต์'],
                            ['plane', 'ประกันเดินทาง'],
                            ['sparkles', 'อื่นๆ / ให้แนะนำ'],
                        ];
                        $firstPlan = true;
                        foreach ($planList as $p):
                        ?>
                        <label class="relative cursor-pointer">
                            <input type="checkbox" name="plans[]" value="<?= $p[1] ?>" <?= $firstPlan ? 'required' : '' ?> class="peer sr-only">
                            <div class="border border-gray-200 rounded-xl px-3 py-3 flex items-center gap-2 hover:border-brand-navy transition peer-checked:border-brand-navy peer-checked:bg-brand-light peer-checked:shadow-sm">
                                <i data-lucide="<?= $p[0] ?>" class="w-4 h-4 text-brand-navy shrink-0"></i>
                                <span class="text-xs font-medium text-brand-text"><?= $p[1] ?></span>
                            </div>
                        </label>
                        <?php $firstPlan = false; endforeach; ?>
                    </div>
                </fieldset>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="budget" class="form-label">งบประมาณเบี้ยประกันที่คาดว่าจะจ่าย (ต่อปี)</label>
                        <select id="budget" name="budget" class="form-input">
                            <option value="">เลือกช่วงงบประมาณ</option>
                            <option>น้อยกว่า 10,000 บาท</option>
                            <option>10,000 - 30,000 บาท</option>
                            <option>30,000 - 50,000 บาท</option>
                            <option>50,000 - 100,000 บาท</option>
                            <option>มากกว่า 100,000 บาท</option>
                            <option>ยังไม่แน่ใจ ต้องการคำแนะนำ</option>
                        </select>
                    </div>
                    <fieldset>
                        <legend class="sr-only">เป้าหมายในการทำประกัน</legend>
                        <span class="form-label">เป้าหมายในการทำประกัน</span>
                        <div class="grid grid-cols-1 gap-2 mt-1.5">
                            <label class="opt-row"><input type="checkbox" name="goals[]" value="คุ้มครองชีวิตและความมั่นคงให้ครอบครัว"> คุ้มครองชีวิตและความมั่นคงให้ครอบครัว</label>
                            <label class="opt-row"><input type="checkbox" name="goals[]" value="ค่ารักษาพยาบาล/สุขภาพ"> ค่ารักษาพยาบาล/สุขภาพ</label>
                            <label class="opt-row"><input type="checkbox" name="goals[]" value="คุ้มครองโรคร้ายแรง"> คุ้มครองโรคร้ายแรง</label>
                            <label class="opt-row"><input type="checkbox" name="goals[]" value="วางแผนเกษียณ/เงินออม"> วางแผนเกษียณ/เงินออม</label>
                            <label class="opt-row"><input type="checkbox" name="goals[]" value="ลดหย่อนภาษี"> ลดหย่อนภาษี</label>
                            <label class="opt-row"><input type="checkbox" name="goals[]" value="อื่นๆ"> อื่นๆ</label>
                        </div>
                    </fieldset>
                </div>
            </div>

            <!-- ══════════ STEP 1: ข้อมูลผู้เอาประกัน ══════════ -->
            <div class="form-step hidden" data-step="2">
                <h2 class="sec-title"><span class="num">2</span> ข้อมูลผู้เอาประกัน</h2>

                <fieldset class="mb-7">
                    <legend class="sr-only">ชื่อ-สกุล</legend>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label for="prefix_th" class="form-label">คำนำหน้า <span class="req">*</span></label>
                            <select id="prefix_th" name="prefix_th" required class="form-input">
                                <option value="">เลือก</option>
                                <option>นาย</option>
                                <option>นาง</option>
                                <option>นางสาว</option>
                                <option>เด็กชาย</option>
                                <option>เด็กหญิง</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="firstname_th" class="form-label">ชื่อ (ภาษาไทย) <span class="req">*</span></label>
                            <input type="text" id="firstname_th" name="firstname_th" required autocomplete="given-name" class="form-input" placeholder="ชื่อจริง">
                        </div>
                        <div>
                            <label for="lastname_th" class="form-label">นามสกุล (ไทย) <span class="req">*</span></label>
                            <input type="text" id="lastname_th" name="lastname_th" required autocomplete="family-name" class="form-input" placeholder="นามสกุล">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="prefix_en" class="form-label">คำนำหน้า (อังกฤษ) <span class="req">*</span></label>
                            <select id="prefix_en" name="prefix_en" required class="form-input">
                                <option value="">เลือก</option>
                                <option>Mr.</option>
                                <option>Mrs.</option>
                                <option>Ms.</option>
                                <option>Miss</option>
                                <option>Master</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label for="firstname_en" class="form-label">ชื่อ (ภาษาอังกฤษ) <span class="req">*</span></label>
                            <input type="text" id="firstname_en" name="firstname_en" required class="form-input" placeholder="First name">
                        </div>
                        <div>
                            <label for="lastname_en" class="form-label">นามสกุล (อังกฤษ) <span class="req">*</span></label>
                            <input type="text" id="lastname_en" name="lastname_en" required class="form-input" placeholder="Last name">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mb-7">
                    <legend class="sr-only">ข้อมูลบัตรประชาชนและส่วนตัว</legend>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="birthdate" class="form-label">วัน เดือน ปี เกิด <span class="req">*</span></label>
                            <input type="date" id="birthdate" name="birthdate" required autocomplete="bday" class="form-input">
                        </div>
                        <div>
                            <label for="id_card" class="form-label">เลขบัตรประชาชน <span class="req">*</span></label>
                            <input type="text" id="id_card" name="id_card" required pattern="[0-9]{13}" maxlength="13" inputmode="numeric" class="form-input" placeholder="เลข 13 หลัก" autocomplete="off">
                        </div>
                        <div>
                            <label for="id_laser" class="form-label">รหัสหลังบัตรประชาชน <span class="req">*</span></label>
                            <input type="text" id="id_laser" name="id_laser" required pattern="[0-9]{2}" maxlength="2" inputmode="numeric" class="form-input" placeholder="2 หลักหลังบัตร" autocomplete="off">
                        </div>
                        <div>
                            <label for="id_expiry" class="form-label">วันที่บัตรหมดอายุ <span class="req">*</span></label>
                            <input type="date" id="id_expiry" name="id_expiry" required class="form-input">
                        </div>
                        <div>
                            <label for="marital_status" class="form-label">สถานภาพ <span class="req">*</span></label>
                            <select id="marital_status" name="marital_status" required class="form-input">
                                <option value="">เลือก</option>
                                <option>โสด</option>
                                <option>สมรส</option>
                                <option>หม้าย</option>
                                <option>หย่าร้าง</option>
                                <option>แยกกันอยู่</option>
                            </select>
                        </div>
                        <div>
                            <label for="nationality" class="form-label">สัญชาติ <span class="req">*</span></label>
                            <select id="nationality" name="nationality" required class="form-input">
                                <option value="">เลือก</option>
                                <option>ไทย</option>
                                <option>อื่นๆ</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <span class="form-label">มีสัญชาติอื่นด้วยไหม</span>
                            <div class="flex gap-5 mt-1.5">
                                <label class="opt-row"><input type="radio" name="other_nationality" value="ไม่มี" checked data-label="มีสัญชาติอื่นด้วยไหม"> ไม่มี</label>
                                <label class="opt-row"><input type="radio" name="other_nationality" value="มี" data-show="other-nationality-box" data-label="มีสัญชาติอื่นด้วยไหม"> มี</label>
                            </div>
                        </div>
                        <div id="other-nationality-box" class="hidden md:col-span-2">
                            <label for="other_nationality_detail" class="form-label">สัญชาติอื่น (ระบุ)</label>
                            <input type="text" id="other_nationality_detail" name="other_nationality_detail" class="form-input" placeholder="เช่น จีน, ลาว">
                        </div>
                        <div>
                            <label for="weight" class="form-label">น้ำหนัก (กก.)</label>
                            <input type="number" id="weight" name="weight" min="1" max="300" step="0.1" inputmode="decimal" class="form-input" placeholder="เช่น 65">
                        </div>
                        <div>
                            <label for="height" class="form-label">ส่วนสูง (ซม.)</label>
                            <input type="number" id="height" name="height" min="50" max="250" inputmode="numeric" class="form-input" placeholder="เช่น 170">
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mb-2">
                    <legend class="sr-only">การทำงานและประกันเดิม</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="workplace" class="form-label">สถานที่ทำงาน</label>
                            <input type="text" id="workplace" name="workplace" autocomplete="organization" class="form-input" placeholder="ชื่อบริษัท / สถานประกอบการ">
                        </div>
                        <div>
                            <label for="policy_count" class="form-label">ถือประกันกี่สัญญา</label>
                            <input type="number" id="policy_count" name="policy_count" min="0" inputmode="numeric" class="form-input" placeholder="จำนวนสัญญา">
                        </div>
                        <div>
                            <label for="policy_companies" class="form-label">บริษัทประกันที่ไหนบ้าง</label>
                            <input type="text" id="policy_companies" name="policy_companies" class="form-input" placeholder="เช่น AIA, ไทยประกันชีวิต, FWD">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="policy_life_sum" class="form-label">ทุนชีวิต (บาท)</label>
                                <input type="number" id="policy_life_sum" name="policy_life_sum" min="0" inputmode="numeric" class="form-input" placeholder="เช่น 500000">
                            </div>
                            <div>
                                <label for="policy_accident_sum" class="form-label">ทุนอุบัติเหตุ (บาท)</label>
                                <input type="number" id="policy_accident_sum" name="policy_accident_sum" min="0" inputmode="numeric" class="form-input" placeholder="เช่น 200000">
                            </div>
                        </div>
                        <div>
                            <label for="workplace_address" class="form-label">ที่อยู่สถานที่ทำงาน</label>
                            <textarea id="workplace_address" name="workplace_address" rows="2" class="form-input resize-y" placeholder="เลขที่ ถนน แขวง/ตำบล เขต/อำเภอ จังหวัด รหัสไปรษณีย์"></textarea>
                        </div>
                        <div>
                            <span class="form-label">ต้องการส่งข้อมูลให้สรรพากรลดหย่อนภาษีไหม</span>
                            <div class="flex gap-5 mt-1.5">
                                <label class="opt-row"><input type="radio" name="tax_deduction" value="ใช่" data-label="ส่งข้อมูลให้สรรพากรลดหย่อนภาษี"> ใช่</label>
                                <label class="opt-row"><input type="radio" name="tax_deduction" value="ไม่ใช่" data-label="ส่งข้อมูลให้สรรพากรลดหย่อนภาษี"> ไม่ใช่</label>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- ══════════ STEP 2: ผู้สมรส + ผู้รับผลประโยชน์ ══════════ -->
            <div class="form-step hidden" data-step="3">
                <h2 class="sec-title"><span class="num">3</span> ข้อมูลผู้สมรส</h2>

                <fieldset class="mb-7">
                    <legend class="sr-only">ข้อมูลผู้สมรส</legend>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="spouse_prefix" class="form-label">คำนำหน้า</label>
                            <select id="spouse_prefix" name="spouse_prefix" class="form-input">
                                <option value="">เลือก</option>
                                <option>นาย</option>
                                <option>นาง</option>
                                <option>นางสาว</option>
                            </select>
                        </div>
                        <div>
                            <span class="form-label">สามี / ภรรยา</span>
                            <div class="flex gap-5 mt-1.5">
                                <label class="opt-row"><input type="radio" name="spouse_relation" value="สามี" data-label="สามี/ภรรยา"> สามี</label>
                                <label class="opt-row"><input type="radio" name="spouse_relation" value="ภรรยา" data-label="สามี/ภรรยา"> ภรรยา</label>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label for="spouse_name" class="form-label">ชื่อ-สกุลผู้สมรส</label>
                            <input type="text" id="spouse_name" name="spouse_name" class="form-input" placeholder="ชื่อ-นามสกุล">
                        </div>
                    </div>
                </fieldset>

                <h2 class="sec-title"><span class="num">3</span> ผู้รับผลประโยชน์</h2>
                <fieldset>
                    <legend class="sr-only">ผู้รับผลประโยชน์</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="beneficiary_name" class="form-label">ชื่อ-สกุล <span class="req">*</span></label>
                            <input type="text" id="beneficiary_name" name="beneficiary_name" required class="form-input" placeholder="ชื่อ-นามสกุลผู้รับผลประโยชน์">
                        </div>
                        <div>
                            <label for="beneficiary_relation" class="form-label">ความสัมพันธ์ <span class="req">*</span></label>
                            <select id="beneficiary_relation" name="beneficiary_relation" required class="form-input">
                                <option value="">เลือก</option>
                                <option>คู่สมรส</option>
                                <option>บิดา/มารดา</option>
                                <option>บุตร</option>
                                <option>ญาติ</option>
                                <option value="อื่นๆ">อื่นๆ</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 hidden" id="beneficiary-relation-box">
                            <label for="beneficiary_relation_detail" class="form-label">ความสัมพันธ์อื่น (ระบุ)</label>
                            <input type="text" id="beneficiary_relation_detail" name="beneficiary_relation_detail" class="form-input" placeholder="เช่น เพื่อน, หุ้นส่วน">
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- ══════════ STEP 3: ข้อมูลติดต่อ + อาชีพ ══════════ -->
            <div class="form-step hidden" data-step="4">
                <h2 class="sec-title"><span class="num">4</span> ข้อมูลติดต่อ</h2>

                <fieldset class="mb-7">
                    <legend class="sr-only">ข้อมูลติดต่อ</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <span class="form-label">ระบุสถานที่ติดต่อ</span>
                            <div class="flex gap-5 mt-1.5">
                                <label class="opt-row"><input type="radio" name="contact_type" value="ที่อยู่ปัจจุบัน" checked data-label="ระบุสถานที่ติดต่อ"> ที่อยู่ปัจจุบัน</label>
                                <label class="opt-row"><input type="radio" name="contact_type" value="สถานที่ทำงาน" data-label="ระบุสถานที่ติดต่อ"> สถานที่ทำงาน</label>
                            </div>
                        </div>
                        <div>
                            <label for="mobile" class="form-label">เบอร์โทรศัพท์มือถือ (รับ SMS จากบริษัท) <span class="req">*</span></label>
                            <input type="tel" id="mobile" name="mobile" required pattern="0[0-9]{8,9}" maxlength="10" inputmode="tel" autocomplete="tel" class="form-input" placeholder="08X-XXX-XXXX">
                        </div>
                        <div class="md:col-span-2">
                            <label for="email" class="form-label">อีเมล (ยืนยันเพื่อรับกรมธรรม์แบบ E-Policy) <span class="req">*</span></label>
                            <input type="email" id="email" name="email" required autocomplete="email" class="form-input" placeholder="you@example.com">
                        </div>
                        <div class="md:col-span-2">
                            <label for="contact_address" class="form-label">ที่อยู่ติดต่อ <span class="req">*</span></label>
                            <textarea id="contact_address" name="contact_address" required rows="3" class="form-input resize-y" placeholder="เลขที่ ถนน แขวง/ตำบล เขต/อำเภอ จังหวัด รหัสไปรษณีย์"></textarea>
                        </div>
                    </div>
                </fieldset>

                <h2 class="sec-title"><span class="num">4</span> อาชีพและรายได้</h2>
                <fieldset>
                    <legend class="sr-only">อาชีพและรายได้</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="occupation" class="form-label">ลักษณะอาชีพ</label>
                            <input type="text" id="occupation" name="occupation" class="form-input" placeholder="เช่น เจ้าของบริษัท, พนักงานบริษัท, ค้าขาย">
                        </div>
                        <div>
                            <label for="position" class="form-label">ตำแหน่ง</label>
                            <input type="text" id="position" name="position" class="form-input" placeholder="เช่น ผู้จัดการ, วิศวกร">
                        </div>
                        <div>
                            <label for="work_detail" class="form-label">ลักษณะงานที่ทำ</label>
                            <textarea id="work_detail" name="work_detail" rows="2" class="form-input resize-y" placeholder="งานหลักที่ทำในแต่ละวัน"></textarea>
                        </div>
                        <div>
                            <label for="business_detail" class="form-label">ลักษณะธุรกิจของบริษัทที่ทำ</label>
                            <textarea id="business_detail" name="business_detail" rows="2" class="form-input resize-y" placeholder="เช่น ธุรกิจนำเข้า-ส่งออก, ค้าปลีก"></textarea>
                        </div>
                        <div>
                            <label for="income" class="form-label">รายได้ต่อปี (บาท)</label>
                            <input type="number" id="income" name="income" min="0" inputmode="numeric" class="form-input" placeholder="เช่น 600000">
                        </div>
                        <div>
                            <span class="form-label">การสูบบุหรี่</span>
                            <div class="flex gap-5 mt-1.5">
                                <label class="opt-row"><input type="radio" name="smoking" value="ไม่สูบ" checked data-label="การสูบบุหรี่"> ไม่สูบ</label>
                                <label class="opt-row"><input type="radio" name="smoking" value="สูบ" data-show="smoking-box" data-label="การสูบบุหรี่"> สูบ</label>
                            </div>
                        </div>
                        <div id="smoking-box" class="hidden">
                            <label for="smoking_detail" class="form-label">สูบ (ระบุจำนวน/วัน)</label>
                            <input type="text" id="smoking_detail" name="smoking_detail" class="form-input" placeholder="เช่น 10 มวน/วัน">
                        </div>
                        <div>
                            <span class="form-label">การดื่มแอลกอฮอล์เป็นประจำหรือไม่</span>
                            <div class="flex gap-5 mt-1.5">
                                <label class="opt-row"><input type="radio" name="alcohol" value="ไม่ดื่ม" checked data-label="ดื่มแอลกอฮอล์เป็นประจำ"> ไม่ดื่ม</label>
                                <label class="opt-row"><input type="radio" name="alcohol" value="ดื่ม" data-label="ดื่มแอลกอฮอล์เป็นประจำ"> ดื่ม</label>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <span class="form-label">เคยถูกปฏิเสธการรับประกัน / บริษัทไม่รับประกัน / ต้องเพิ่มเบี้ยหรือไม่</span>
                            <div class="flex gap-5 mt-1.5">
                                <label class="opt-row"><input type="radio" name="rejected" value="ไม่เคย" checked data-label="เคยถูกปฏิเสธการรับประกัน/เพิ่มเบี้ย"> ไม่เคย</label>
                                <label class="opt-row"><input type="radio" name="rejected" value="เคย" data-show="rejected-box" data-label="เคยถูกปฏิเสธการรับประกัน/เพิ่มเบี้ย"> เคย</label>
                            </div>
                        </div>
                        <div id="rejected-box" class="hidden md:col-span-2">
                            <label for="rejected_detail" class="form-label">รายละเอียด (ระบุ)</label>
                            <input type="text" id="rejected_detail" name="rejected_detail" class="form-input" placeholder="บริษัท / เหตุผล / ปีที่ถูกปฏิเสธ">
                        </div>
                        <div>
                            <span class="form-label">เคยเปลี่ยนชื่อ/นามสกุลหรือไม่</span>
                            <div class="flex gap-5 mt-1.5">
                                <label class="opt-row"><input type="radio" name="name_changed" value="ไม่เคย" checked data-label="เคยเปลี่ยนชื่อ/นามสกุล"> ไม่เคย</label>
                                <label class="opt-row"><input type="radio" name="name_changed" value="เคย" data-show="old-name-box" data-label="เคยเปลี่ยนชื่อ/นามสกุล"> เคย</label>
                            </div>
                        </div>
                        <div id="old-name-box" class="hidden">
                            <label for="old_name" class="form-label">ชื่อ-นามสกุลเดิม</label>
                            <input type="text" id="old_name" name="old_name" class="form-input" placeholder="ชื่อ-นามสกุลเดิม">
                        </div>
                        <div>
                            <label for="nickname" class="form-label">ชื่อเล่น / ชื่อที่เรียกอื่นๆ</label>
                            <input type="text" id="nickname" name="nickname" autocomplete="nickname" class="form-input" placeholder="เช่น ป๊อบ">
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- ══════════ STEP 4: ประวัติสุขภาพ ══════════ -->
            <div class="form-step hidden" data-step="5">
                <h2 class="sec-title"><span class="num">5</span> ประวัติสุขภาพ (ในช่วง 5 ปี)</h2>

                <fieldset class="mb-7">
                    <legend class="font-medium text-sm text-brand-text mb-3">ท่านเคยตรวจร่างกายอะไรบ้าง</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label class="opt-row"><input type="checkbox" name="health_checks[]" value="ตรวจเลือด"> ตรวจเลือด</label>
                        <label class="opt-row"><input type="checkbox" name="health_checks[]" value="ตรวจปัสสาวะ"> ตรวจปัสสาวะ</label>
                        <label class="opt-row"><input type="checkbox" name="health_checks[]" value="X-Ray ปอด"> X-Ray ปอด</label>
                        <label class="opt-row"><input type="checkbox" name="health_checks[]" value="คลื่นไฟฟ้าหัวใจ"> คลื่นไฟฟ้าหัวใจ (EKG)</label>
                        <label class="opt-row"><input type="checkbox" name="health_checks[]" value="แมมโมแกรม"> แมมโมแกรม</label>
                        <label class="opt-row"><input type="checkbox" name="health_checks[]" value="ส่องกล้อง"> ส่องกล้อง</label>
                        <label class="opt-row"><input type="checkbox" name="health_checks[]" value="ชิ้นเนื้อ"> ชิ้นเนื้อ (Biopsy)</label>
                        <label class="opt-row"><input type="checkbox" name="health_checks[]" value="ตรวจด้วยเครื่องมือพิเศษอื่นๆ"> ตรวจด้วยเครื่องมือพิเศษอื่นๆ</label>
                    </div>
                </fieldset>

                <fieldset class="mb-7">
                    <legend class="sr-only">สาเหตุที่ไปตรวจ</legend>
                    <span class="form-label">ไปตรวจด้วยสาเหตุใด</span>
                    <div class="flex flex-wrap gap-5 mt-1.5 mb-3">
                        <label class="opt-row"><input type="radio" name="health_reason" value="ตรวจสุขภาพประจำปี" checked data-label="ไปตรวจด้วยสาเหตุใด"> ตรวจสุขภาพประจำปี</label>
                        <label class="opt-row"><input type="radio" name="health_reason" value="หาอาการ" data-label="ไปตรวจด้วยสาเหตุใด"> หาอาการ</label>
                        <label class="opt-row"><input type="radio" name="health_reason" value="ติดตามโรค" data-label="ไปตรวจด้วยสาเหตุใด"> ติดตามโรค</label>
                    </div>
                    <div id="health-reason-box" class="hidden">
                        <label for="health_reason_detail" class="form-label">สาเหตุอื่น (ระบุ)</label>
                        <input type="text" id="health_reason_detail" name="health_reason_detail" class="form-input" placeholder="รายละเอียดสาเหตุที่ไปตรวจ">
                    </div>
                </fieldset>

                <fieldset>
                    <legend class="sr-only">ประวัติการรักษา</legend>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="hospital_stays" class="form-label">เคยเข้าพักรักษาที่โรงพยาบาลกี่ครั้ง</label>
                            <input type="number" id="hospital_stays" name="hospital_stays" min="0" inputmode="numeric" class="form-input" placeholder="จำนวนครั้ง">
                        </div>
                        <div>
                            <label for="diseases" class="form-label">โรคประจำตัว หรือโรคที่รักษาอยู่ / เคยรักษา / เคยเป็น</label>
                            <textarea id="diseases" name="diseases" rows="3" class="form-input resize-y" placeholder="เช่น ความดันโลหิตสูง, เบาหวาน, ภูมิแพ้ ... (ถ้าไม่มี กรุณาระบุ ไม่มี)"></textarea>
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- ══════════ STEP 5: ตรวจสอบและส่ง ══════════ -->
            <div class="form-step hidden" data-step="6">
                <h2 class="sec-title"><span class="num">6</span> ตรวจสอบข้อมูลก่อนส่ง</h2>

                <div class="bg-brand-light/60 rounded-2xl p-5 mb-6">
                    <h3 class="font-bold text-brand-navy mb-2 flex items-center gap-2">
                        <i data-lucide="clipboard-list" class="w-5 h-5"></i> สรุปข้อมูลที่กรอก
                    </h3>
                    <p class="text-xs text-brand-gray mb-4">กรุณาตรวจสอบความถูกต้อง หากต้องการแก้ไข กดปุ่ม "ย้อนกลับ" เพื่อแก้ไขข้อมูล</p>
                    <div id="review-body"></div>
                </div>

                <div class="bg-brand-light rounded-2xl p-5 border border-brand-navy/10">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="consent" required class="mt-1 w-5 h-5 accent-brand-navy shrink-0">
                        <span class="text-sm text-brand-text leading-relaxed">
                            ข้าพเจ้ายินยอมให้เก็บรวบรวม ใช้ และเปิดเผยข้อมูลส่วนบุคคลตามวัตถุประสงค์ในการจัดทำประกัน
                            และรับทราบ<a href="/privacy.php" target="_blank" class="text-brand-navy font-bold underline hover:no-underline">นโยบายความเป็นส่วนตัว</a>แล้ว
                            <span class="req">*</span>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Nav buttons -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-100">
                <button type="button" id="prev-btn" class="hidden inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-brand-navy text-brand-text font-bold px-6 py-3 rounded-xl transition text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> ย้อนกลับ
                </button>
                <button type="button" id="next-btn" class="inline-flex items-center gap-2 bg-brand-navy hover:bg-brand-navyHover text-white font-bold px-8 py-3 rounded-xl transition text-sm shadow-md">
                    ถัดไป <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
                <div id="submit-btn-wrap" class="hidden ml-auto">
                    <button type="submit" class="inline-flex items-center gap-2 bg-brand-navy hover:bg-brand-navyHover text-white font-bold px-8 py-3 rounded-xl transition text-sm shadow-md">
                        <i data-lucide="send" class="w-4 h-4"></i> ส่งข้อมูล
                    </button>
                </div>
            </div>
        </form>

        <?php endif; ?>
    </div>
</section>

<script>
(function () {
    var form = document.getElementById('insurance-form');
    if (!form) return;

    var steps = document.querySelectorAll('.form-step');
    var dots = document.querySelectorAll('.step-dot');
    var lines = document.querySelectorAll('.step-line');
    var total = steps.length;
    var current = 1;

    function showStep(n) {
        current = n;
        for (var i = 0; i < total; i++) {
            steps[i].classList.toggle('hidden', i !== n - 1);
            dots[i].classList.toggle('active', i === n - 1);
            dots[i].classList.toggle('done', i < n - 1);
            if (lines[i]) lines[i].classList.toggle('done', i < n - 1);
        }
        document.getElementById('prev-btn').classList.toggle('hidden', n === 1);
        document.getElementById('next-btn').classList.toggle('hidden', n === total);
        document.getElementById('submit-btn-wrap').classList.toggle('hidden', n !== total);
        if (n === total) buildReview();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(n) {
        var fields = steps[n - 1].querySelectorAll('input, select, textarea');
        for (var i = 0; i < fields.length; i++) {
            var f = fields[i];
            if (f.disabled || f.type === 'hidden') continue;
            if (!f.checkValidity()) {
                f.reportValidity();
                return false;
            }
        }
        return true;
    }

    document.getElementById('next-btn').addEventListener('click', function () {
        if (validateStep(current)) showStep(current + 1);
    });
    document.getElementById('prev-btn').addEventListener('click', function () {
        showStep(current - 1);
    });

    // ─── Plan cards: คลิกการ์ดแล้ว toggle เอง (กัน browser ที่ label activation ไม่ทำงาน) ───
    document.querySelectorAll('label.relative input[type="checkbox"].peer').forEach(function (cb) {
        var lbl = cb.closest('label');
        if (!lbl) return;
        lbl.addEventListener('click', function (e) {
            if (e.target === cb) return; // คลิกที่ checkbox โดยตรง ปล่อย default
            e.preventDefault();
            cb.checked = !cb.checked;
            cb.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });

    // Conditional reveal: radio ที่มี data-show
    document.querySelectorAll('input[data-show]').forEach(function (r) {
        r.addEventListener('change', function () {
            var target = document.getElementById(r.getAttribute('data-show'));
            if (target) target.classList.toggle('hidden', !r.checked);
        });
    });
    // กรณี beneficiary_relation = อื่นๆ
    var benRel = document.getElementById('beneficiary_relation');
    if (benRel) benRel.addEventListener('change', function () {
        var box = document.getElementById('beneficiary-relation-box');
        if (box) box.classList.toggle('hidden', benRel.value !== 'อื่นๆ');
    });

    // ─── Review builder ───
    function labelFor(el) {
        var txt = '';
        if (el.getAttribute('data-label')) {
            txt = el.getAttribute('data-label');
        } else if (el.type === 'radio' || el.type === 'checkbox') {
            var fs = el.closest('fieldset');
            if (fs && fs.querySelector('legend')) {
                txt = fs.querySelector('legend').textContent.trim().replace(/\s+/g, ' ');
            }
        }
        if (!txt) {
            var l = document.querySelector('label[for="' + el.id + '"]');
            txt = l ? l.textContent.trim().replace(/\s+/g, ' ') : el.name;
        }
        // ตัดเครื่องหมาย * และช่องว่างท้าย
        return txt.replace(/\s*\*\s*$/, '').trim();
    }

    function esc(s) {
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function buildReview() {
        var html = '';
        var rendered = {};
        var checkboxGroups = {};

        form.querySelectorAll('input, select, textarea').forEach(function (el) {
            if (!el.name) return;
            if (el.type === 'hidden' || el.type === 'submit' || el.type === 'button') return;
            if (el.name === 'website' || el.name === 'consent') return;
            if (el.type === 'radio') {
                if (!el.checked || rendered[el.name]) return;
                if (el.value === '') return;
                rendered[el.name] = true;
                html += '<div class="review-row"><div class="review-label">' + esc(labelFor(el)) + '</div><div class="review-value">' + esc(el.value) + '</div></div>';
                return;
            }
            if (el.type === 'checkbox') {
                if (!el.checked) return;
                var key = labelFor(el);
                if (!checkboxGroups[key]) checkboxGroups[key] = [];
                checkboxGroups[key].push(el.value);
                return;
            }
            var val = el.value.trim();
            if (val === '') return;
            html += '<div class="review-row"><div class="review-label">' + esc(labelFor(el)) + '</div><div class="review-value">' + esc(val) + '</div></div>';
        });

        Object.keys(checkboxGroups).forEach(function (key) {
            html += '<div class="review-row"><div class="review-label">' + esc(key) + '</div><div class="review-value">' + esc(checkboxGroups[key].join(', ')) + '</div></div>';
        });

        document.getElementById('review-body').innerHTML = html || '<p class="text-sm text-brand-gray">ยังไม่มีข้อมูลที่กรอก</p>';
    }
})();
</script>

<?php
// Dual-mode include: ใช้ได้ทั้งโหมดโฟลเดอร์ย่อย (prakanhub.com/form/) และโหมด subdomain (form.prakanhub.com)
if (file_exists(__DIR__ . '/../includes/footer.php')) {
    include __DIR__ . '/../includes/footer.php';
} else {
    include __DIR__ . '/includes/footer.php';
}
?>

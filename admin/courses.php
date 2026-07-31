<?php
require_once __DIR__ . '/includes/auth.php';
admin_guard();

$db = admin_db();
$adminPageTitle = 'คอร์สเรียนสมาชิก';
$adminMenu = 'courses';

// ─── Import จาก courses.json ───
if (isset($_GET['import'])) {
    $jsonPath = __DIR__ . '/../member/data/courses.json';
    $count = 0;
    if (file_exists($jsonPath)) {
        $data = json_decode(file_get_contents($jsonPath), true);
        if (is_array($data)) {
            $db->query('TRUNCATE TABLE courses');
            $ins = $db->prepare('INSERT INTO courses (title, category, description, thumb, sections, sort_order, is_active) VALUES (?,?,?,?,?,?,1)');
            foreach ($data as $c) {
                $ins->execute([
                    $c['title'] ?? '',
                    $c['category'] ?? '',
                    $c['desc'] ?? '',
                    $c['thumb'] ?? '',
                    json_encode($c['sections'] ?? [], JSON_UNESCAPED_UNICODE),
                    (int)($c['id'] ?? 0),
                ]);
                $count++;
            }
        }
    }
    admin_flash($count > 0 ? "Import เรียบร้อย: $count คอร์ส" : 'ไม่พบไฟล์ courses.json', $count > 0 ? 'success' : 'error');
    header('Location: courses.php');
    exit;
}

// ─── CRUD ───
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            if ($title === '') throw new Exception('กรุณากรอกชื่อคอร์ส');
            // sections จาก JS builder: sections[]=name&lesson_title[0][]=...  → สร้าง JSON
            $sections = [];
            $names = $_POST['section_name'] ?? [];
            foreach ($names as $si => $sname) {
                if (trim((string)$sname) === '') continue;
                $lessons = [];
                $ltitles = $_POST['lesson_title'][$si] ?? [];
                foreach ($ltitles as $li => $ltitle) {
                    if (trim((string)$ltitle) === '') continue;
                    $lessons[] = [
                        'title' => trim((string)$ltitle),
                        'video' => trim($_POST['lesson_video'][$si][$li] ?? ''),
                        'duration' => trim($_POST['lesson_duration'][$si][$li] ?? '0:00'),
                    ];
                }
                $sections[] = ['name' => trim((string)$sname), 'lessons' => $lessons];
            }
            if (empty($sections)) throw new Exception('กรุณาเพิ่มอย่างน้อย 1 บทเรียน');
            $data = [
                $title,
                trim($_POST['category'] ?? ''),
                trim($_POST['description'] ?? ''),
                trim($_POST['thumb'] ?? ''),
                json_encode($sections, JSON_UNESCAPED_UNICODE),
                (int)($_POST['sort_order'] ?? 0),
                !empty($_POST['is_active']) ? 1 : 0,
            ];
            if ($id > 0) {
                $data[] = $id;
                $db->prepare('UPDATE courses SET title=?, category=?, description=?, thumb=?, sections=?, sort_order=?, is_active=? WHERE id=?')->execute($data);
                admin_flash('บันทึกคอร์สเรียบร้อย');
            } else {
                $db->prepare('INSERT INTO courses (title, category, description, thumb, sections, sort_order, is_active) VALUES (?,?,?,?,?,?,?)')->execute($data);
                admin_flash('เพิ่มคอร์สเรียบร้อย');
            }
        } elseif ($action === 'delete') {
            $db->prepare('DELETE FROM courses WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('ลบคอร์สเรียบร้อย');
        } elseif ($action === 'toggle') {
            $db->prepare('UPDATE courses SET is_active = 1 - is_active WHERE id=?')->execute([(int)$_POST['id']]);
            admin_flash('อัปเดตสถานะเรียบร้อย');
        }
    } catch (Exception $e) {
        admin_flash($e->getMessage(), 'error');
    }
    header('Location: courses.php');
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $db->prepare('SELECT * FROM courses WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
    if (!$edit) { header('Location: courses.php'); exit; }
    $edit['sections'] = json_decode((string)$edit['sections'], true) ?: [];
}

$rows = $db->query('SELECT id, title, category, thumb, is_active, sort_order FROM courses ORDER BY sort_order, id')->fetchAll();
$adminDataTable = true;
include __DIR__ . '/includes/header.php';
?>

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h6 class="page-title">คอร์สเรียนสมาชิก (<?= count($rows) ?>)</h6>
            <ol class="breadcrumb m-0"><li class="breadcrumb-item"><a href="index.php">Admin</a></li><li class="breadcrumb-item active">คอร์สเรียน</li></ol>
        </div>
        <div class="col-md-4">
            <div class="float-end">
                <a href="courses.php?import=1" class="btn btn-outline-info waves-effect me-1" onclick="return confirm('Import จาก courses.json (ล้างข้อมูลเดิม)?')"><i class="ti ti-download me-1"></i> Import</a>
                <a href="courses.php?new=1" class="btn btn-primary waves-effect"><i class="ti ti-plus me-1"></i> เพิ่มคอร์ส</a>
            </div>
        </div>
    </div>
</div>

<?php if ($edit || isset($_GET['new'])): ?>
<?php $e = $edit ?: ['id' => 0, 'title' => '', 'category' => '', 'description' => '', 'thumb' => '', 'sections' => [], 'sort_order' => 0, 'is_active' => 1]; ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title"><?= $edit ? 'แก้ไขคอร์ส: ' . admin_e($edit['title']) : 'เพิ่มคอร์สใหม่' ?></h4>
                <form method="post" id="courseForm">
                    <input type="hidden" name="action" value="save">
                    <input type="hidden" name="id" value="<?= (int)$e['id'] ?>">
                    <div class="row">
                        <div class="col-md-8 mb-3"><label class="form-label">ชื่อคอร์ส <span class="required-flag">*</span></label><input type="text" class="form-control" name="title" value="<?= admin_e($e['title']) ?>" required></div>
                        <div class="col-md-4 mb-3"><label class="form-label">หมวดหมู่</label><input type="text" class="form-control" name="category" value="<?= admin_e($e['category']) ?>" placeholder="ความรู้พื้นฐาน"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">คำอธิบาย</label><input type="text" class="form-control" name="description" value="<?= admin_e($e['description']) ?>"></div>
                        <div class="col-md-6 mb-3"><label class="form-label">รูปปก (YouTube thumbnail URL)</label><input type="text" class="form-control" name="thumb" value="<?= admin_e($e['thumb']) ?>" placeholder="https://img.youtube.com/vi/XXXX/hqdefault.jpg"></div>
                        <div class="col-md-4 mb-3"><label class="form-label">เรียงลำดับ</label><input type="number" class="form-control" name="sort_order" value="<?= (int)$e['sort_order'] ?>"></div>
                        <div class="col-md-4 mb-3"><div class="form-check form-switch mt-4"><input type="checkbox" class="form-check-input" name="is_active" id="c_active" value="1" <?= $e['is_active'] ? 'checked' : '' ?>><label class="form-check-label" for="c_active">แสดงผล</label></div></div>
                    </div>

                    <hr>
                    <h5 class="mb-3">บทเรียน (Sections & Lessons)</h5>
                    <div id="sections-wrap"></div>
                    <button type="button" class="btn btn-outline-success waves-effect mt-2" onclick="addSection()"><i class="ti ti-plus me-1"></i> เพิ่มบท (Section)</button>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="ti ti-check me-1"></i> บันทึกคอร์ส</button>
                        <a href="courses.php" class="btn btn-secondary waves-effect">ย้อนกลับ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// ═══ Sections builder ═══
var initialSections = <?= json_encode($e['sections'], JSON_UNESCAPED_UNICODE) ?>;
var secCounter = 0;

function addSection(section) {
    section = section || { name: '', lessons: [] };
    var wrap = document.getElementById('sections-wrap');
    var sec = document.createElement('div');
    sec.className = 'border rounded-3 p-3 mb-3 bg-light-subtle';
    sec.dataset.sec = secCounter;
    var secHtml = '<div class="d-flex align-items-center gap-2 mb-2">'
        + '<input type="text" class="form-control" name="section_name[]" placeholder="ชื่อบท เช่น บทที่ 1 — แนะนำคอร์ส" value="' + esc(section.name) + '">'
        + '<button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest(\'.border\').remove()"><i class="ti ti-trash"></i></button>'
        + '</div><div class="lessons"></div>'
        + '<button type="button" class="btn btn-sm btn-outline-primary" onclick="addLesson(this)"><i class="ti ti-plus me-1"></i> เพิ่มวิดีโอ</button>';
    sec.innerHTML = secHtml;
    wrap.appendChild(sec);
    (section.lessons || []).forEach(function (l) {
        var btn = sec.querySelector('.lessons').parentElement.querySelector('button:last-child');
        addLesson(sec.querySelector('button:last-child'), l);
    });
    secCounter++;
}

function addLesson(btn, lesson) {
    lesson = lesson || { title: '', video: '', duration: '' };
    var sec = btn.closest('.border');
    var idx = Array.prototype.indexOf.call(sec.parentElement.children, sec);
    var lessonsBox = sec.querySelector('.lessons');
    var row = document.createElement('div');
    row.className = 'row g-2 mb-2 lesson-row';
    row.innerHTML = '<div class="col-md-5"><input type="text" class="form-control form-control-sm" name="lesson_title[' + idx + '][]" placeholder="ชื่อวิดีโอ" value="' + esc(lesson.title) + '"></div>'
        + '<div class="col-md-4"><input type="text" class="form-control form-control-sm" name="lesson_video[' + idx + '][]" placeholder="YouTube ID (เช่น aqz-KE-bpKQ)" value="' + esc(lesson.video) + '"></div>'
        + '<div class="col-md-2"><input type="text" class="form-control form-control-sm" name="lesson_duration[' + idx + '][]" placeholder="8:12" value="' + esc(lesson.duration) + '"></div>'
        + '<div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="this.closest(\'.lesson-row\').remove()"><i class="ti ti-x"></i></button></div>';
    lessonsBox.appendChild(row);
}

function esc(s) {
    return String(s || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

// แก้ index ของ lesson rows ใหม่ก่อน submit (section index เปลี่ยนได้หลังลบ)
document.getElementById('courseForm').addEventListener('submit', function () {
    var wrap = document.getElementById('sections-wrap');
    Array.prototype.forEach.call(wrap.children, function (sec, si) {
        sec.querySelectorAll('.lesson-row').forEach(function (row) {
            ['lesson_title', 'lesson_video', 'lesson_duration'].forEach(function (n) {
                var inp = row.querySelector('input[name^="' + n + '"]');
                inp.name = n + '[' + si + '][]';
            });
        });
    });
});

if (initialSections.length) {
    initialSections.forEach(addSection);
} else {
    addSection();
}
</script>

<?php else: ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive nowrap datatable" style="width:100%">
                        <thead><tr><th>#</th><th>รูป</th><th>ชื่อคอร์ส</th><th>หมวด</th><th>แสดง</th><th style="width:140px">จัดการ</th></tr></thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= (int)$r['sort_order'] ?></td>
                                <td><?php if ($r['thumb']): ?><img src="<?= admin_e($r['thumb']) ?>" class="thumb-xs" onerror="this.style.display='none'"><?php endif; ?></td>
                                <td class="fw-semibold"><?= admin_e($r['title']) ?></td>
                                <td><span class="badge bg-soft-info text-info"><?= admin_e($r['category']) ?></span></td>
                                <td>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                                        <button type="submit" class="btn btn-sm <?= $r['is_active'] ? 'btn-success' : 'btn-secondary' ?>"><?= $r['is_active'] ? 'แสดง' : 'ซ่อน' ?></button>
                                    </form>
                                </td>
                                <td>
                                    <a href="courses.php?edit=<?= (int)$r['id'] ?>" class="btn btn-sm btn-soft-primary"><i class="ti ti-pencil"></i></a>
                                    <form method="post" class="d-inline" onsubmit="return confirm('ลบคอร์สนี้?')">
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
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>

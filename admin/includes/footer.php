<?php
/**
 * Admin Layout — Footer (JS + close tags)
 * ตัวแปร: $adminDataTable = true (เปิด DataTable), $adminTinyMCE = true (เปิด TinyMCE)
 */
?>
            </div><!-- container-fluid -->
        </div><!-- page-content -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">© <?= date('Y') ?> Prakanhub Admin — ประกันจริงใจ by ปกป้อง</div>
                    <div class="col-sm-6"><div class="text-sm-end d-none d-sm-block">Admin System v1.0</div></div>
                </div>
            </div>
        </footer>
    </div><!-- main-content -->
</div><!-- layout-wrapper -->

<!-- JS -->
<script src="assets/libs/jquery/jquery.min.js"></script>
<script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/libs/metismenu/metisMenu.min.js"></script>
<script src="assets/libs/simplebar/simplebar.min.js"></script>
<script src="assets/libs/node-waves/waves.min.js"></script>
<script src="assets/js/app.js"></script>

<script>
    // ═══ CSRF token — inject ลงทุกฟอร์ม POST อัตโนมัติ ═══
    (function () {
        var token = <?= json_encode(csrf_token()) ?>;
        document.querySelectorAll('form[method="post"]').forEach(function (f) {
            var inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'csrf';
            inp.value = token;
            f.appendChild(inp);
        });
    })();

    // ═══ ปุ่มลบแบบ 2 ขั้นตอน (ไม่มี popup): คลิก 1 = "แน่ใจ?" คลิก 2 = ลบจริง ═══
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-del');
        if (!btn) return;
        if (btn.dataset.arm !== '1') {
            e.preventDefault();
            e.stopPropagation();
            btn.dataset.arm = '1';
            var old = btn.innerHTML;
            btn.innerHTML = 'แน่ใจ?';
            btn.classList.remove('btn-soft-danger');
            btn.classList.add('btn-danger');
            setTimeout(function () {
                btn.dataset.arm = '0';
                btn.innerHTML = old;
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-soft-danger');
            }, 3000);
        }
    });
</script>

<?php if (!empty($adminDataTable)): ?>
<link rel="stylesheet" href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css">
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {
    $('.datatable').DataTable({
        language: {
            search: "ค้นหา:",
            lengthMenu: "แสดง _MENU_ รายการ",
            info: "แสดง _START_ - _END_ จาก _TOTAL_ รายการ",
            infoEmpty: "ไม่มีข้อมูล",
            infoFiltered: "(จากทั้งหมด _MAX_ รายการ)",
            zeroRecords: "ไม่พบข้อมูลที่ค้นหา",
            paginate: { previous: "ก่อนหน้า", next: "ถัดไป" }
        },
        order: [[0, 'desc']]
    });
});
</script>
<?php endif; ?>

<?php if (!empty($adminTinyMCE)): ?>
<script src="assets/libs/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '.tinymce',
    language: 'th',
    height: 450,
    menubar: false,
    plugins: 'lists link image table code autolink',
    toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link image table | code',
    branding: false,
    convert_urls: false
});
</script>
<?php endif; ?>

</body>
</html>

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

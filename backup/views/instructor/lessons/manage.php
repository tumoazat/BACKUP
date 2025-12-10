<?php
$tiêu_đề = "Quản lý bài học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Quản lý bài học</h1>
            <p>Trang quản lý bài học đang được phát triển.</p>
            <a href="index.php?controller=instructor&action=my_courses" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

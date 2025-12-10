<?php
$tiêu_đề = "Dashboard quản trị - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Dashboard quản trị</h1>
            
            <div class="stats">
                <div class="stat-card">
                    <h3><?php echo $tổng_người_dùng; ?></h3>
                    <p>Tổng người dùng</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $tổng_giảng_viên; ?></h3>
                    <p>Giảng viên</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $tổng_học_viên; ?></h3>
                    <p>Học viên</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $tổng_khóa_học; ?></h3>
                    <p>Khóa học</p>
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <h2>Quản lý nhanh</h2>
                <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                    <a href="index.php?controller=admin&action=manage_users" class="btn btn-primary">Quản lý người dùng</a>
                    <a href="index.php?controller=admin&action=list_categories" class="btn btn-primary">Quản lý danh mục</a>
                    <a href="index.php?controller=admin&action=statistics" class="btn btn-primary">Xem thống kê</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

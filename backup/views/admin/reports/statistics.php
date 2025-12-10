<?php
$tiêu_đề = "Thống kê - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Thống kê hệ thống</h1>
            
            <div class="stats">
                <div class="stat-card">
                    <h3><?php echo count($danh_sách_người_dùng); ?></h3>
                    <p>Tổng người dùng</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo count($danh_sách_khóa_học); ?></h3>
                    <p>Tổng khóa học</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo count($danh_sách_danh_mục); ?></h3>
                    <p>Danh mục</p>
                </div>
            </div>
            
            <h2>Danh sách khóa học</h2>
            <?php if (!empty($danh_sách_khóa_học)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tên khóa học</th>
                            <th>Giảng viên</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Ngày tạo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_khóa_học as $khóa_học): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($khóa_học['title']); ?></td>
                                <td><?php echo htmlspecialchars($khóa_học['tên_giảng_viên']); ?></td>
                                <td><?php echo htmlspecialchars($khóa_học['tên_danh_mục']); ?></td>
                                <td><?php echo number_format($khóa_học['price'], 0, ',', '.'); ?> VNĐ</td>
                                <td><?php echo date('d/m/Y', strtotime($khóa_học['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Chưa có khóa học nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

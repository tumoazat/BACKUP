<?php
$tiêu_đề = "Dashboard học viên - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Dashboard học viên</h1>
            <p>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong>!</p>
            
            <div class="stats">
                <div class="stat-card">
                    <h3><?php echo count($danh_sách_khóa_học); ?></h3>
                    <p>Khóa học đã đăng ký</p>
                </div>
            </div>
            
            <h2>Khóa học của tôi</h2>
            <?php if (!empty($danh_sách_khóa_học)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tên khóa học</th>
                            <th>Giảng viên</th>
                            <th>Tiến độ</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_khóa_học as $khóa_học): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($khóa_học['title']); ?></td>
                                <td><?php echo htmlspecialchars($khóa_học['tên_giảng_viên']); ?></td>
                                <td><?php echo $khóa_học['progress']; ?>%</td>
                                <td><?php echo htmlspecialchars($khóa_học['status']); ?></td>
                                <td>
                                    <a href="index.php?controller=student&action=course_progress&course_id=<?php echo $khóa_học['course_id']; ?>" class="btn btn-small">Xem chi tiết</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Bạn chưa đăng ký khóa học nào.</p>
                <a href="index.php?controller=course&action=index" class="btn btn-primary">Tìm khóa học</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

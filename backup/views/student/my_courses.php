<?php
$tiêu_đề = "Khóa học của tôi - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Khóa học của tôi</h1>
            
            <?php if (!empty($danh_sách_khóa_học)): ?>
                <div class="course-grid">
                    <?php foreach ($danh_sách_khóa_học as $khóa_học): ?>
                        <div class="course-card">
                            <div class="course-image">
                                <?php if (!empty($khóa_học['image'])): ?>
                                    <img src="assets/images/<?php echo htmlspecialchars($khóa_học['image']); ?>" alt="<?php echo htmlspecialchars($khóa_học['title']); ?>">
                                <?php else: ?>
                                    <div class="course-placeholder">Không có ảnh</div>
                                <?php endif; ?>
                            </div>
                            <div class="course-info">
                                <h3><?php echo htmlspecialchars($khóa_học['title']); ?></h3>
                                <p>Giảng viên: <?php echo htmlspecialchars($khóa_học['tên_giảng_viên']); ?></p>
                                <p>Tiến độ: <?php echo $khóa_học['progress']; ?>%</p>
                                <p>Trạng thái: <?php echo htmlspecialchars($khóa_học['status']); ?></p>
                                <a href="index.php?controller=student&action=course_progress&course_id=<?php echo $khóa_học['course_id']; ?>" class="btn btn-small">Xem chi tiết</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Bạn chưa đăng ký khóa học nào.</p>
                <a href="index.php?controller=course&action=index" class="btn btn-primary">Tìm khóa học</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

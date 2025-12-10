<?php
$tiêu_đề = "Khóa học của tôi - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Khóa học của tôi</h1>
            
            <a href="index.php?controller=instructor&action=create_course" class="btn btn-success" style="margin-bottom: 1rem;">Tạo khóa học mới</a>
            
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
                                <p>Danh mục: <?php echo htmlspecialchars($khóa_học['tên_danh_mục']); ?></p>
                                <p>Giá: <?php echo number_format($khóa_học['price'], 0, ',', '.'); ?> VNĐ</p>
                                <p>Trình độ: <?php echo htmlspecialchars($khóa_học['level']); ?></p>
                                <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                                    <a href="index.php?controller=instructor&action=manage_course&id=<?php echo $khóa_học['id']; ?>" class="btn btn-small">Quản lý</a>
                                    <a href="index.php?controller=instructor&action=edit_course&id=<?php echo $khóa_học['id']; ?>" class="btn btn-small">Sửa</a>
                                    <a href="index.php?controller=instructor&action=delete_course&id=<?php echo $khóa_học['id']; ?>" 
                                       onclick="return xácNhậnXóa('Bạn có chắc muốn xóa khóa học này?')" 
                                       class="btn btn-small btn-danger">Xóa</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Bạn chưa có khóa học nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

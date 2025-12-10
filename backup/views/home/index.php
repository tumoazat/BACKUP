<?php
$tiêu_đề = "Trang chủ - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <section class="hero">
        <h1>Chào mừng đến với Hệ thống Khóa học Online</h1>
        <p>Học tập mọi lúc, mọi nơi với hàng trăm khóa học chất lượng cao</p>
        <div class="hero-buttons">
            <?php if (!isset($_SESSION['đã_đăng_nhập'])): ?>
                <a href="index.php?controller=auth&action=register" class="btn btn-primary">Đăng ký ngay</a>
                <a href="index.php?controller=course&action=index" class="btn btn-secondary">Xem khóa học</a>
            <?php else: ?>
                <a href="index.php?controller=course&action=index" class="btn btn-primary">Xem khóa học</a>
            <?php endif; ?>
        </div>
    </section>
    
    <section class="categories">
        <h2>Danh mục khóa học</h2>
        <div class="category-grid">
            <?php if (!empty($danh_sách_danh_mục)): ?>
                <?php foreach ($danh_sách_danh_mục as $danh_mục): ?>
                    <div class="category-card">
                        <h3><?php echo htmlspecialchars($danh_mục['name']); ?></h3>
                        <p><?php echo htmlspecialchars($danh_mục['description']); ?></p>
                        <a href="index.php?controller=course&action=index&category_id=<?php echo $danh_mục['id']; ?>" class="btn btn-small">Xem khóa học</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Chưa có danh mục nào.</p>
            <?php endif; ?>
        </div>
    </section>
    
    <section class="courses">
        <h2>Khóa học nổi bật</h2>
        <div class="course-grid">
            <?php if (!empty($danh_sách_khóa_học)): ?>
                <?php $đếm = 0; ?>
                <?php foreach ($danh_sách_khóa_học as $khóa_học): ?>
                    <?php if ($đếm >= 6) break; ?>
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
                            <p class="course-instructor">Giảng viên: <?php echo htmlspecialchars($khóa_học['tên_giảng_viên']); ?></p>
                            <p class="course-category">Danh mục: <?php echo htmlspecialchars($khóa_học['tên_danh_mục']); ?></p>
                            <p class="course-level">Trình độ: <?php echo htmlspecialchars($khóa_học['level']); ?></p>
                            <p class="course-price"><?php echo number_format($khóa_học['price'], 0, ',', '.'); ?> VNĐ</p>
                            <a href="index.php?controller=course&action=detail&id=<?php echo $khóa_học['id']; ?>" class="btn btn-small">Chi tiết</a>
                        </div>
                    </div>
                    <?php $đếm++; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Chưa có khóa học nào.</p>
            <?php endif; ?>
        </div>
        <div class="text-center">
            <a href="index.php?controller=course&action=index" class="btn btn-primary">Xem tất cả khóa học</a>
        </div>
    </section>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

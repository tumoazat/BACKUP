<?php
$tiêu_đề = "Tìm kiếm khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <h1>Tìm kiếm khóa học</h1>
    
    <div class="search-form" style="margin: 2rem 0;">
        <form method="GET" action="index.php">
            <input type="hidden" name="controller" value="course">
            <input type="hidden" name="action" value="search">
            <div style="display: flex; gap: 1rem;">
                <input type="text" name="keyword" id="search-keyword" placeholder="Nhập từ khóa tìm kiếm..." class="form-control" value="<?php echo htmlspecialchars($từ_khóa ?? ''); ?>" style="flex: 1;">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
            </div>
        </form>
    </div>
    
    <?php if (isset($từ_khóa) && $từ_khóa): ?>
        <h2>Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($từ_khóa); ?>"</h2>
        
        <div class="course-grid">
            <?php if (!empty($danh_sách_khóa_học)): ?>
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
                            <p class="course-instructor">Giảng viên: <?php echo htmlspecialchars($khóa_học['tên_giảng_viên']); ?></p>
                            <p class="course-category">Danh mục: <?php echo htmlspecialchars($khóa_học['tên_danh_mục']); ?></p>
                            <p class="course-level">Trình độ: <?php echo htmlspecialchars($khóa_học['level']); ?></p>
                            <p class="course-price"><?php echo number_format($khóa_học['price'], 0, ',', '.'); ?> VNĐ</p>
                            <a href="index.php?controller=course&action=detail&id=<?php echo $khóa_học['id']; ?>" class="btn btn-small">Chi tiết</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Không tìm thấy khóa học nào phù hợp.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

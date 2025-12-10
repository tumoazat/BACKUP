<?php
$tiêu_đề = "Danh sách khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <h1>Danh sách khóa học</h1>
    
    <div class="filter-section" style="margin: 2rem 0;">
        <h3>Lọc theo danh mục:</h3>
        <select onchange="window.location.href='index.php?controller=course&action=index&category_id='+this.value" class="form-control" style="max-width: 300px;">
            <option value="">Tất cả danh mục</option>
            <?php if (!empty($danh_sách_danh_mục)): ?>
                <?php foreach ($danh_sách_danh_mục as $danh_mục): ?>
                    <option value="<?php echo $danh_mục['id']; ?>" <?php echo (isset($_GET['category_id']) && $_GET['category_id'] == $danh_mục['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($danh_mục['name']); ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    
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
            <p>Không tìm thấy khóa học nào.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

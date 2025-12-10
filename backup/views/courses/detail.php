<?php
$tiêu_đề = "Chi tiết khóa học - " . ($khóa_học['title'] ?? 'Khóa học');
require_once 'views/layouts/header.php';
?>

<div class="container">
    <?php if ($khóa_học): ?>
        <div class="course-detail">
            <?php if (!empty($khóa_học['image'])): ?>
                <div class="course-detail-image" style="margin-bottom: 2rem; text-align: center;">
                    <img src="assets/images/<?php echo htmlspecialchars($khóa_học['image']); ?>" alt="<?php echo htmlspecialchars($khóa_học['title']); ?>" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                </div>
            <?php endif; ?>
            <h1><?php echo htmlspecialchars($khóa_học['title']); ?></h1>
            <p><strong>Giảng viên:</strong> <?php echo htmlspecialchars($khóa_học['tên_giảng_viên']); ?></p>
            <p><strong>Danh mục:</strong> <?php echo htmlspecialchars($khóa_học['tên_danh_mục']); ?></p>
            <p><strong>Trình độ:</strong> <?php echo htmlspecialchars($khóa_học['level']); ?></p>
            <p><strong>Thời lượng:</strong> <?php echo $khóa_học['duration_weeks']; ?> tuần</p>
            <p><strong>Giá:</strong> <span class="course-price"><?php echo number_format($khóa_học['price'], 0, ',', '.'); ?> VNĐ</span></p>
            
            <div class="course-description">
                <h3>Mô tả khóa học</h3>
                <p><?php echo nl2br(htmlspecialchars($khóa_học['description'])); ?></p>
            </div>
            
            <?php if (isset($_SESSION['đã_đăng_nhập']) && $_SESSION['role'] == 0): ?>
                <?php if (!$đã_đăng_ký): ?>
                    <form method="POST" action="index.php?controller=enrollment&action=enroll">
                        <input type="hidden" name="course_id" value="<?php echo $khóa_học['id']; ?>">
                        <button type="submit" class="btn btn-success">Đăng ký khóa học</button>
                    </form>
                <?php else: ?>
                    <p class="alert alert-success">Bạn đã đăng ký khóa học này</p>
                    <a href="index.php?controller=student&action=course_progress&course_id=<?php echo $khóa_học['id']; ?>" class="btn btn-primary">Xem tiến độ</a>
                <?php endif; ?>
            <?php endif; ?>
            
            <div class="lessons-section" style="margin-top: 2rem;">
                <h3>Danh sách bài học</h3>
                <?php if (!empty($danh_sách_bài_học)): ?>
                    <ul style="list-style: none; padding: 0;">
                        <?php foreach ($danh_sách_bài_học as $bài_học): ?>
                            <li style="padding: 0.5rem; border-bottom: 1px solid #ddd;">
                                <?php echo htmlspecialchars($bài_học['title']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Chưa có bài học nào.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <p>Không tìm thấy khóa học.</p>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

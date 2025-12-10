<?php
$tiêu_đề = "Tiến độ khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Tiến độ khóa học</h1>
            
            <?php if ($khóa_học): ?>
                <h2><?php echo htmlspecialchars($khóa_học['title']); ?></h2>
                <p><strong>Giảng viên:</strong> <?php echo htmlspecialchars($khóa_học['tên_giảng_viên']); ?></p>
                
                <h3>Danh sách bài học</h3>
                <?php if (!empty($danh_sách_bài_học)): ?>
                    <div style="margin: 2rem 0;">
                        <?php foreach ($danh_sách_bài_học as $bài_học): ?>
                            <div style="padding: 1rem; background: #f8f9fa; margin-bottom: 1rem; border-radius: 4px;">
                                <h4><?php echo htmlspecialchars($bài_học['title']); ?></h4>
                                <a href="index.php?controller=lesson&action=view&id=<?php echo $bài_học['id']; ?>" class="btn btn-small btn-primary">Xem bài học</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>Chưa có bài học nào.</p>
                <?php endif; ?>
            <?php else: ?>
                <p>Không tìm thấy khóa học.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

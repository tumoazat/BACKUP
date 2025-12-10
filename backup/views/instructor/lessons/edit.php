<?php
$tiêu_đề = "Chỉnh sửa bài học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Chỉnh sửa bài học</h1>
            
            <?php if ($bài_học): ?>
                <form method="POST" action="index.php?controller=instructor&action=edit_lesson&id=<?php echo $bài_học['id']; ?>">
                    <div class="form-group">
                        <label for="title">Tên bài học:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($bài_học['title']); ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Nội dung:</label>
                        <textarea id="content" name="content" rows="6" required class="form-control"><?php echo htmlspecialchars($bài_học['content']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="video_url">Video URL:</label>
                        <input type="url" id="video_url" name="video_url" value="<?php echo htmlspecialchars($bài_học['video_url']); ?>" placeholder="https://youtube.com/..." class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="order">Thứ tự:</label>
                        <input type="number" id="order" name="order" min="1" value="<?php echo $bài_học['order']; ?>" class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                    <a href="index.php?controller=instructor&action=manage_course&id=<?php echo $bài_học['course_id']; ?>" class="btn btn-secondary">Hủy</a>
                </form>
            <?php else: ?>
                <p>Không tìm thấy bài học.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

<?php
$tiêu_đề = "Tạo bài học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Tạo bài học mới</h1>
            
            <form method="POST" action="index.php?controller=instructor&action=create_lesson&course_id=<?php echo $_GET['course_id']; ?>">
                <div class="form-group">
                    <label for="title">Tên bài học:</label>
                    <input type="text" id="title" name="title" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="content">Nội dung:</label>
                    <textarea id="content" name="content" rows="6" required class="form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="video_url">Video URL:</label>
                    <input type="url" id="video_url" name="video_url" placeholder="https://youtube.com/..." class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="order">Thứ tự:</label>
                    <input type="number" id="order" name="order" min="1" value="1" class="form-control">
                </div>
                
                <button type="submit" class="btn btn-success">Tạo bài học</button>
                <a href="index.php?controller=instructor&action=manage_course&id=<?php echo $_GET['course_id']; ?>" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

<?php
$tiêu_đề = "Tạo danh mục - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Tạo danh mục mới</h1>
            
            <form method="POST" action="index.php?controller=admin&action=create_category">
                <div class="form-group">
                    <label for="name">Tên danh mục:</label>
                    <input type="text" id="name" name="name" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="description">Mô tả:</label>
                    <textarea id="description" name="description" rows="4" class="form-control"></textarea>
                </div>
                
                <button type="submit" class="btn btn-success">Tạo danh mục</button>
                <a href="index.php?controller=admin&action=list_categories" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

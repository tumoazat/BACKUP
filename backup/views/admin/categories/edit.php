<?php
$tiêu_đề = "Chỉnh sửa danh mục - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Chỉnh sửa danh mục</h1>
            
            <?php if ($danh_mục): ?>
                <form method="POST" action="index.php?controller=admin&action=edit_category&id=<?php echo $danh_mục['id']; ?>">
                    <div class="form-group">
                        <label for="name">Tên danh mục:</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($danh_mục['name']); ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Mô tả:</label>
                        <textarea id="description" name="description" rows="4" class="form-control"><?php echo htmlspecialchars($danh_mục['description']); ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                    <a href="index.php?controller=admin&action=list_categories" class="btn btn-secondary">Hủy</a>
                </form>
            <?php else: ?>
                <p>Không tìm thấy danh mục.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

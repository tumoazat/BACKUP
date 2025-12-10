<?php
$tiêu_đề = "Tạo khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Tạo khóa học mới</h1>
            
            <form method="POST" action="index.php?controller=instructor&action=create_course">
                <div class="form-group">
                    <label for="title">Tên khóa học:</label>
                    <input type="text" id="title" name="title" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="description">Mô tả:</label>
                    <textarea id="description" name="description" rows="4" required class="form-control"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="category_id">Danh mục:</label>
                    <select id="category_id" name="category_id" required class="form-control">
                        <?php if (!empty($danh_sách_danh_mục)): ?>
                            <?php foreach ($danh_sách_danh_mục as $danh_mục): ?>
                                <option value="<?php echo $danh_mục['id']; ?>"><?php echo htmlspecialchars($danh_mục['name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="price">Giá (VNĐ):</label>
                    <input type="number" id="price" name="price" min="0" value="0" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="duration_weeks">Thời lượng (tuần):</label>
                    <input type="number" id="duration_weeks" name="duration_weeks" min="1" value="8" class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="level">Trình độ:</label>
                    <select id="level" name="level" class="form-control">
                        <option value="Beginner">Beginner</option>
                        <option value="Intermediate">Intermediate</option>
                        <option value="Advanced">Advanced</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="image">Hình ảnh khóa học:</label>
                    <select id="image" name="image" class="form-control" onchange="previewImage(this.value)">
                        <option value="">-- Chọn ảnh --</option>
                        <option value="web.jpg">Web Development</option>
                        <option value="php.jpg">PHP Programming</option>
                        <option value="mobile.jpg">Mobile Development</option>
                        <option value="android.jpg">Android Development</option>
                        <option value="khoahocdulieu.jpg">Data Science</option>
                        <option value="cosodulieu.jpg">Database</option>
                        <option value="anninhmang.jpg">Network Security</option>
                    </select>
                    <div id="imagePreview" style="margin-top: 10px; display: none;">
                        <img id="previewImg" src="" alt="Preview" style="max-width: 300px; height: auto; border: 2px solid #ddd; border-radius: 5px;">
                    </div>
                </div>
                
                <script>
                function previewImage(filename) {
                    var preview = document.getElementById('imagePreview');
                    var img = document.getElementById('previewImg');
                    if (filename) {
                        img.src = 'assets/images/' + filename;
                        preview.style.display = 'block';
                    } else {
                        preview.style.display = 'none';
                    }
                }
                </script>
                
                <button type="submit" class="btn btn-success">Tạo khóa học</button>
                <a href="index.php?controller=instructor&action=my_courses" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

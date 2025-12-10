<?php
$tiêu_đề = "Chỉnh sửa khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Chỉnh sửa khóa học</h1>
            
            <?php if ($khóa_học): ?>
                <form method="POST" action="index.php?controller=instructor&action=edit_course&id=<?php echo $khóa_học['id']; ?>">
                    <div class="form-group">
                        <label for="title">Tên khóa học:</label>
                        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($khóa_học['title']); ?>" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Mô tả:</label>
                        <textarea id="description" name="description" rows="4" required class="form-control"><?php echo htmlspecialchars($khóa_học['description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Danh mục:</label>
                        <select id="category_id" name="category_id" required class="form-control">
                            <?php if (!empty($danh_sách_danh_mục)): ?>
                                <?php foreach ($danh_sách_danh_mục as $danh_mục): ?>
                                    <option value="<?php echo $danh_mục['id']; ?>" <?php echo ($khóa_học['category_id'] == $danh_mục['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($danh_mục['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Giá (VNĐ):</label>
                        <input type="number" id="price" name="price" min="0" value="<?php echo $khóa_học['price']; ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="duration_weeks">Thời lượng (tuần):</label>
                        <input type="number" id="duration_weeks" name="duration_weeks" min="1" value="<?php echo $khóa_học['duration_weeks']; ?>" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="level">Trình độ:</label>
                        <select id="level" name="level" class="form-control">
                            <option value="Beginner" <?php echo ($khóa_học['level'] == 'Beginner') ? 'selected' : ''; ?>>Beginner</option>
                            <option value="Intermediate" <?php echo ($khóa_học['level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                            <option value="Advanced" <?php echo ($khóa_học['level'] == 'Advanced') ? 'selected' : ''; ?>>Advanced</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Hình ảnh khóa học:</label>
                        <select id="image" name="image" class="form-control" onchange="previewImage(this.value)">
                            <option value="">-- Chọn ảnh --</option>
                            <option value="web.jpg" <?php echo ($khóa_học['image'] == 'web.jpg') ? 'selected' : ''; ?>>Web Development</option>
                            <option value="php.jpg" <?php echo ($khóa_học['image'] == 'php.jpg') ? 'selected' : ''; ?>>PHP Programming</option>
                            <option value="mobile.jpg" <?php echo ($khóa_học['image'] == 'mobile.jpg') ? 'selected' : ''; ?>>Mobile Development</option>
                            <option value="android.jpg" <?php echo ($khóa_học['image'] == 'android.jpg') ? 'selected' : ''; ?>>Android Development</option>
                            <option value="khoahocdulieu.jpg" <?php echo ($khóa_học['image'] == 'khoahocdulieu.jpg') ? 'selected' : ''; ?>>Data Science</option>
                            <option value="cosodulieu.jpg" <?php echo ($khóa_học['image'] == 'cosodulieu.jpg') ? 'selected' : ''; ?>>Database</option>
                            <option value="anninhmang.jpg" <?php echo ($khóa_học['image'] == 'anninhmang.jpg') ? 'selected' : ''; ?>>Network Security</option>
                        </select>
                        <div id="imagePreview" style="margin-top: 10px;">
                            <?php if (!empty($khóa_học['image'])): ?>
                                <img id="previewImg" src="assets/images/<?php echo htmlspecialchars($khóa_học['image']); ?>" alt="Preview" style="max-width: 300px; height: auto; border: 2px solid #ddd; border-radius: 5px;">
                            <?php else: ?>
                                <img id="previewImg" src="" alt="Preview" style="max-width: 300px; height: auto; border: 2px solid #ddd; border-radius: 5px; display: none;">
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <script>
                    function previewImage(filename) {
                        var img = document.getElementById('previewImg');
                        if (filename) {
                            img.src = 'assets/images/' + filename;
                            img.style.display = 'block';
                        } else {
                            img.style.display = 'none';
                        }
                    }
                    </script>
                    
                    <button type="submit" class="btn btn-success">Cập nhật</button>
                    <a href="index.php?controller=instructor&action=my_courses" class="btn btn-secondary">Hủy</a>
                </form>
            <?php else: ?>
                <p>Không tìm thấy khóa học.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

<?php
$tiêu_đề = "Tải lên tài liệu - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Tải lên tài liệu học tập</h1>
            
            <div class="upload-material-container">
                <!-- Upload Area -->
                <form id="material-upload-form" method="POST" action="index.php?controller=instructor&action=upload_material&lesson_id=<?php echo $_GET['lesson_id']; ?>" enctype="multipart/form-data" onsubmit="return validateUploadForm('material-upload-form')">
                    
                    <div class="form-group">
                        <label for="title">Tiêu đề tài liệu: <span style="color: red;">*</span></label>
                        <input type="text" id="title" name="title" required class="form-control" placeholder="Nhập tiêu đề tài liệu">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Mô tả:</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Mô tả ngắn về tài liệu này"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Chọn file tài liệu: <span style="color: red;">*</span></label>
                        <div class="upload-area" id="material-upload-area">
                            <div class="upload-icon">📚</div>
                            <p class="upload-text">Kéo thả file vào đây hoặc click để chọn</p>
                            <p style="font-size: 0.9rem; color: #999;">
                                Định dạng: PDF, DOC, DOCX, PPT, PPTX (Max: 10MB)
                            </p>
                            <input type="file" id="material-input" name="material_file" accept=".pdf,.doc,.docx,.ppt,.pptx" onchange="previewMaterial(this)" style="display: none;" required>
                        </div>
                        
                        <div id="file-info" class="file-info" style="display: none;"></div>
                    </div>
                    
                    <div class="progress-bar" id="material-progress" style="display: none;">
                        <div class="progress-fill" style="width: 0%;">0%</div>
                    </div>
                    
                    <div class="form-actions" style="margin-top: 2rem; display: flex; gap: 1rem;">
                        <button type="submit" class="btn btn-success">
                            📤 Tải lên tài liệu
                        </button>
                        <a href="index.php?controller=instructor&action=manage_course&id=<?php echo $khóa_học['id'] ?? ''; ?>" class="btn btn-secondary">
                            ❌ Hủy
                        </a>
                    </div>
                </form>
                
                <!-- List of existing materials -->
                <?php if (isset($tài_liệu_hiện_có) && !empty($tài_liệu_hiện_có)): ?>
                <div class="existing-materials" style="margin-top: 3rem;">
                    <h3>Tài liệu đã tải lên</h3>
                    <ul class="material-list">
                        <?php foreach ($tài_liệu_hiện_có as $tài_liệu): ?>
                        <li class="material-item">
                            <div style="display: flex; align-items: center;">
                                <span class="material-icon"><?php echo getFileIcon($tài_liệu['filename']); ?></span>
                                <div class="material-info">
                                    <div class="material-name"><?php echo htmlspecialchars($tài_liệu['filename']); ?></div>
                                    <div class="material-meta">
                                        Tải lên: <?php echo date('d/m/Y H:i', strtotime($tài_liệu['uploaded_at'])); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="material-actions">
                                <a href="assets/uploads/materials/<?php echo htmlspecialchars($tài_liệu['file_path']); ?>" class="btn btn-small btn-primary" download>
                                    ⬇️ Tải về
                                </a>
                                <a href="index.php?controller=instructor&action=delete_material&id=<?php echo $tài_liệu['id']; ?>" 
                                   class="btn btn-small btn-danger" 
                                   onclick="return xácNhậnXóa('Bạn có chắc muốn xóa tài liệu này?')">
                                    🗑️ Xóa
                                </a>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.upload-material-container {
    max-width: 800px;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-start;
}

.existing-materials {
    padding: 2rem;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 8px;
    border: 1px solid #eee;
}

.existing-materials h3 {
    margin-bottom: 1.5rem;
    color: #2c3e50;
}
</style>

<script>
function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const icons = {
        'pdf': '📄',
        'doc': '📝',
        'docx': '📝',
        'ppt': '📊',
        'pptx': '📊'
    };
    return icons[ext] || '📎';
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>

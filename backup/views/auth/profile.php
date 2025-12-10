<?php
/**
 * View: Profile Settings - Upload Avatar
 */
$tiêu_đề = "Cài đặt Tài khoản";
include 'views/layouts/header.php';
?>

<div class="container">
    <div class="profile-container">
        <h1 class="text-center">Cài đặt Tài khoản</h1>
        
        <div class="profile-card">
            <div class="profile-header">
                <div class="avatar-preview" id="avatar-preview">
                    <?php if (isset($người_dùng['avatar']) && !empty($người_dùng['avatar'])): ?>
                        <img src="assets/uploads/avatars/<?php echo htmlspecialchars($người_dùng['avatar']); ?>" alt="Avatar">
                    <?php else: ?>
                        <div class="avatar-placeholder">
                            <?php echo strtoupper(substr($người_dùng['fullname'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="profile-name"><?php echo htmlspecialchars($người_dùng['fullname']); ?></div>
                <div class="profile-role">
                    <?php 
                        $roles = ['Học viên', 'Giảng viên', 'Quản trị viên'];
                        echo $roles[$người_dùng['role']];
                    ?>
                </div>
            </div>
            
            <!-- Upload Avatar Form -->
            <div class="upload-section">
                <h3>Cập nhật Avatar</h3>
                <form id="avatar-upload-form" action="index.php?controller=auth&action=upload_avatar" method="POST" enctype="multipart/form-data" onsubmit="return validateUploadForm('avatar-upload-form')">
                    
                    <div class="upload-area" id="avatar-upload-area">
                        <div class="upload-icon">📷</div>
                        <p class="upload-text">Kéo thả ảnh vào đây hoặc click để chọn</p>
                        <p style="font-size: 0.9rem; color: #999;">Định dạng: JPG, PNG, GIF (Max: 2MB)</p>
                        <input type="file" id="avatar-input" name="avatar" accept="image/*" onchange="previewAvatar(this)" style="display: none;">
                    </div>
                    
                    <div class="progress-bar" id="avatar-progress" style="display: none;">
                        <div class="progress-fill" style="width: 0%;">0%</div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary btn-block">
                            Tải lên Avatar
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Profile Information -->
            <div class="profile-info">
                <h3>Thông tin cá nhân</h3>
                <div class="profile-info-item">
                    <span class="profile-info-label">Username:</span>
                    <span><?php echo htmlspecialchars($người_dùng['username']); ?></span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Email:</span>
                    <span><?php echo htmlspecialchars($người_dùng['email']); ?></span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Số điện thoại:</span>
                    <span><?php echo htmlspecialchars($người_dùng['phone'] ?? 'Chưa cập nhật'); ?></span>
                </div>
                <div class="profile-info-item">
                    <span class="profile-info-label">Ngày tham gia:</span>
                    <span><?php echo date('d/m/Y', strtotime($người_dùng['created_at'])); ?></span>
                </div>
            </div>
            
            <!-- Update Profile Information -->
            <div class="update-profile-section" style="margin-top: 2rem;">
                <h3>Cập nhật thông tin</h3>
                <form action="index.php?controller=auth&action=update_profile" method="POST" onsubmit="return validateForm('update-profile-form')" id="update-profile-form">
                    <div class="form-group">
                        <label for="fullname">Họ và tên:</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo htmlspecialchars($người_dùng['fullname']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($người_dùng['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Số điện thoại:</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($người_dùng['phone'] ?? ''); ?>" pattern="[0-9]{10,11}" placeholder="0123456789">
                        <small style="color: #666; font-size: 0.9rem;">Nhập 10-11 số</small>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            💾 Cập nhật thông tin
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Change Password Section -->
            <div class="password-section" style="margin-top: 2rem;">
                <h3>Đổi mật khẩu</h3>
                <form action="index.php?controller=auth&action=change_password" method="POST" onsubmit="return validateForm('password-form')" id="password-form">
                    <div class="form-group">
                        <label for="current_password">Mật khẩu hiện tại:</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới:</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary btn-block">
                            🔒 Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles now in main CSS file */
</style>

<?php include 'views/layouts/footer.php'; ?>

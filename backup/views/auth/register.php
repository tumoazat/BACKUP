<?php
$tiêu_đề = "Đăng ký - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="auth-container">
        <div class="auth-box">
            <h2>Đăng ký tài khoản</h2>
            <form method="POST" action="index.php?controller=auth&action=register" class="auth-form">
                <div class="form-group">
                    <label for="fullname">Họ và tên:</label>
                    <input type="text" id="fullname" name="fullname" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="username">Tên đăng nhập:</label>
                    <input type="text" id="username" name="username" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="password">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="form-control">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
            </form>
            
            <div class="auth-footer">
                <p>Đã có tài khoản? <a href="index.php?controller=auth&action=login">Đăng nhập</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

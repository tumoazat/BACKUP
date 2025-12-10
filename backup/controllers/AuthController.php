<?php
/**
 * Controller Auth - Xử lý đăng nhập và đăng ký
 */
class AuthController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Hiển thị form đăng nhập
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->xửLýĐăngNhập();
        } else {
            require_once 'views/auth/login.php';
        }
    }
    
    /**
     * Xử lý đăng nhập
     */
    private function xửLýĐăngNhập()
    {
        require_once 'models/User.php';
        
        $userModel = new User($this->db);
        $userModel->username = $_POST['username'] ?? '';
        $userModel->password = $_POST['password'] ?? '';
        
        if ($userModel->đăngNhập()) {
            // Lưu thông tin vào session
            $_SESSION['user_id'] = $userModel->id;
            $_SESSION['username'] = $userModel->username;
            $_SESSION['fullname'] = $userModel->fullname;
            $_SESSION['role'] = $userModel->role;
            $_SESSION['đã_đăng_nhập'] = true;
            
            // Chuyển hướng theo vai trò
            switch ($userModel->role) {
                case 2: // Admin
                    header('Location: index.php?controller=admin&action=dashboard');
                    break;
                case 1: // Giảng viên
                    header('Location: index.php?controller=instructor&action=dashboard');
                    break;
                case 0: // Học viên
                default:
                    header('Location: index.php?controller=student&action=dashboard');
                    break;
            }
            exit();
        } else {
            $_SESSION['lỗi'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }
    
    /**
     * Hiển thị form đăng ký
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->xửLýĐăngKý();
        } else {
            require_once 'views/auth/register.php';
        }
    }
    
    /**
     * Xử lý đăng ký
     */
    private function xửLýĐăngKý()
    {
        require_once 'models/User.php';
        
        $userModel = new User($this->db);
        $userModel->username = $_POST['username'] ?? '';
        $userModel->email = $_POST['email'] ?? '';
        $userModel->password = $_POST['password'] ?? '';
        $userModel->fullname = $_POST['fullname'] ?? '';
        $userModel->role = 0; // Mặc định là học viên
        
        // Validate
        $xác_nhận_mật_khẩu = $_POST['confirm_password'] ?? '';
        if ($userModel->password !== $xác_nhận_mật_khẩu) {
            $_SESSION['lỗi'] = 'Mật khẩu xác nhận không khớp!';
            header('Location: index.php?controller=auth&action=register');
            exit();
        }
        
        if ($userModel->đăngKý()) {
            $_SESSION['thành_công'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
            header('Location: index.php?controller=auth&action=login');
            exit();
        } else {
            $_SESSION['lỗi'] = 'Đăng ký thất bại! Tên đăng nhập hoặc email đã tồn tại.';
            header('Location: index.php?controller=auth&action=register');
            exit();
        }
    }
    
    /**
     * Đăng xuất
     */
    public function logout()
    {
        session_destroy();
        header('Location: index.php');
        exit();
    }
    
    /**
     * Hiển thị trang profile
     */
    public function profile()
    {
        if (!isset($_SESSION['đã_đăng_nhập'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        require_once 'models/User.php';
        $userModel = new User($this->db);
        $người_dùng = $userModel->lấyTheoId($_SESSION['user_id']);
        
        require_once 'views/auth/profile.php';
    }
    
    /**
     * Upload avatar
     */
    public function upload_avatar()
    {
        if (!isset($_SESSION['đã_đăng_nhập'])) {
            $_SESSION['lỗi'] = 'Bạn cần đăng nhập!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        // Kiểm tra file upload
        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['lỗi'] = 'Vui lòng chọn file ảnh!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        $file = $_FILES['avatar'];
        
        // Kiểm tra loại file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            $_SESSION['lỗi'] = 'Chỉ chấp nhận file ảnh JPG, PNG, GIF!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        // Kiểm tra kích thước (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            $_SESSION['lỗi'] = 'Kích thước ảnh không được vượt quá 2MB!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        // Tạo tên file unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
        $upload_path = 'assets/uploads/avatars/' . $filename;
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // Cập nhật database
            require_once 'models/User.php';
            $userModel = new User($this->db);
            
            // Xóa avatar cũ nếu có
            $người_dùng = $userModel->lấyTheoId($_SESSION['user_id']);
            if (!empty($người_dùng['avatar']) && file_exists('assets/uploads/avatars/' . $người_dùng['avatar'])) {
                unlink('assets/uploads/avatars/' . $người_dùng['avatar']);
            }
            
            // Cập nhật avatar mới
            if ($userModel->cậpNhậtAvatar($_SESSION['user_id'], $filename)) {
                $_SESSION['thành_công'] = 'Cập nhật avatar thành công!';
            } else {
                $_SESSION['lỗi'] = 'Không thể cập nhật avatar vào database!';
            }
        } else {
            $_SESSION['lỗi'] = 'Lỗi khi upload file!';
        }
        
        header('Location: index.php?controller=auth&action=profile');
        exit();
    }
    
    /**
     * Đổi mật khẩu
     */
    public function change_password()
    {
        if (!isset($_SESSION['đã_đăng_nhập'])) {
            $_SESSION['lỗi'] = 'Bạn cần đăng nhập!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validate
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $_SESSION['lỗi'] = 'Vui lòng điền đầy đủ thông tin!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        if ($new_password !== $confirm_password) {
            $_SESSION['lỗi'] = 'Mật khẩu mới không khớp!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        if (strlen($new_password) < 6) {
            $_SESSION['lỗi'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        require_once 'models/User.php';
        $userModel = new User($this->db);
        
        // Kiểm tra mật khẩu hiện tại
        $người_dùng = $userModel->lấyTheoId($_SESSION['user_id']);
        if (!password_verify($current_password, $người_dùng['password'])) {
            $_SESSION['lỗi'] = 'Mật khẩu hiện tại không đúng!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        // Cập nhật mật khẩu mới
        if ($userModel->đổiMậtKhẩu($_SESSION['user_id'], $new_password)) {
            $_SESSION['thành_công'] = 'Đổi mật khẩu thành công!';
        } else {
            $_SESSION['lỗi'] = 'Không thể đổi mật khẩu!';
        }
        
        header('Location: index.php?controller=auth&action=profile');
        exit();
    }
    
    /**
     * Cập nhật thông tin profile
     */
    public function update_profile()
    {
        if (!isset($_SESSION['đã_đăng_nhập'])) {
            $_SESSION['lỗi'] = 'Bạn cần đăng nhập!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        
        // Validate
        if (empty($fullname) || empty($email)) {
            $_SESSION['lỗi'] = 'Họ tên và email không được để trống!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['lỗi'] = 'Email không hợp lệ!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        if (!empty($phone) && !preg_match('/^[0-9]{10,11}$/', $phone)) {
            $_SESSION['lỗi'] = 'Số điện thoại phải có 10-11 chữ số!';
            header('Location: index.php?controller=auth&action=profile');
            exit();
        }
        
        require_once 'models/User.php';
        $userModel = new User($this->db);
        
        // Kiểm tra email đã tồn tại chưa (trừ email của chính user)
        $người_dùng_hiện_tại = $userModel->lấyTheoId($_SESSION['user_id']);
        if ($email !== $người_dùng_hiện_tại['email']) {
            if ($userModel->kiểmTraEmailTồnTại($email)) {
                $_SESSION['lỗi'] = 'Email đã được sử dụng bởi tài khoản khác!';
                header('Location: index.php?controller=auth&action=profile');
                exit();
            }
        }
        
        // Cập nhật thông tin
        if ($userModel->cậpNhậtProfile($_SESSION['user_id'], $fullname, $email, $phone)) {
            $_SESSION['fullname'] = $fullname; // Cập nhật session
            $_SESSION['thành_công'] = 'Cập nhật thông tin thành công!';
        } else {
            $_SESSION['lỗi'] = 'Không thể cập nhật thông tin!';
        }
        
        header('Location: index.php?controller=auth&action=profile');
        exit();
    }
}

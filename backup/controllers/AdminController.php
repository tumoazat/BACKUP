<?php
/**
 * Controller Admin - Xử lý các chức năng quản trị
 */
class AdminController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
        
        // Kiểm tra quyền admin
        if (!isset($_SESSION['đã_đăng_nhập']) || $_SESSION['role'] != 2) {
            $_SESSION['lỗi'] = 'Bạn không có quyền truy cập trang này!';
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * Dashboard admin
     */
    public function dashboard()
    {
        require_once 'models/User.php';
        require_once 'models/Course.php';
        require_once 'models/Enrollment.php';
        
        $userModel = new User($this->db);
        $courseModel = new Course($this->db);
        
        // Thống kê
        $tổng_người_dùng = count($userModel->lấyTấtCả());
        $tổng_giảng_viên = count($userModel->lấyTheoVaiTrò(1));
        $tổng_học_viên = count($userModel->lấyTheoVaiTrò(0));
        $tổng_khóa_học = count($courseModel->lấyTấtCả());
        
        require_once 'views/admin/dashboard.php';
    }
    
    /**
     * Quản lý người dùng
     */
    public function manage_users()
    {
        require_once 'models/User.php';
        
        $userModel = new User($this->db);
        $danh_sách_người_dùng = $userModel->lấyTấtCả();
        
        require_once 'views/admin/users/manage.php';
    }
    
    /**
     * Xóa người dùng
     */
    public function delete_user()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id && $id != $_SESSION['user_id']) {
            require_once 'models/User.php';
            
            $userModel = new User($this->db);
            if ($userModel->xóa($id)) {
                $_SESSION['thành_công'] = 'Xóa người dùng thành công!';
            } else {
                $_SESSION['lỗi'] = 'Xóa người dùng thất bại!';
            }
        }
        
        header('Location: index.php?controller=admin&action=manage_users');
        exit();
    }
    
    /**
     * Danh sách danh mục
     */
    public function list_categories()
    {
        require_once 'models/Category.php';
        
        $categoryModel = new Category($this->db);
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/admin/categories/list.php';
    }
    
    /**
     * Tạo danh mục
     */
    public function create_category()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Category.php';
            
            $categoryModel = new Category($this->db);
            $categoryModel->name = $_POST['name'] ?? '';
            $categoryModel->description = $_POST['description'] ?? '';
            
            if ($categoryModel->tạo()) {
                $_SESSION['thành_công'] = 'Tạo danh mục thành công!';
                header('Location: index.php?controller=admin&action=list_categories');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Tạo danh mục thất bại!';
            }
        }
        
        require_once 'views/admin/categories/create.php';
    }
    
    /**
     * Chỉnh sửa danh mục
     */
    public function edit_category()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=admin&action=list_categories');
            exit();
        }
        
        require_once 'models/Category.php';
        
        $categoryModel = new Category($this->db);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel->id = $id;
            $categoryModel->name = $_POST['name'] ?? '';
            $categoryModel->description = $_POST['description'] ?? '';
            
            if ($categoryModel->cậpNhật()) {
                $_SESSION['thành_công'] = 'Cập nhật danh mục thành công!';
                header('Location: index.php?controller=admin&action=list_categories');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Cập nhật danh mục thất bại!';
            }
        }
        
        $danh_mục = $categoryModel->lấyTheoId($id);
        require_once 'views/admin/categories/edit.php';
    }
    
    /**
     * Xóa danh mục
     */
    public function delete_category()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            require_once 'models/Category.php';
            
            $categoryModel = new Category($this->db);
            if ($categoryModel->xóa($id)) {
                $_SESSION['thành_công'] = 'Xóa danh mục thành công!';
            } else {
                $_SESSION['lỗi'] = 'Xóa danh mục thất bại! Có thể danh mục đang được sử dụng.';
            }
        }
        
        header('Location: index.php?controller=admin&action=list_categories');
        exit();
    }
    
    /**
     * Thống kê
     */
    public function statistics()
    {
        require_once 'models/User.php';
        require_once 'models/Course.php';
        require_once 'models/Category.php';
        
        $userModel = new User($this->db);
        $courseModel = new Course($this->db);
        $categoryModel = new Category($this->db);
        
        $danh_sách_người_dùng = $userModel->lấyTấtCả();
        $danh_sách_khóa_học = $courseModel->lấyTấtCả();
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/admin/reports/statistics.php';
    }
}

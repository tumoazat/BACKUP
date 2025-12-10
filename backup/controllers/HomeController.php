<?php
/**
 * Controller Home - Xử lý trang chủ
 */
class HomeController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        // Lấy danh sách khóa học
        require_once 'models/Course.php';
        require_once 'models/Category.php';
        
        $courseModel = new Course($this->db);
        $categoryModel = new Category($this->db);
        
        $danh_sách_khóa_học = $courseModel->lấyTấtCả();
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        // Load view
        require_once 'views/home/index.php';
    }
}

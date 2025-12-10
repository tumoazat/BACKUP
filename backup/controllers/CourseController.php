<?php
/**
 * Controller Course - Xử lý các thao tác liên quan đến khóa học
 */
class CourseController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Hiển thị danh sách khóa học
     */
    public function index()
    {
        require_once 'models/Course.php';
        require_once 'models/Category.php';
        
        $courseModel = new Course($this->db);
        $categoryModel = new Category($this->db);
        
        // Lấy tham số lọc
        $category_id = $_GET['category_id'] ?? null;
        
        if ($category_id) {
            $danh_sách_khóa_học = $courseModel->lấyTheoDanhMục($category_id);
        } else {
            $danh_sách_khóa_học = $courseModel->lấyTấtCả();
        }
        
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/courses/index.php';
    }
    
    /**
     * Hiển thị chi tiết khóa học
     */
    public function detail()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=course&action=index');
            exit();
        }
        
        require_once 'models/Course.php';
        require_once 'models/Lesson.php';
        require_once 'models/Enrollment.php';
        
        $courseModel = new Course($this->db);
        $lessonModel = new Lesson($this->db);
        $enrollmentModel = new Enrollment($this->db);
        
        $khóa_học = $courseModel->lấyTheoId($id);
        $danh_sách_bài_học = $lessonModel->lấyTheoKhóaHọc($id);
        
        // Kiểm tra đã đăng ký chưa (nếu đã đăng nhập)
        $đã_đăng_ký = false;
        if (isset($_SESSION['user_id'])) {
            $đã_đăng_ký = $enrollmentModel->kiểmTraĐãĐăngKý($id, $_SESSION['user_id']);
        }
        
        require_once 'views/courses/detail.php';
    }
    
    /**
     * Tìm kiếm khóa học
     */
    public function search()
    {
        require_once 'models/Course.php';
        require_once 'models/Category.php';
        
        $courseModel = new Course($this->db);
        $categoryModel = new Category($this->db);
        
        $từ_khóa = $_GET['keyword'] ?? '';
        
        if ($từ_khóa) {
            $danh_sách_khóa_học = $courseModel->tìmKiếm($từ_khóa);
        } else {
            $danh_sách_khóa_học = [];
        }
        
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/courses/search.php';
    }
}

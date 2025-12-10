<?php
/**
 * Controller Student - Xử lý các chức năng học viên
 */
class StudentController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
        
        // Kiểm tra quyền học viên
        if (!isset($_SESSION['đã_đăng_nhập']) || $_SESSION['role'] != 0) {
            $_SESSION['lỗi'] = 'Bạn cần đăng nhập với vai trò học viên!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }
    
    /**
     * Dashboard học viên
     */
    public function dashboard()
    {
        require_once 'models/Enrollment.php';
        
        $enrollmentModel = new Enrollment($this->db);
        $danh_sách_khóa_học = $enrollmentModel->lấyKhóaHọcCủaHọcViên($_SESSION['user_id']);
        
        require_once 'views/student/dashboard.php';
    }
    
    /**
     * Khóa học của tôi
     */
    public function my_courses()
    {
        require_once 'models/Enrollment.php';
        
        $enrollmentModel = new Enrollment($this->db);
        $danh_sách_khóa_học = $enrollmentModel->lấyKhóaHọcCủaHọcViên($_SESSION['user_id']);
        
        require_once 'views/student/my_courses.php';
    }
    
    /**
     * Tiến độ khóa học
     */
    public function course_progress()
    {
        $course_id = $_GET['course_id'] ?? null;
        
        if (!$course_id) {
            header('Location: index.php?controller=student&action=my_courses');
            exit();
        }
        
        require_once 'models/Course.php';
        require_once 'models/Lesson.php';
        require_once 'models/Enrollment.php';
        
        $courseModel = new Course($this->db);
        $lessonModel = new Lesson($this->db);
        $enrollmentModel = new Enrollment($this->db);
        
        // Kiểm tra đã đăng ký chưa
        if (!$enrollmentModel->kiểmTraĐãĐăngKý($course_id, $_SESSION['user_id'])) {
            $_SESSION['lỗi'] = 'Bạn chưa đăng ký khóa học này!';
            header('Location: index.php?controller=course&action=detail&id=' . $course_id);
            exit();
        }
        
        $khóa_học = $courseModel->lấyTheoId($course_id);
        $danh_sách_bài_học = $lessonModel->lấyTheoKhóaHọc($course_id);
        
        require_once 'views/student/course_progress.php';
    }
}

<?php
/**
 * Controller Lesson - Xử lý bài học
 */
class LessonController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Xem bài học
     */
    public function view()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['đã_đăng_nhập'])) {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        $lesson_id = $_GET['id'] ?? null;
        
        if (!$lesson_id) {
            header('Location: index.php');
            exit();
        }
        
        require_once 'models/Lesson.php';
        require_once 'models/Material.php';
        require_once 'models/Course.php';
        require_once 'models/Enrollment.php';
        
        $lessonModel = new Lesson($this->db);
        $materialModel = new Material($this->db);
        $courseModel = new Course($this->db);
        $enrollmentModel = new Enrollment($this->db);
        
        $bài_học = $lessonModel->lấyTheoId($lesson_id);
        
        if (!$bài_học) {
            header('Location: index.php');
            exit();
        }
        
        $khóa_học = $courseModel->lấyTheoId($bài_học['course_id']);
        $danh_sách_tài_liệu = $materialModel->lấyTheoBàiHọc($lesson_id);
        $danh_sách_bài_học = $lessonModel->lấyTheoKhóaHọc($bài_học['course_id']);
        
        // Kiểm tra quyền truy cập (học viên phải đăng ký khóa học hoặc là giảng viên của khóa học)
        $có_quyền_truy_cập = false;
        
        if ($_SESSION['role'] == 0) { // Học viên
            $có_quyền_truy_cập = $enrollmentModel->kiểmTraĐãĐăngKý($bài_học['course_id'], $_SESSION['user_id']);
        } elseif ($_SESSION['role'] == 1) { // Giảng viên
            $có_quyền_truy_cập = ($khóa_học['instructor_id'] == $_SESSION['user_id']);
        } elseif ($_SESSION['role'] == 2) { // Admin
            $có_quyền_truy_cập = true;
        }
        
        if (!$có_quyền_truy_cập) {
            $_SESSION['lỗi'] = 'Bạn không có quyền xem bài học này!';
            header('Location: index.php?controller=course&action=detail&id=' . $bài_học['course_id']);
            exit();
        }
        
        require_once 'views/student/lesson_view.php';
    }
}

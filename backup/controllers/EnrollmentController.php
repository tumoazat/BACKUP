<?php
/**
 * Controller Enrollment - Xử lý đăng ký khóa học
 */
class EnrollmentController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Đăng ký khóa học
     */
    public function enroll()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['đã_đăng_nhập']) || $_SESSION['role'] != 0) {
            $_SESSION['lỗi'] = 'Bạn cần đăng nhập với vai trò học viên để đăng ký khóa học!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
        
        $course_id = $_POST['course_id'] ?? null;
        
        if (!$course_id) {
            header('Location: index.php');
            exit();
        }
        
        require_once 'models/Enrollment.php';
        
        $enrollmentModel = new Enrollment($this->db);
        
        // Kiểm tra đã đăng ký chưa
        if ($enrollmentModel->kiểmTraĐãĐăngKý($course_id, $_SESSION['user_id'])) {
            $_SESSION['lỗi'] = 'Bạn đã đăng ký khóa học này rồi!';
            header('Location: index.php?controller=course&action=detail&id=' . $course_id);
            exit();
        }
        
        // Đăng ký khóa học
        $enrollmentModel->course_id = $course_id;
        $enrollmentModel->student_id = $_SESSION['user_id'];
        $enrollmentModel->status = 'active';
        $enrollmentModel->progress = 0;
        
        if ($enrollmentModel->đăngKý()) {
            $_SESSION['thành_công'] = 'Đăng ký khóa học thành công!';
            header('Location: index.php?controller=student&action=my_courses');
        } else {
            $_SESSION['lỗi'] = 'Đăng ký khóa học thất bại!';
            header('Location: index.php?controller=course&action=detail&id=' . $course_id);
        }
        exit();
    }
}

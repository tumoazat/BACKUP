<?php
/**
 * Model Enrollment - Quản lý đăng ký khóa học
 */
class Enrollment
{
    // Kết nối cơ sở dữ liệu
    private $kết_nối;
    private $tên_bảng = 'enrollments';
    
    // Thuộc tính của enrollment
    public $id;
    public $course_id;
    public $student_id;
    public $enrolled_date;
    public $status;
    public $progress;
    
    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->kết_nối = $db;
    }
    
    /**
     * Đăng ký khóa học
     */
    public function đăngKý()
    {
        $câu_truy_vấn = "INSERT INTO " . $this->tên_bảng . " 
                        (course_id, student_id, status, progress) 
                        VALUES (:course_id, :student_id, :status, :progress)";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        
        $stmt->bindParam(':course_id', $this->course_id);
        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':progress', $this->progress);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Kiểm tra đã đăng ký chưa
     */
    public function kiểmTraĐãĐăngKý($course_id, $student_id)
    {
        $câu_truy_vấn = "SELECT * FROM " . $this->tên_bảng . " 
                        WHERE course_id = :course_id AND student_id = :student_id 
                        LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        return $stmt->fetch() ? true : false;
    }
    
    /**
     * Lấy khóa học của học viên
     */
    public function lấyKhóaHọcCủaHọcViên($student_id)
    {
        $câu_truy_vấn = "SELECT e.*, c.title, c.description, c.image, c.level, 
                        u.fullname as tên_giảng_viên, cat.name as tên_danh_mục
                        FROM " . $this->tên_bảng . " e
                        INNER JOIN courses c ON e.course_id = c.id
                        LEFT JOIN users u ON c.instructor_id = u.id
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        WHERE e.student_id = :student_id
                        ORDER BY e.enrolled_date DESC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Lấy học viên của khóa học
     */
    public function lấyHọcViênCủaKhóaHọc($course_id)
    {
        $câu_truy_vấn = "SELECT e.*, u.fullname, u.email, u.username
                        FROM " . $this->tên_bảng . " e
                        INNER JOIN users u ON e.student_id = u.id
                        WHERE e.course_id = :course_id
                        ORDER BY e.enrolled_date DESC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Cập nhật tiến độ
     */
    public function cậpNhậtTiếnĐộ($enrollment_id, $tiến_độ)
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET progress = :progress 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':progress', $tiến_độ);
        $stmt->bindParam(':id', $enrollment_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Cập nhật trạng thái
     */
    public function cậpNhậtTrạngThái($enrollment_id, $trạng_thái)
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET status = :status 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':status', $trạng_thái);
        $stmt->bindParam(':id', $enrollment_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Hủy đăng ký
     */
    public function hủyĐăngKý($enrollment_id)
    {
        $câu_truy_vấn = "DELETE FROM " . $this->tên_bảng . " WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':id', $enrollment_id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

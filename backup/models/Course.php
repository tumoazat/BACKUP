<?php
/**
 * Model Course - Quản lý khóa học
 */
class Course
{
    // Kết nối cơ sở dữ liệu
    private $kết_nối;
    private $tên_bảng = 'courses';
    
    // Thuộc tính của course
    public $id;
    public $title;
    public $description;
    public $instructor_id;
    public $category_id;
    public $price;
    public $duration_weeks;
    public $level;
    public $image;
    public $created_at;
    public $updated_at;
    
    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->kết_nối = $db;
    }
    
    /**
     * Tạo khóa học mới
     */
    public function tạo()
    {
        $câu_truy_vấn = "INSERT INTO " . $this->tên_bảng . " 
                        (title, description, instructor_id, category_id, price, duration_weeks, level, image) 
                        VALUES (:title, :description, :instructor_id, :category_id, :price, :duration_weeks, :level, :image)";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        
        // Bind dữ liệu
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':instructor_id', $this->instructor_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':duration_weeks', $this->duration_weeks);
        $stmt->bindParam(':level', $this->level);
        $stmt->bindParam(':image', $this->image);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Lấy tất cả khóa học
     */
    public function lấyTấtCả()
    {
        $câu_truy_vấn = "SELECT c.*, u.fullname as tên_giảng_viên, cat.name as tên_danh_mục 
                        FROM " . $this->tên_bảng . " c
                        LEFT JOIN users u ON c.instructor_id = u.id
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        ORDER BY c.created_at DESC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Lấy khóa học theo ID
     */
    public function lấyTheoId($id)
    {
        $câu_truy_vấn = "SELECT c.*, u.fullname as tên_giảng_viên, u.email as email_giảng_viên, cat.name as tên_danh_mục 
                        FROM " . $this->tên_bảng . " c
                        LEFT JOIN users u ON c.instructor_id = u.id
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        WHERE c.id = :id 
                        LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Lấy khóa học theo giảng viên
     */
    public function lấyTheoGiảngViên($instructor_id)
    {
        $câu_truy_vấn = "SELECT c.*, cat.name as tên_danh_mục 
                        FROM " . $this->tên_bảng . " c
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        WHERE c.instructor_id = :instructor_id 
                        ORDER BY c.created_at DESC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':instructor_id', $instructor_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Lấy khóa học theo danh mục
     */
    public function lấyTheoDanhMục($category_id)
    {
        $câu_truy_vấn = "SELECT c.*, u.fullname as tên_giảng_viên, cat.name as tên_danh_mục 
                        FROM " . $this->tên_bảng . " c
                        LEFT JOIN users u ON c.instructor_id = u.id
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        WHERE c.category_id = :category_id 
                        ORDER BY c.created_at DESC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Tìm kiếm khóa học
     */
    public function tìmKiếm($từ_khóa)
    {
        $câu_truy_vấn = "SELECT c.*, u.fullname as tên_giảng_viên, cat.name as tên_danh_mục 
                        FROM " . $this->tên_bảng . " c
                        LEFT JOIN users u ON c.instructor_id = u.id
                        LEFT JOIN categories cat ON c.category_id = cat.id
                        WHERE c.title LIKE :từ_khóa OR c.description LIKE :từ_khóa
                        ORDER BY c.created_at DESC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $tìm_kiếm = "%{$từ_khóa}%";
        $stmt->bindParam(':từ_khóa', $tìm_kiếm);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Cập nhật khóa học
     */
    public function cậpNhật()
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET title = :title, description = :description, category_id = :category_id, 
                            price = :price, duration_weeks = :duration_weeks, level = :level, image = :image 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':duration_weeks', $this->duration_weeks);
        $stmt->bindParam(':level', $this->level);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Xóa khóa học
     */
    public function xóa($id)
    {
        $câu_truy_vấn = "DELETE FROM " . $this->tên_bảng . " WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

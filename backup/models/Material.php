<?php
/**
 * Model Material - Quản lý tài liệu học tập
 */
class Material
{
    // Kết nối cơ sở dữ liệu
    private $kết_nối;
    private $tên_bảng = 'materials';
    
    // Thuộc tính của material
    public $id;
    public $lesson_id;
    public $filename;
    public $file_path;
    public $file_type;
    public $uploaded_at;
    
    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->kết_nối = $db;
    }
    
    /**
     * Tải lên tài liệu mới
     */
    public function tảiLên()
    {
        $câu_truy_vấn = "INSERT INTO " . $this->tên_bảng . " 
                        (lesson_id, filename, file_path, file_type) 
                        VALUES (:lesson_id, :filename, :file_path, :file_type)";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        
        $stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->bindParam(':filename', $this->filename);
        $stmt->bindParam(':file_path', $this->file_path);
        $stmt->bindParam(':file_type', $this->file_type);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Lấy tài liệu theo bài học
     */
    public function lấyTheoBàiHọc($lesson_id)
    {
        $câu_truy_vấn = "SELECT * FROM " . $this->tên_bảng . " 
                        WHERE lesson_id = :lesson_id 
                        ORDER BY uploaded_at DESC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':lesson_id', $lesson_id);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Alias method for lấyTheoBàiHọc
     */
    public function lấyTheoLessonId($lesson_id)
    {
        return $this->lấyTheoBàiHọc($lesson_id);
    }
    
    /**
     * Lấy tài liệu theo ID
     */
    public function lấyTheoId($id)
    {
        $câu_truy_vấn = "SELECT * FROM " . $this->tên_bảng . " WHERE id = :id LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch();
    }
    
    /**
     * Xóa tài liệu
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

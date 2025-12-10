<?php
/**
 * Lớp Database - Quản lý kết nối cơ sở dữ liệu
 * Sử dụng PDO để kết nối với MySQL
 */
class Database
{
    // Thông tin kết nối cơ sở dữ liệu
    private $máy_chủ = 'localhost';
    private $tên_csdl = 'onlinecourse';
    private $tên_người_dùng = 'root';
    private $mật_khẩu = '';
    
    // Đối tượng kết nối PDO
    private $kết_nối;
    
    /**
     * Tạo kết nối đến cơ sở dữ liệu
     * @return PDO|null
     */
    public function kếtNối()
    {
        $this->kết_nối = null;
        
        try {
            // Tạo DSN (Data Source Name)
            $dsn = "mysql:host=" . $this->máy_chủ . ";dbname=" . $this->tên_csdl . ";charset=utf8mb4";
            
            // Tùy chọn PDO
            $tùy_chọn = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            // Tạo kết nối PDO
            $this->kết_nối = new PDO($dsn, $this->tên_người_dùng, $this->mật_khẩu, $tùy_chọn);
            
        } catch (PDOException $e) {
            echo "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage();
        }
        
        return $this->kết_nối;
    }
}

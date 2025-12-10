<?php
/**
 * Model User - Quản lý người dùng
 */
class User
{
    // Kết nối cơ sở dữ liệu
    private $kết_nối;
    private $tên_bảng = 'users';
    
    // Thuộc tính của user
    public $id;
    public $username;
    public $email;
    public $password;
    public $fullname;
    public $role;
    public $created_at;
    
    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->kết_nối = $db;
    }
    
    /**
     * Đăng ký người dùng mới
     */
    public function đăngKý()
    {
        $câu_truy_vấn = "INSERT INTO " . $this->tên_bảng . " 
                        (username, email, password, fullname, role) 
                        VALUES (:username, :email, :password, :fullname, :role)";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        
        // Mã hóa mật khẩu
        $mật_khẩu_đã_mã_hóa = password_hash($this->password, PASSWORD_BCRYPT);
        
        // Bind dữ liệu
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $mật_khẩu_đã_mã_hóa);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':role', $this->role);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Đăng nhập
     */
    public function đăngNhập()
    {
        $câu_truy_vấn = "SELECT * FROM " . $this->tên_bảng . " 
                        WHERE username = :username OR email = :email 
                        LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->username);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            // Kiểm tra mật khẩu
            if (password_verify($this->password, $row['password'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
                $this->email = $row['email'];
                $this->fullname = $row['fullname'];
                $this->role = $row['role'];
                $this->created_at = $row['created_at'];
                return true;
            }
        }
        return false;
    }
    
    /**
     * Lấy thông tin user theo ID
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
     * Lấy tất cả người dùng
     */
    public function lấyTấtCả()
    {
        $câu_truy_vấn = "SELECT * FROM " . $this->tên_bảng . " ORDER BY created_at DESC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Lấy người dùng theo vai trò
     */
    public function lấyTheoVaiTrò($vai_trò)
    {
        $câu_truy_vấn = "SELECT * FROM " . $this->tên_bảng . " WHERE role = :role ORDER BY created_at DESC";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':role', $vai_trò);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    /**
     * Cập nhật thông tin user
     */
    public function cậpNhật()
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET email = :email, fullname = :fullname, role = :role 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':id', $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Xóa user
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
    
    /**
     * Cập nhật avatar
     */
    public function cậpNhậtAvatar($id, $avatar_filename)
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET avatar = :avatar 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':avatar', $avatar_filename);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Đổi mật khẩu
     */
    public function đổiMậtKhẩu($id, $mật_khẩu_mới)
    {
        $mật_khẩu_đã_mã_hóa = password_hash($mật_khẩu_mới, PASSWORD_BCRYPT);
        
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET password = :password 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':password', $mật_khẩu_đã_mã_hóa);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Kiểm tra email đã tồn tại chưa
     */
    public function kiểmTraEmailTồnTại($email)
    {
        $câu_truy_vấn = "SELECT id FROM " . $this->tên_bảng . " WHERE email = :email LIMIT 1";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Cập nhật thông tin profile
     */
    public function cậpNhậtProfile($id, $fullname, $email, $phone)
    {
        $câu_truy_vấn = "UPDATE " . $this->tên_bảng . " 
                        SET fullname = :fullname, email = :email, phone = :phone 
                        WHERE id = :id";
        
        $stmt = $this->kết_nối->prepare($câu_truy_vấn);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}

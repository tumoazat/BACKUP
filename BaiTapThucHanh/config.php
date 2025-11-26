<?php
// ============================================================
// config.php - FILE KẾT NỐI CƠ SỞ DỮ LIỆU
// ============================================================
// Đặt file này trong thư mục gốc của project
// Các file khác sẽ include file này để kết nối DB
// ============================================================

// --- THÔNG TIN KẾT NỐI ---
$db_host = 'localhost';      // Địa chỉ server MySQL
$db_user = 'root';           // Tên đăng nhập MySQL
$db_pass = '';               // Mật khẩu MySQL (XAMPP mặc định để trống)
$db_name = 'baitap_web';     // Tên database

// --- TẠO KẾT NỐI ---
// mysqli_connect() dùng để kết nối đến MySQL
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// --- KIỂM TRA KẾT NỐI ---
if (!$conn) {
    // Nếu kết nối thất bại, hiển thị lỗi và dừng chương trình
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// --- THIẾT LẬP CHARSET UTF-8 ---
// Để hiển thị tiếng Việt đúng
mysqli_set_charset($conn, "utf8mb4");

// Thông báo kết nối thành công (có thể comment lại sau khi test)
// echo "Kết nối database thành công!";
?>
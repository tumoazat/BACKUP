# Hệ thống Quản lý Khóa học Online

## Mô tả dự án
Website quản lý khóa học online được xây dựng bằng PHP & MySQL theo mô hình OOP MVC với đầy đủ chức năng cho 3 vai trò: học viên, giảng viên và quản trị viên.

## Công nghệ sử dụng
- PHP 7.4+
- MySQL 5.7+
- HTML5, CSS3, JavaScript
- Mô hình MVC
- PDO cho kết nối cơ sở dữ liệu
- Password hashing với bcrypt

## Cấu trúc thư mục

```
onlinecourse/
├── controllers/         # Controllers xử lý logic
│   ├── HomeController.php
│   ├── AuthController.php
│   ├── CourseController.php
│   ├── EnrollmentController.php
│   ├── LessonController.php
│   ├── StudentController.php
│   ├── InstructorController.php
│   └── AdminController.php
├── models/             # Models tương tác với database
│   ├── User.php
│   ├── Course.php
│   ├── Category.php
│   ├── Enrollment.php
│   ├── Lesson.php
│   └── Material.php
├── views/              # Views hiển thị giao diện
│   ├── layouts/
│   ├── home/
│   ├── auth/
│   ├── courses/
│   ├── student/
│   ├── instructor/
│   └── admin/
├── assets/             # Tài nguyên tĩnh
│   ├── css/
│   ├── js/
│   └── uploads/
├── config/             # Cấu hình
│   └── Database.php
├── .htaccess          # Cấu hình Apache
├── index.php          # Entry point
├── database.sql       # File SQL tạo database
└── README.md          # File này

```

## Cài đặt

### Yêu cầu hệ thống
- PHP >= 7.4
- MySQL >= 5.7
- Apache/Nginx web server
- Extension: PDO, PDO_MySQL

### Các bước cài đặt

1. **Clone hoặc tải về mã nguồn**
   ```bash
   git clone <repository-url>
   cd onlinecourse
   ```

2. **Tạo cơ sở dữ liệu**
   - Mở phpMyAdmin hoặc MySQL client
   - Import file `database.sql` để tạo database và bảng
   - Hoặc chạy lệnh:
   ```bash
   mysql -u root -p < database.sql
   ```

3. **Cấu hình kết nối database**
   - Mở file `config/Database.php`
   - Chỉnh sửa thông tin kết nối:
   ```php
   private $máy_chủ = 'localhost';
   private $tên_csdl = 'onlinecourse';
   
   ```

4. **Cấu hình web server**
   - Đặt thư mục `onlinecourse` vào document root (htdocs/www)
   - Đảm bảo mod_rewrite được bật (Apache)
   - Truy cập: `http://localhost:3000/onlinecourse/index.php`

5. **Đăng nhập với tài khoản mặc định**
   - **Quản trị viên:**
     - Username: `admin`
     - Password: `admin123`
   - **Giảng viên:**
     - Username: `giaovien1`
     - Password: `giaovien123`
   - **Học viên:**
     - Username: `hocvien1`
     - Password: `hocvien123`

## Chức năng chính

### Chức năng Học viên (role = 0)
-  Xem danh sách khóa học
-  Tìm kiếm và lọc khóa học theo danh mục
-  Xem chi tiết khóa học
-  Đăng ký khóa học
-  Xem khóa học đã đăng ký
-  Theo dõi tiến độ học tập
-  Xem bài học và tài liệu

### Chức năng Giảng viên (role = 1)
-  Đăng nhập/đăng xuất
-  Tạo, chỉnh sửa, xóa khóa học
-  Quản lý bài học (tạo, sửa, xóa)
-  Đăng tải tài liệu học tập
-  Xem danh sách học viên đã đăng ký
-  Theo dõi tiến độ của từng học viên

### Chức năng Quản trị viên (role = 2)
-  Quản lý người dùng (xem, xóa)
-  Quản lý danh mục khóa học (tạo, sửa, xóa)
-  Xem thống kê sử dụng hệ thống
-  Xem danh sách tất cả khóa học

## Bảo mật

### Các biện pháp bảo mật đã áp dụng:
1. **Password Hashing:** Sử dụng bcrypt để mã hóa mật khẩu
2. **Prepared Statements:** Sử dụng PDO prepared statements để tránh SQL injection
3. **Session Management:** Quản lý phiên đăng nhập an toàn
4. **Role-based Access Control:** Kiểm tra quyền truy cập cho từng trang
5. **Input Validation:** Validate và sanitize input từ người dùng
6. **XSS Prevention:** Sử dụng htmlspecialchars() để tránh XSS

## Cấu trúc Database

### Bảng users
Lưu thông tin người dùng với 3 vai trò (0: học viên, 1: giảng viên, 2: admin)

### Bảng courses
Lưu thông tin khóa học do giảng viên tạo

### Bảng categories
Lưu danh mục khóa học

### Bảng enrollments
Lưu thông tin đăng ký khóa học của học viên

### Bảng lessons
Lưu bài học của từng khóa học

### Bảng materials
Lưu tài liệu học tập của bài học

## Hướng dẫn sử dụng

### Đối với Học viên:
1. Đăng ký tài khoản mới hoặc đăng nhập
2. Tìm kiếm và xem danh sách khóa học
3. Xem chi tiết khóa học và đăng ký
4. Truy cập "Khóa học của tôi" để xem các khóa đã đăng ký
5. Xem bài học và theo dõi tiến độ

### Đối với Giảng viên:
1. Đăng nhập với tài khoản giảng viên
2. Tạo khóa học mới từ dashboard
3. Thêm bài học cho khóa học
4. Tải lên tài liệu học tập
5. Xem và theo dõi học viên đã đăng ký

### Đối với Quản trị viên:
1. Đăng nhập với tài khoản admin
2. Quản lý người dùng và danh mục
3. Xem thống kê hệ thống
4. Giám sát hoạt động của hệ thống

## Quy ước code

### Tuân theo chuẩn PSR-2:
- Indent: 4 spaces
- Line endings: Unix (LF)
- Class names: PascalCase
- Method names: camelCase
- Constants: UPPER_CASE

### Việt hóa:
- Tất cả tên biến, comment được viết bằng tiếng Việt
- Giao diện người dùng hoàn toàn bằng tiếng Việt
- Message và thông báo bằng tiếng Việt

## Xử lý lỗi thường gặp

### Lỗi kết nối database:
- Kiểm tra thông tin kết nối trong `config/Database.php`
- Đảm bảo MySQL service đang chạy
- Kiểm tra database đã được tạo chưa

### Lỗi 404 Not Found:
- Kiểm tra file `.htaccess` đã được tạo
- Đảm bảo mod_rewrite được bật

### Lỗi không đăng nhập được:
- Kiểm tra session có được khởi động chưa
- Xóa cache và cookie của trình duyệt

## Phát triển thêm

Các tính năng có thể mở rộng:
- Upload file thực tế (hiện tại chỉ lưu đường dẫn)
- Hệ thống thanh toán cho khóa học
- Đánh giá và nhận xét khóa học
- Forum/Discussion cho từng khóa học
- Certificate sau khi hoàn thành
- Notification system
- Email verification
- Quiz và bài kiểm tra
- Video streaming

## Liên hệ và Hỗ trợ

Nếu có vấn đề hoặc câu hỏi, vui lòng liên hệ:
- sdt:09XXXXXX
DIACHI: 175 Tây Sơn, Đống Đa, Hà Nội

## License

Copyright © 2024 Online Course Management System. All rights reserved.

---

**Lưu ý:** Đây là project học tập, không nên sử dụng trực tiếp cho môi trường production mà không có security audit và testing đầy đủ.

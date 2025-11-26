-- ============================================================
-- TẠO CƠ SỞ DỮ LIỆU
-- ============================================================
CREATE DATABASE IF NOT EXISTS baitap_web 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE baitap_web;

-- ============================================================
-- BẢNG 1: DANH SÁCH HOA (Bài tập 1)
-- ============================================================
CREATE TABLE IF NOT EXISTS flowers (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Khóa chính, tự tăng
    name VARCHAR(255) NOT NULL,             -- Tên hoa
    description TEXT,                        -- Mô tả
    image VARCHAR(255),                      -- Tên file ảnh
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP  -- Ngày tạo
);

-- Thêm dữ liệu mẫu cho bảng flowers
INSERT INTO flowers (name, description, image) VALUES
('Hoa Đỗ Quyên', 'Hoa đỗ quyên nở rực rỡ với những chùm hoa dày và nổi bật. ', 'doquyen.jpg'),
('Hoa Hải Đường', 'Hoa hải đường thanh nhã, thường có màu trắng, hồng hoặc kem.', 'haiduong.jpg'),
('Hoa Mai', 'Hoa mai vàng là biểu tượng đặc trưng của mùa xuân phương Nam.', 'mai.jpg'),
('Hoa Tường Vy', 'Hoa tường vy có cánh nhỏ, mọc thành chùm xinh xắn.', 'tuongvy.jpg');

-- ============================================================
-- BẢNG 2: CÂU HỎI QUIZ (Bài tập 2)
-- ============================================================
CREATE TABLE IF NOT EXISTS quiz_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,      -- Khóa chính
    question TEXT NOT NULL,                  -- Nội dung câu hỏi
    option_a VARCHAR(500),                   -- Lựa chọn A
    option_b VARCHAR(500),                   -- Lựa chọn B
    option_c VARCHAR(500),                   -- Lựa chọn C
    option_d VARCHAR(500),                   -- Lựa chọn D
    option_e VARCHAR(500),                   -- Lựa chọn E (nếu có)
    answer VARCHAR(20) NOT NULL,             -- Đáp án đúng (vd: "C" hoặc "A,C")
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Thêm dữ liệu mẫu cho quiz
INSERT INTO quiz_questions (question, option_a, option_b, option_c, option_d, answer) VALUES
('Thành phần nào sau đây KHÔNG phải là một thành phần giao diện người dùng (UI) trong Android?', 'TextView', 'Button', 'Service', 'ImageView', 'C'),
('Layout nào thường được sử dụng để sắp xếp các thành phần UI theo chiều dọc hoặc chiều ngang?', 'RelativeLayout', 'LinearLayout', 'FrameLayout', 'ConstraintLayout', 'B'),
('Intent trong Android được sử dụng để làm gì?', 'Hiển thị thông báo cho người dùng', 'Lưu trữ dữ liệu', 'Khởi chạy Activity', 'Xử lý sự kiện chạm', 'C');

-- ============================================================
-- BẢNG 3: DANH SÁCH SINH VIÊN (Bài tập 3)
-- ============================================================
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,           -- Mã sinh viên
    password VARCHAR(100),                   -- Mật khẩu
    lastname VARCHAR(100),                   -- Họ đệm
    firstname VARCHAR(100),                  -- Tên
    city VARCHAR(100),                       -- Lớp
    email VARCHAR(150),                      -- Email
    course1 VARCHAR(100),                    -- Khóa học
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Thêm dữ liệu mẫu
INSERT INTO students (username, password, lastname, firstname, city, email, course1) VALUES
('2351160500', 'cse@485A', 'Đinh Thị Lan', 'Anh', '65HTTT', '2351160500@e.tlu.edu.vn', 'CSE485. 202401'),
('2351160501', 'cse@485A', 'Đỗ Quang Nam', 'Anh', '65HTTT', '2351160501@e. tlu.edu.vn', 'CSE485.202401'),
('2351160502', 'cse@485A', 'Nguyễn Thái', 'Anh', '65HTTT', '2351160502@e.tlu.edu.vn', 'CSE485.202401');
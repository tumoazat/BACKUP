<?php
/**
 * Controller Instructor - Xử lý các chức năng giảng viên
 */
class InstructorController
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
        
        // Kiểm tra quyền giảng viên
        if (!isset($_SESSION['đã_đăng_nhập']) || $_SESSION['role'] != 1) {
            $_SESSION['lỗi'] = 'Bạn cần đăng nhập với vai trò giảng viên!';
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }
    
    /**
     * Dashboard giảng viên
     */
    public function dashboard()
    {
        require_once 'models/Course.php';
        
        $courseModel = new Course($this->db);
        $danh_sách_khóa_học = $courseModel->lấyTheoGiảngViên($_SESSION['user_id']);
        
        require_once 'views/instructor/dashboard.php';
    }
    
    /**
     * Khóa học của tôi
     */
    public function my_courses()
    {
        require_once 'models/Course.php';
        
        $courseModel = new Course($this->db);
        $danh_sách_khóa_học = $courseModel->lấyTheoGiảngViên($_SESSION['user_id']);
        
        require_once 'views/instructor/my_courses.php';
    }
    
    /**
     * Tạo khóa học
     */
    public function create_course()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Course.php';
            
            $courseModel = new Course($this->db);
            $courseModel->title = $_POST['title'] ?? '';
            $courseModel->description = $_POST['description'] ?? '';
            $courseModel->instructor_id = $_SESSION['user_id'];
            $courseModel->category_id = $_POST['category_id'] ?? 1;
            $courseModel->price = $_POST['price'] ?? 0;
            $courseModel->duration_weeks = $_POST['duration_weeks'] ?? 0;
            $courseModel->level = $_POST['level'] ?? 'Beginner';
            $courseModel->image = $_POST['image'] ?? '';
            
            if ($courseModel->tạo()) {
                $_SESSION['thành_công'] = 'Tạo khóa học thành công!';
                header('Location: index.php?controller=instructor&action=my_courses');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Tạo khóa học thất bại!';
            }
        }
        
        require_once 'models/Category.php';
        $categoryModel = new Category($this->db);
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/instructor/course/create.php';
    }
    
    /**
     * Chỉnh sửa khóa học
     */
    public function edit_course()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Course.php';
        
        $courseModel = new Course($this->db);
        $khóa_học = $courseModel->lấyTheoId($id);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền chỉnh sửa khóa học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $courseModel->id = $id;
            $courseModel->title = $_POST['title'] ?? '';
            $courseModel->description = $_POST['description'] ?? '';
            $courseModel->category_id = $_POST['category_id'] ?? 1;
            $courseModel->price = $_POST['price'] ?? 0;
            $courseModel->duration_weeks = $_POST['duration_weeks'] ?? 0;
            $courseModel->level = $_POST['level'] ?? 'Beginner';
            $courseModel->image = $_POST['image'] ?? '';
            
            if ($courseModel->cậpNhật()) {
                $_SESSION['thành_công'] = 'Cập nhật khóa học thành công!';
                header('Location: index.php?controller=instructor&action=my_courses');
                exit();
            } else {
                $_SESSION['lỗi'] = 'Cập nhật khóa học thất bại!';
            }
        }
        
        require_once 'models/Category.php';
        $categoryModel = new Category($this->db);
        $danh_sách_danh_mục = $categoryModel->lấyTấtCả();
        
        require_once 'views/instructor/course/edit.php';
    }
    
    /**
     * Quản lý khóa học
     */
    public function manage_course()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Course.php';
        require_once 'models/Lesson.php';
        
        $courseModel = new Course($this->db);
        $lessonModel = new Lesson($this->db);
        
        $khóa_học = $courseModel->lấyTheoId($id);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền quản lý khóa học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        $danh_sách_bài_học = $lessonModel->lấyTheoKhóaHọc($id);
        
        require_once 'views/instructor/course/manage.php';
    }
    
    /**
     * Xóa khóa học
     */
    public function delete_course()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            require_once 'models/Course.php';
            
            $courseModel = new Course($this->db);
            $khóa_học = $courseModel->lấyTheoId($id);
            
            // Kiểm tra quyền sở hữu
            if ($khóa_học['instructor_id'] == $_SESSION['user_id']) {
                if ($courseModel->xóa($id)) {
                    $_SESSION['thành_công'] = 'Xóa khóa học thành công!';
                } else {
                    $_SESSION['lỗi'] = 'Xóa khóa học thất bại!';
                }
            } else {
                $_SESSION['lỗi'] = 'Bạn không có quyền xóa khóa học này!';
            }
        }
        
        header('Location: index.php?controller=instructor&action=my_courses');
        exit();
    }
    
    /**
     * Tạo bài học
     */
    public function create_lesson()
    {
        $course_id = $_GET['course_id'] ?? null;
        
        if (!$course_id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Course.php';
        
        $courseModel = new Course($this->db);
        $khóa_học = $courseModel->lấyTheoId($course_id);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền tạo bài học cho khóa học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'models/Lesson.php';
            
            $lessonModel = new Lesson($this->db);
            $lessonModel->course_id = $course_id;
            $lessonModel->title = $_POST['title'] ?? '';
            $lessonModel->content = $_POST['content'] ?? '';
            $lessonModel->video_url = $_POST['video_url'] ?? '';
            $lessonModel->order = $_POST['order'] ?? 0;
            
            if ($lessonModel->tạo()) {
                $_SESSION['thành_công'] = 'Tạo bài học thành công!';
                header('Location: index.php?controller=instructor&action=manage_course&id=' . $course_id);
                exit();
            } else {
                $_SESSION['lỗi'] = 'Tạo bài học thất bại!';
            }
        }
        
        require_once 'views/instructor/lessons/create.php';
    }
    
    /**
     * Chỉnh sửa bài học
     */
    public function edit_lesson()
    {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Lesson.php';
        require_once 'models/Course.php';
        
        $lessonModel = new Lesson($this->db);
        $courseModel = new Course($this->db);
        
        $bài_học = $lessonModel->lấyTheoId($id);
        $khóa_học = $courseModel->lấyTheoId($bài_học['course_id']);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền chỉnh sửa bài học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $lessonModel->id = $id;
            $lessonModel->title = $_POST['title'] ?? '';
            $lessonModel->content = $_POST['content'] ?? '';
            $lessonModel->video_url = $_POST['video_url'] ?? '';
            $lessonModel->order = $_POST['order'] ?? 0;
            
            if ($lessonModel->cậpNhật()) {
                $_SESSION['thành_công'] = 'Cập nhật bài học thành công!';
                header('Location: index.php?controller=instructor&action=manage_course&id=' . $bài_học['course_id']);
                exit();
            } else {
                $_SESSION['lỗi'] = 'Cập nhật bài học thất bại!';
            }
        }
        
        require_once 'views/instructor/lessons/edit.php';
    }
    
    /**
     * Xóa bài học
     */
    public function delete_lesson()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            require_once 'models/Lesson.php';
            require_once 'models/Course.php';
            
            $lessonModel = new Lesson($this->db);
            $courseModel = new Course($this->db);
            
            $bài_học = $lessonModel->lấyTheoId($id);
            $khóa_học = $courseModel->lấyTheoId($bài_học['course_id']);
            
            // Kiểm tra quyền sở hữu
            if ($khóa_học['instructor_id'] == $_SESSION['user_id']) {
                if ($lessonModel->xóa($id)) {
                    $_SESSION['thành_công'] = 'Xóa bài học thành công!';
                    header('Location: index.php?controller=instructor&action=manage_course&id=' . $bài_học['course_id']);
                    exit();
                } else {
                    $_SESSION['lỗi'] = 'Xóa bài học thất bại!';
                }
            } else {
                $_SESSION['lỗi'] = 'Bạn không có quyền xóa bài học này!';
            }
        }
        
        header('Location: index.php?controller=instructor&action=my_courses');
        exit();
    }
    
    /**
     * Tải lên tài liệu
     */
    public function upload_material()
    {
        $lesson_id = $_GET['lesson_id'] ?? null;
        
        if (!$lesson_id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Lesson.php';
        require_once 'models/Course.php';
        
        $lessonModel = new Lesson($this->db);
        $courseModel = new Course($this->db);
        
        $bài_học = $lessonModel->lấyTheoId($lesson_id);
        $khóa_học = $courseModel->lấyTheoId($bài_học['course_id']);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền tải lên tài liệu cho bài học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra file upload
            if (!isset($_FILES['material_file']) || $_FILES['material_file']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['lỗi'] = 'Vui lòng chọn file tài liệu!';
                header('Location: index.php?controller=instructor&action=upload_material&lesson_id=' . $lesson_id);
                exit();
            }
            
            $file = $_FILES['material_file'];
            
            // Kiểm tra loại file
            $allowed_types = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            ];
            
            if (!in_array($file['type'], $allowed_types)) {
                $_SESSION['lỗi'] = 'Chỉ chấp nhận file PDF, DOC, DOCX, PPT, PPTX!';
                header('Location: index.php?controller=instructor&action=upload_material&lesson_id=' . $lesson_id);
                exit();
            }
            
            // Kiểm tra kích thước (max 10MB)
            if ($file['size'] > 10 * 1024 * 1024) {
                $_SESSION['lỗi'] = 'Kích thước file không được vượt quá 10MB!';
                header('Location: index.php?controller=instructor&action=upload_material&lesson_id=' . $lesson_id);
                exit();
            }
            
            // Tạo tên file unique
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'material_' . $lesson_id . '_' . time() . '.' . $extension;
            $upload_path = 'assets/uploads/materials/' . $filename;
            
            // Upload file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                require_once 'models/Material.php';
                
                $materialModel = new Material($this->db);
                $materialModel->lesson_id = $lesson_id;
                $materialModel->filename = $_POST['title'] ?? $file['name'];
                $materialModel->file_path = $filename;
                $materialModel->file_type = $extension;
                
                if ($materialModel->tảiLên()) {
                    $_SESSION['thành_công'] = 'Tải lên tài liệu thành công!';
                    header('Location: index.php?controller=instructor&action=manage_course&id=' . $bài_học['course_id']);
                    exit();
                } else {
                    // Xóa file nếu không lưu được vào database
                    unlink($upload_path);
                    $_SESSION['lỗi'] = 'Không thể lưu thông tin tài liệu vào database!';
                }
            } else {
                $_SESSION['lỗi'] = 'Lỗi khi upload file!';
            }
        }
        
        // Lấy danh sách tài liệu hiện có
        require_once 'models/Material.php';
        $materialModel = new Material($this->db);
        $tài_liệu_hiện_có = $materialModel->lấyTheoLessonId($lesson_id);
        
        require_once 'views/instructor/materials/upload.php';
    }
    
    /**
     * Xóa tài liệu
     */
    public function delete_material()
    {
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            require_once 'models/Material.php';
            require_once 'models/Lesson.php';
            require_once 'models/Course.php';
            
            $materialModel = new Material($this->db);
            $lessonModel = new Lesson($this->db);
            $courseModel = new Course($this->db);
            
            $tài_liệu = $materialModel->lấyTheoId($id);
            $bài_học = $lessonModel->lấyTheoId($tài_liệu['lesson_id']);
            $khóa_học = $courseModel->lấyTheoId($bài_học['course_id']);
            
            // Kiểm tra quyền sở hữu
            if ($khóa_học['instructor_id'] == $_SESSION['user_id']) {
                // Xóa file vật lý
                $file_path = 'assets/uploads/materials/' . $tài_liệu['file_path'];
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
                
                // Xóa record trong database
                if ($materialModel->xóa($id)) {
                    $_SESSION['thành_công'] = 'Xóa tài liệu thành công!';
                } else {
                    $_SESSION['lỗi'] = 'Xóa tài liệu thất bại!';
                }
            } else {
                $_SESSION['lỗi'] = 'Bạn không có quyền xóa tài liệu này!';
            }
            
            header('Location: index.php?controller=instructor&action=upload_material&lesson_id=' . $tài_liệu['lesson_id']);
            exit();
        }
        
        header('Location: index.php?controller=instructor&action=my_courses');
        exit();
    }
    
    /**
     * Danh sách học viên
     */
    public function list_students()
    {
        $course_id = $_GET['course_id'] ?? null;
        
        if (!$course_id) {
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        require_once 'models/Course.php';
        require_once 'models/Enrollment.php';
        
        $courseModel = new Course($this->db);
        $enrollmentModel = new Enrollment($this->db);
        
        $khóa_học = $courseModel->lấyTheoId($course_id);
        
        // Kiểm tra quyền sở hữu
        if ($khóa_học['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['lỗi'] = 'Bạn không có quyền xem danh sách học viên của khóa học này!';
            header('Location: index.php?controller=instructor&action=my_courses');
            exit();
        }
        
        $danh_sách_học_viên = $enrollmentModel->lấyHọcViênCủaKhóaHọc($course_id);
        
        require_once 'views/instructor/students/list.php';
    }
}

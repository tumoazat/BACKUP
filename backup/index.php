<?php
/**
 * File index.php - Entry point của ứng dụng
 * Xử lý routing và khởi tạo controllers
 */

// Khởi động session
session_start();

// Kết nối cơ sở dữ liệu
require_once 'config/Database.php';

$database = new Database();
$db = $database->kếtNối();

// Lấy tham số controller và action từ URL
$controller_name = $_GET['controller'] ?? 'home';
$action_name = $_GET['action'] ?? 'index';

// Mapping controllers
$controller_map = [
    'home' => 'HomeController',
    'auth' => 'AuthController',
    'course' => 'CourseController',
    'enrollment' => 'EnrollmentController',
    'lesson' => 'LessonController',
    'student' => 'StudentController',
    'instructor' => 'InstructorController',
    'admin' => 'AdminController',
];

// Kiểm tra controller tồn tại
if (isset($controller_map[$controller_name])) {
    $controller_class = $controller_map[$controller_name];
    $controller_file = 'controllers/' . $controller_class . '.php';
    
    if (file_exists($controller_file)) {
        require_once $controller_file;
        
        // Khởi tạo controller
        $controller = new $controller_class($db);
        
        // Kiểm tra method tồn tại
        if (method_exists($controller, $action_name)) {
            // Gọi action
            $controller->$action_name();
        } else {
            // Action không tồn tại
            echo "Action không tồn tại!";
        }
    } else {
        // Controller file không tồn tại
        echo "Controller không tồn tại!";
    }
} else {
    // Controller không tồn tại trong map
    echo "Controller không hợp lệ!";
}

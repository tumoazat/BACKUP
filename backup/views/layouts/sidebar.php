<aside class="sidebar">
    <?php if (isset($_SESSION['đã_đăng_nhập']) && $_SESSION['đã_đăng_nhập']): ?>
        <?php if ($_SESSION['role'] == 0): // Học viên ?>
            <h3>Menu học viên</h3>
            <ul>
                <li><a href="index.php?controller=student&action=dashboard">Dashboard</a></li>
                <li><a href="index.php?controller=student&action=my_courses">Khóa học của tôi</a></li>
                <li><a href="index.php?controller=course&action=index">Tìm khóa học</a></li>
            </ul>
        <?php elseif ($_SESSION['role'] == 1): // Giảng viên ?>
            <h3>Menu giảng viên</h3>
            <ul>
                <li><a href="index.php?controller=instructor&action=dashboard">Dashboard</a></li>
                <li><a href="index.php?controller=instructor&action=my_courses">Khóa học của tôi</a></li>
                <li><a href="index.php?controller=instructor&action=create_course">Tạo khóa học mới</a></li>
            </ul>
        <?php elseif ($_SESSION['role'] == 2): // Admin ?>
            <h3>Menu quản trị</h3>
            <ul>
                <li><a href="index.php?controller=admin&action=dashboard">Dashboard</a></li>
                <li><a href="index.php?controller=admin&action=manage_users">Quản lý người dùng</a></li>
                <li><a href="index.php?controller=admin&action=list_categories">Quản lý danh mục</a></li>
                <li><a href="index.php?controller=admin&action=statistics">Thống kê</a></li>
            </ul>
        <?php endif; ?>
    <?php endif; ?>
</aside>

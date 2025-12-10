<?php
$tiêu_đề = "Danh sách học viên - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Danh sách học viên</h1>
            <h2>Khóa học: <?php echo htmlspecialchars($khóa_học['title']); ?></h2>
            
            <?php if (!empty($danh_sách_học_viên)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tên đăng nhập</th>
                            <th>Họ tên</th>
                            <th>Email</th>
                            <th>Ngày đăng ký</th>
                            <th>Tiến độ</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_học_viên as $học_viên): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($học_viên['username']); ?></td>
                                <td><?php echo htmlspecialchars($học_viên['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($học_viên['email']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($học_viên['enrolled_date'])); ?></td>
                                <td><?php echo $học_viên['progress']; ?>%</td>
                                <td><?php echo htmlspecialchars($học_viên['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Chưa có học viên nào đăng ký.</p>
            <?php endif; ?>
            
            <a href="index.php?controller=instructor&action=manage_course&id=<?php echo $_GET['course_id']; ?>" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

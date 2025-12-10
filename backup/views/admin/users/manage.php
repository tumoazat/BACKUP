<?php
$tiêu_đề = "Quản lý người dùng - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Quản lý người dùng</h1>
            
            <?php if (!empty($danh_sách_người_dùng)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Họ tên</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_người_dùng as $người_dùng): ?>
                            <tr>
                                <td><?php echo $người_dùng['id']; ?></td>
                                <td><?php echo htmlspecialchars($người_dùng['username']); ?></td>
                                <td><?php echo htmlspecialchars($người_dùng['email']); ?></td>
                                <td><?php echo htmlspecialchars($người_dùng['fullname']); ?></td>
                                <td>
                                    <?php
                                    switch($người_dùng['role']) {
                                        case 0: echo 'Học viên'; break;
                                        case 1: echo 'Giảng viên'; break;
                                        case 2: echo 'Quản trị viên'; break;
                                    }
                                    ?>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($người_dùng['created_at'])); ?></td>
                                <td>
                                    <?php if ($người_dùng['id'] != $_SESSION['user_id']): ?>
                                        <a href="index.php?controller=admin&action=delete_user&id=<?php echo $người_dùng['id']; ?>" 
                                           onclick="return xácNhậnXóa('Bạn có chắc muốn xóa người dùng này?')" 
                                           class="btn btn-small btn-danger">Xóa</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Không có người dùng nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

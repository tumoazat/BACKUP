<?php
$tiêu_đề = "Dashboard giảng viên - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Dashboard giảng viên</h1>
            <p>Xin chào, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong>!</p>
            
            <div class="stats">
                <div class="stat-card">
                    <h3><?php echo count($danh_sách_khóa_học); ?></h3>
                    <p>Khóa học của tôi</p>
                </div>
            </div>
            
            <div style="margin: 2rem 0;">
                <a href="index.php?controller=instructor&action=create_course" class="btn btn-success">Tạo khóa học mới</a>
            </div>
            
            <h2>Khóa học của tôi</h2>
            <?php if (!empty($danh_sách_khóa_học)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tên khóa học</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Trình độ</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_khóa_học as $khóa_học): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($khóa_học['title']); ?></td>
                                <td><?php echo htmlspecialchars($khóa_học['tên_danh_mục']); ?></td>
                                <td><?php echo number_format($khóa_học['price'], 0, ',', '.'); ?> VNĐ</td>
                                <td><?php echo htmlspecialchars($khóa_học['level']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($khóa_học['created_at'])); ?></td>
                                <td>
                                    <a href="index.php?controller=instructor&action=manage_course&id=<?php echo $khóa_học['id']; ?>" class="btn btn-small">Quản lý</a>
                                    <a href="index.php?controller=instructor&action=edit_course&id=<?php echo $khóa_học['id']; ?>" class="btn btn-small">Sửa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Bạn chưa có khóa học nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

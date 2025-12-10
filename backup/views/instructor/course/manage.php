<?php
$tiêu_đề = "Quản lý khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Quản lý khóa học: <?php echo htmlspecialchars($khóa_học['title']); ?></h1>
            
            <div style="margin: 1rem 0;">
                <a href="index.php?controller=instructor&action=create_lesson&course_id=<?php echo $khóa_học['id']; ?>" class="btn btn-success">Tạo bài học mới</a>
                <a href="index.php?controller=instructor&action=list_students&course_id=<?php echo $khóa_học['id']; ?>" class="btn btn-primary">Xem học viên</a>
            </div>
            
            <h2>Danh sách bài học</h2>
            <?php if (!empty($danh_sách_bài_học)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Thứ tự</th>
                            <th>Tên bài học</th>
                            <th>Video URL</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_bài_học as $bài_học): ?>
                            <tr>
                                <td><?php echo $bài_học['order']; ?></td>
                                <td><?php echo htmlspecialchars($bài_học['title']); ?></td>
                                <td><?php echo htmlspecialchars($bài_học['video_url']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($bài_học['created_at'])); ?></td>
                                <td>
                                    <a href="index.php?controller=instructor&action=edit_lesson&id=<?php echo $bài_học['id']; ?>" class="btn btn-small">Sửa</a>
                                    <a href="index.php?controller=instructor&action=upload_material&lesson_id=<?php echo $bài_học['id']; ?>" class="btn btn-small">Tài liệu</a>
                                    <a href="index.php?controller=instructor&action=delete_lesson&id=<?php echo $bài_học['id']; ?>" 
                                       onclick="return xácNhậnXóa('Bạn có chắc muốn xóa bài học này?')" 
                                       class="btn btn-small btn-danger">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Chưa có bài học nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

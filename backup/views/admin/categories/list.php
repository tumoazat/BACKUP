<?php
$tiêu_đề = "Danh sách danh mục - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <div class="dashboard">
        <?php require_once 'views/layouts/sidebar.php'; ?>
        
        <div class="content">
            <h1>Danh sách danh mục</h1>
            
            <a href="index.php?controller=admin&action=create_category" class="btn btn-success" style="margin-bottom: 1rem;">Tạo danh mục mới</a>
            
            <?php if (!empty($danh_sách_danh_mục)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($danh_sách_danh_mục as $danh_mục): ?>
                            <tr>
                                <td><?php echo $danh_mục['id']; ?></td>
                                <td><?php echo htmlspecialchars($danh_mục['name']); ?></td>
                                <td><?php echo htmlspecialchars($danh_mục['description']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($danh_mục['created_at'])); ?></td>
                                <td>
                                    <a href="index.php?controller=admin&action=edit_category&id=<?php echo $danh_mục['id']; ?>" class="btn btn-small">Sửa</a>
                                    <a href="index.php?controller=admin&action=delete_category&id=<?php echo $danh_mục['id']; ?>" 
                                       onclick="return xácNhậnXóa('Bạn có chắc muốn xóa danh mục này?')" 
                                       class="btn btn-small btn-danger">Xóa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Không có danh mục nào.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

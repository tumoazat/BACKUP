<?php
// ============================================================
// admin.php - TRANG QUẢN TRỊ HOA (CRUD)
// ============================================================
// Chức năng: Thêm, Sửa, Xóa hoa (Create, Read, Update, Delete)
// ============================================================

// Khởi động session để lưu thông báo flash
session_start();

// --- CẤU HÌNH ---
require_once('../config.php');  // Include file kết nối database
$imageDir = 'images/';          // Thư mục lưu ảnh

// --- BƯỚC 1: ĐỌC DỮ LIỆU TỪ DATABASE ---
$flowers = [];
$sql = "SELECT * FROM flowers ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $flowers[] = $row;
    }
}

// --- BƯỚC 2: XỬ LÝ CÁC HÀNH ĐỘNG ---
$action = $_REQUEST['action'] ?? '';

// ========== XỬ LÝ XÓA ==========
if ($action === 'delete') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id > 0) {
        // Lấy thông tin ảnh để xóa file
        $sqlGetImage = "SELECT image FROM flowers WHERE id = $id";
        $resultImage = mysqli_query($conn, $sqlGetImage);
        
        if ($resultImage && $row = mysqli_fetch_assoc($resultImage)) {
            $image = $row['image'];
            if ($image !== '' && file_exists($imageDir . $image)) {
                unlink($imageDir . $image);
            }
        }
        
        // Xóa khỏi database
        $sqlDelete = "DELETE FROM flowers WHERE id = $id";
        if (mysqli_query($conn, $sqlDelete)) {
            $_SESSION['flash'] = '✅ Đã xóa thành công!';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash'] = '❌ Lỗi khi xóa: ' . mysqli_error($conn);
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: admin.php');
    exit;
}

// ========== XỬ LÝ THÊM/SỬA ==========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'save') {
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? intval($_POST['id']) : 0;
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $currentImage = trim($_POST['current_image'] ?? '');
    
    // Xử lý upload ảnh mới
    $imageName = $currentImage;
    
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image_file']['tmp_name'];
        $originalName = $_FILES['image_file']['name'];
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $newName = $safeName . '_' . time() . '.' . $ext;
        
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }
        
        if (move_uploaded_file($tmpName, $imageDir . $newName)) {
            $imageName = $newName;
        }
    }
    
    // Escape dữ liệu để tránh SQL injection
    $name = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $imageName = mysqli_real_escape_string($conn, $imageName);
    
    if ($id > 0) {
        // Cập nhật
        $sqlUpdate = "UPDATE flowers SET name = '$name', description = '$description', image = '$imageName' WHERE id = $id";
        if (mysqli_query($conn, $sqlUpdate)) {
            $_SESSION['flash'] = '✅ Cập nhật thành công!';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash'] = '❌ Lỗi: ' . mysqli_error($conn);
            $_SESSION['flash_type'] = 'error';
        }
    } else {
        // Thêm mới
        $sqlInsert = "INSERT INTO flowers (name, description, image) VALUES ('$name', '$description', '$imageName')";
        if (mysqli_query($conn, $sqlInsert)) {
            $_SESSION['flash'] = '✅ Thêm mới thành công!';
            $_SESSION['flash_type'] = 'success';
        } else {
            $_SESSION['flash'] = '❌ Lỗi: ' . mysqli_error($conn);
            $_SESSION['flash_type'] = 'error';
        }
    }
    
    header('Location: admin.php');
    exit;
}

// --- BƯỚC 3: CHUẨN BỊ DỮ LIỆU CHO FORM SỬA ---
$editId = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$editItem = null;

if ($editId > 0) {
    $sqlEdit = "SELECT * FROM flowers WHERE id = $editId";
    $resultEdit = mysqli_query($conn, $sqlEdit);
    if ($resultEdit && $row = mysqli_fetch_assoc($resultEdit)) {
        $editItem = $row;
    }
}
?>
<! doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>14 loại hoa tuyệt đẹp thích hợp trồng để khoe hương sắc dịp xuân hè</title>
    
    <style>
        /* ===== RESET & CƠ BẢN ===== */
        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            /* Gradient nền tối sang trọng */
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            padding: 20px;
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* ===== HEADER ===== */
        . header {
            background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
            padding: 25px 30px;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(233, 69, 96, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .header h1 {
            color: #fff;
            font-size: 1.5rem;
        }
        
        .header a {
            background: rgba(255,255,255,0.2);
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .header a:hover {
            background: #fff;
            color: #e94560;
        }

        /* ===== THÔNG BÁO FLASH ===== */
        . flash {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
            animation: slideIn 0.5s ease;
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .flash.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: #fff;
            box-shadow: 0 5px 20px rgba(17, 153, 142, 0.3);
        }
        
        .flash. error {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: #fff;
        }

        /* ===== THỐNG KÊ ===== */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            color: #fff;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }
        
        .stat-card . icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .stat-card . number {
            font-size: 2rem;
            font-weight: bold;
        }
        
        .stat-card .label {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        /* ===== FORM SECTION ===== */
        .form-section {
            background: rgba(255,255,255,0.95);
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        
        .form-section h2 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            font-size: 1. 4rem;
        }
        
        .form-row {
            margin-bottom: 18px;
        }
        
        .form-row label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-row input[type="text"],
        .form-row textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-row input[type="text"]:focus,
        .form-row textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .form-row textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-row input[type="file"] {
            padding: 10px;
            background: #f8f9fa;
            border: 2px dashed #ddd;
            border-radius: 12px;
            width: 100%;
            cursor: pointer;
        }

        /* ===== BUTTONS ===== */
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: #fff;
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: #fff;
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
            color: #fff;
        }

        /* ===== PREVIEW ẢNH ===== */
        .img-preview {
            width: 150px;
            height: 100px;
            border: 3px dashed #ddd;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #999;
            overflow: hidden;
        }
        
        .img-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ===== TABLE SECTION ===== */
        . table-section {
            background: rgba(255,255,255,0.95);
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow-x: auto;
        }
        
        .table-section h2 {
            background: linear-gradient(135deg, #e94560 0%, #ff6b6b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            font-size: 1.4rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        th:first-child { border-radius: 12px 0 0 0; }
        th:last-child { border-radius: 0 12px 0 0; }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }
        
        td img {
            width: 80px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .actions .btn {
            padding: 8px 15px;
            font-size: 12px;
        }

        /* ===== EMPTY STATE ===== */
        . empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        
        .empty-state . icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        /* ===== FOOTER ===== */
        .footer {
            text-align: center;
            padding: 20px;
            margin-top: 30px;
            color: rgba(255,255,255,0.7);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }
            . header h1 {
                font-size: 1.2rem;
            }
            . stats {
                grid-template-columns: 1fr 1fr;
            }
            .actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- ===== HEADER ===== -->
        <div class="header">
            <h1>🔧 Quản trị danh sách hoa (CRUD)</h1>
            <a href="index.php">🌸 Xem trang khách</a>
        </div>

        <!-- ===== THÔNG BÁO FLASH ===== -->
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="flash <?php echo $_SESSION['flash_type'] ??  'success'; ?>">
                <?php 
                    echo htmlspecialchars($_SESSION['flash']); 
                    unset($_SESSION['flash']);
                    unset($_SESSION['flash_type']);
                ?>
            </div>
        <?php endif; ?>

        <!-- ===== THỐNG KÊ ===== -->
        <div class="stats">
            <div class="stat-card">
                <div class="icon">🌺</div>
                <div class="number"><?php echo count($flowers); ?></div>
                <div class="label">Tổng số hoa</div>
            </div>
            <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                <div class="icon">📁</div>
                <div class="number"><?php echo is_dir($imageDir) ? count(glob($imageDir . "*. {jpg,jpeg,png,gif}", GLOB_BRACE)) : 0; ?></div>
                <div class="label">Ảnh trong images/</div>
            </div>
        </div>

        <!-- ===== FORM THÊM/SỬA ===== -->
        <div class="form-section">
            <h2><?php echo $editItem ? '✏️ Sửa hoa' : '➕ Thêm hoa mới'; ?></h2>
            
            <form method="post" enctype="multipart/form-data" action="admin.php?action=save">
                <input type="hidden" name="id" value="<?php echo $editItem ? $editItem['id'] : ''; ?>">
                
                <div class="form-row">
                    <label for="name">🌷 Tên hoa</label>
                    <input type="text" id="name" name="name" required
                           value="<?php echo htmlspecialchars($editItem['name'] ?? ''); ?>"
                           placeholder="Nhập tên hoa... ">
                </div>
                
                <div class="form-row">
                    <label for="description">📝 Mô tả</label>
                    <textarea id="description" name="description" required
                              placeholder="Nhập mô tả về loại hoa..."><?php echo htmlspecialchars($editItem['description'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-row">
                    <label for="image_file">📷 Chọn ảnh (upload)</label>
                    <input type="file" id="image_file" name="image_file" accept="image/*">
                </div>
                
                <div class="form-row">
                    <label for="current_image">🖼️ Tên file ảnh hiện tại</label>
                    <input type="text" id="current_image" name="current_image" 
                           value="<?php echo htmlspecialchars($editItem['image'] ?? ''); ?>"
                           placeholder="vd: rose.jpg">
                </div>
                
                <div class="form-row">
                    <label>👁️ Preview</label>
                    <div class="img-preview">
                        <?php if ($editItem && ! empty($editItem['image'])): ?>
                            <img src="images/<?php echo htmlspecialchars($editItem['image']); ?>" alt="preview">
                        <?php else: ?>
                            📷 Chưa có ảnh
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="form-row">
                    <button type="submit" class="btn btn-primary">💾 Lưu</button>
                    <a href="admin.php" class="btn btn-secondary">🔄 Reset form</a>
                </div>
            </form>
        </div>

        <!-- ===== BẢNG DANH SÁCH ===== -->
        <div class="table-section">
            <h2>📋 Danh sách hoa</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Mô tả</th>
                        <th>Ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($flowers)): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="icon">🌱</div>
                                    <p>Chưa có dữ liệu.  Hãy thêm hoa mới!</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $stt = 0;
                        foreach ($flowers as $f): 
                            $stt++;
                        ?>
                            <tr>
                                <td><strong><?php echo $stt; ?></strong></td>
                                <td><?php echo htmlspecialchars($f['name'] ?? ''); ?></td>
                                <td>
                                    <?php 
                                        $desc = $f['description'] ?? '';
                                        if (mb_strlen($desc) > 50) {
                                            echo htmlspecialchars(mb_substr($desc, 0, 50)) . '...';
                                        } else {
                                            echo htmlspecialchars($desc);
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php if (!empty($f['image'])): ?>
                                        <img src="images/<?php echo htmlspecialchars($f['image']); ?>" 
                                             alt="<?php echo htmlspecialchars($f['name'] ?? ''); ?>">
                                    <?php else: ?>
                                        <span style="color:#999;">📷 Không có</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="admin.php?edit=<?php echo $f['id']; ?>" class="btn btn-warning">✏️ Sửa</a>
                                        <a href="admin.php?action=delete&id=<?php echo $f['id']; ?>" 
                                           class="btn btn-danger"
                                           onclick="return confirm('⚠️ Bạn có chắc muốn xóa hoa này?');">🗑️ Xóa</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ===== FOOTER ===== -->
        <div class="footer">
            <p>💾 Database: <strong>baitap_web.flowers</strong> | 🖼️ Ảnh: <strong>images/</strong></p>
        </div>
    </div>
</body>
</html>
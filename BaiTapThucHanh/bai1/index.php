<?php
// ============================================================
// index.php - TRANG KHÁCH XEM DANH SÁCH HOA
// ============================================================
// Chức năng: Kết nối database và hiển thị danh sách hoa
// ============================================================

// --- BƯỚC 1: Kết nối database ---
require_once('../config.php');  // Include file kết nối database

$flowers = [];  // Mảng rỗng để chứa danh sách hoa

// Truy vấn lấy danh sách hoa từ database
$sql = "SELECT * FROM flowers ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

// Kiểm tra và lấy dữ liệu
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $flowers[] = $row;
    }
}
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>14 loại hoa tuyệt đẹp</title>
    
    <style>
        /* ===== RESET & CƠ BẢN ===== */
        * { 
            box-sizing: border-box; 
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            /* Gradient nền từ hồng nhạt sang tím nhạt */
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 50%, #ee9ca7 100%);
            min-height: 100vh;
            padding: 20px;
        }

        /* ===== CONTAINER ===== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ===== HEADER ===== */
        .header {
            /* Gradient xanh dương sang tím */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
            text-align: center;
        }
        
        .header h1 {
            color: #fff;
            font-size: 1.8rem;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 1rem;
            margin-bottom: 15px;
        }
        
        .header a {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            color: #fff;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
            border: 2px solid rgba(255,255,255,0.3);
        }
        
        .header a:hover {
            background: #fff;
            color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }

        /* ===== THỐNG KÊ ===== */
        .stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .stat-box {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            padding: 20px 40px;
            border-radius: 15px;
            text-align: center;
            color: #fff;
            box-shadow: 0 8px 25px rgba(17, 153, 142, 0.3);
        }
        
        .stat-box . number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        
        .stat-box .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* ===== LƯỚI THẺ HOA ===== */
        . card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        /* ===== THẺ HOA ===== */
        .card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            position: relative;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        }
        
        /* Badge số thứ tự */
        . card . badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            box-shadow: 0 4px 15px rgba(240, 147, 251, 0.5);
        }
        
        .card img {
            width: 100%;
            height: 250px;
            object-fit: contain;
            background-color: #f8f9fa;
            transition: transform 0.5s ease;
        }
        
        .card:hover img {
            transform: scale(1. 1);
        }
        
        .card-body {
            padding: 20px;
        }
        
        .card-body h3 {
            color: #5a189a;
            font-size: 1.3rem;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .card-body p {
            color: #333;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        
        /* Nút xem chi tiết */
        .card-body .btn-detail {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .card-body .btn-detail:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        /* ===== KHÔNG CÓ DỮ LIỆU ===== */
        .no-data {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            padding: 60px;
            text-align: center;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .no-data . icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }
        
        .no-data h2 {
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .no-data p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .no-data a {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0. 3s ease;
        }
        
        .no-data a:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        /* ===== FOOTER ===== */
        . footer {
            text-align: center;
            padding: 30px;
            margin-top: 40px;
            color: #666;
            background: rgba(255,255,255,0.5);
            border-radius: 15px;
        }
        
        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.4rem;
            }
            . stats {
                flex-direction: column;
                align-items: center;
            }
            .card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- ===== HEADER ===== -->
        <div class="header">
            <h1>🌸 14 loại hoa tuyệt đẹp thích hợp trồng dịp xuân hè 🌺</h1>
            <p>Khám phá vẻ đẹp của các loại hoa tuyệt vời nhất cho khu vườn của bạn</p>
            <a href="admin.php">🔧 Chuyển sang trang quản trị (CRUD)</a>
        </div>

        <!-- ===== THỐNG KÊ ===== -->
        <div class="stats">
            <div class="stat-box">
                <div class="number"><?php echo count($flowers); ?></div>
                <div class="label">Loại hoa</div>
            </div>
        </div>

        <!-- ===== DANH SÁCH HOA ===== -->
        <?php if (empty($flowers)): ?>
            <!-- Nếu không có dữ liệu -->
            <div class="no-data">
                <div class="icon">🌷</div>
                <h2>Chưa có dữ liệu hoa</h2>
                <p>Hãy thêm các loại hoa đẹp vào bộ sưu tập của bạn!</p>
                <a href="admin.php">+ Thêm hoa mới</a>
            </div>
        <?php else: ?>
            <!-- Hiển thị lưới các thẻ hoa -->
            <div class="card-grid">
                <?php 
                // Duyệt qua từng phần tử trong mảng $flowers
                $stt = 0;
                foreach ($flowers as $flower): 
                    $stt++;
                    // Lấy các thông tin của hoa
                    $name = $flower['name'] ?? '';
                    $desc = $flower['description'] ?? '';
                    $image = $flower['image'] ?? '';
                ?>
                    <div class="card">
                        <!-- Badge số thứ tự -->
                        <div class="badge"><?php echo $stt; ?></div>
                        
                        <!-- Ảnh hoa -->
                        <img src="images/<?php echo htmlspecialchars($image); ?>" 
                             alt="<?php echo htmlspecialchars($name); ?>"
                             onerror="this.src='https://via.placeholder.com/280x200/667eea/fff? text=🌸'">
                        
                        <div class="card-body">
                            <!-- Tên hoa -->
                            <h3><?php echo htmlspecialchars($name); ?></h3>
                            <!-- Mô tả -->
                            <p><?php echo htmlspecialchars($desc); ?></p>
                            <!-- Nút xem chi tiết -->
                            <a href="#" class="btn-detail">Xem chi tiết →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- ===== FOOTER ===== -->
        <div class="footer">
            <p>🌻 Đặt ảnh trong thư mục <strong>images/</strong> | Dữ liệu lưu trong database <strong>baitap_web.flowers</strong></p>
            <p>Made with ❤️ by <a href="admin.php">Admin</a></p>
        </div>
    </div>
</body>
</html>
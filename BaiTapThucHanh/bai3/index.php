<?php
/**
 * BÀI 03: HIỂN THỊ DỮ LIỆU SINH VIÊN TỪ DATABASE
 * 
 * Chương trình đơn giản để:
 * - Kết nối database và lấy dữ liệu sinh viên
 * - Hiển thị dữ liệu dưới dạng bảng
 * - Tìm kiếm, xuất JSON
 */

// BƯỚC 1: Kết nối database
require_once('../config.php');  // Include file kết nối database

$headers = ['username', 'password', 'lastname', 'firstname', 'city', 'email', 'course1'];  // Tên các cột
$rows = [];         // Mảng chứa dữ liệu sinh viên
$message = '';      // Thông báo
$sqlText = '';      // Chuỗi chứa các câu lệnh SQL
$csvFile = __DIR__ . '/65HTTT_Danh_sach_diem_danh.csv';  // Đường dẫn file CSV (để hiển thị nội dung thô)

// BƯỚC 2: Truy vấn lấy danh sách sinh viên từ database
$sql = "SELECT * FROM students ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

// BƯỚC 3: Kiểm tra và lấy dữ liệu
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    $message = 'Đã tải ' . count($rows) . ' sinh viên từ database';
} else {
    $message = 'Không có dữ liệu trong database. Vui lòng import file database.sql';
}

// BƯỚC 4: Tạo SQL nếu người dùng nhấn nút "Sinh SQL INSERT"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_sql']) && !empty($rows)) {
    // Tạo câu INSERT cho mỗi dòng
    $sqlStatements = [];
    foreach ($rows as $row) {
        $username = mysqli_real_escape_string($conn, $row['username']);
        $password = mysqli_real_escape_string($conn, $row['password']);
        $lastname = mysqli_real_escape_string($conn, $row['lastname']);
        $firstname = mysqli_real_escape_string($conn, $row['firstname']);
        $city = mysqli_real_escape_string($conn, $row['city']);
        $email = mysqli_real_escape_string($conn, $row['email']);
        $course1 = mysqli_real_escape_string($conn, $row['course1']);
        
        $sqlStatements[] = "INSERT INTO students (username, password, lastname, firstname, city, email, course1) VALUES ('$username', '$password', '$lastname', '$firstname', '$city', '$email', '$course1');";
    }
    
    $sqlText = implode("\n", $sqlStatements);
}
?>
<!doctype html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Bài 03 — Đọc tệp CSV (Danh sách điểm danh)</title>
<style>
  :root{
    --bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --card:#fff;
    --accent: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --accent-hover: linear-gradient(135deg, #5568d3 0%, #62408a 100%);
    --success:#10b981;
    --danger:#ef4444;
    --warning:#f59e0b;
    --muted:#64748b;
    --border:#e2e8f0;
  }
  *{box-sizing:border-box}
  body{
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    background:var(--bg);
    margin:0;
    padding:20px;
    color:#1e293b;
    min-height:100vh;
  }
  .wrap{max-width:1200px;margin:0 auto}
  .card{
    background:var(--card);
    padding:24px;
    border-radius:16px;
    border:none;
    box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
  }
  h1{
    margin:0 0 12px;
    font-size:1.8rem;
    background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    background-clip:text;
    font-weight:700;
  }
  .note{
    color:var(--muted);
    margin-bottom:16px;
    padding:12px 16px;
    background:#f1f5f9;
    border-radius:8px;
    border-left:4px solid #667eea;
  }
  .note strong{color:#667eea}
  form.inline{display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap}
  button{
    background:var(--accent);
    color:#fff;
    border:0;
    padding:10px 20px;
    border-radius:8px;
    cursor:pointer;
    font-weight:600;
    transition:all 0.3s ease;
    box-shadow:0 4px 6px rgba(102,126,234,0.3);
  }
  button:hover{
    background:var(--accent-hover);
    transform:translateY(-2px);
    box-shadow:0 6px 12px rgba(102,126,234,0.4);
  }
  button:active{transform:translateY(0)}
  .msg{
    margin:12px 0;
    padding:12px 16px;
    background:#d1fae5;
    color:#065f46;
    border-radius:8px;
    border-left:4px solid var(--success);
    font-weight:500;
  }
  .toolbar{
    display:flex;
    gap:10px;
    align-items:center;
    margin-top:16px;
    flex-wrap:wrap;
    padding:12px;
    background:#f8fafc;
    border-radius:8px;
  }
  .search{
    padding:10px 16px;
    border:2px solid var(--border);
    border-radius:8px;
    transition:all 0.3s ease;
    font-size:14px;
  }
  .search:focus{
    outline:none;
    border-color:#667eea;
    box-shadow:0 0 0 3px rgba(102,126,234,0.1);
  }
  .table-wrap{
    overflow:auto;
    margin-top:16px;
    border-radius:12px;
    box-shadow:0 4px 6px rgba(0,0,0,0.05);
  }
  table{
    border-collapse:collapse;
    width:100%;
    min-width:900px;
    background:#fff;
  }
  th, td{
    padding:12px 16px;
    text-align:left;
    border-bottom:1px solid #f1f5f9;
  }
  th{
    background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color:#fff;
    position:sticky;
    top:0;
    z-index:2;
    font-weight:600;
    text-transform:uppercase;
    font-size:0.85rem;
    letter-spacing:0.5px;
  }
  tbody tr{transition:all 0.2s ease}
  tbody tr:hover{
    background:#f8fafc;
    transform:scale(1.01);
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
  }
  tr:nth-child(even){background:#fafcff}
  tr:nth-child(even):hover{background:#f8fafc}
  pre.sql{
    background:linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color:#e2e8f0;
    padding:16px;
    border-radius:12px;
    overflow:auto;
    max-height:400px;
    box-shadow:0 4px 6px rgba(0,0,0,0.1);
    border:1px solid #334155;
  }
  .raw{
    background:linear-gradient(135deg, #0f172a 0%, #020617 100%);
    color:#cbd5e1;
    padding:16px;
    border-radius:12px;
    white-space:pre-wrap;
    margin-top:16px;
    box-shadow:0 4px 6px rgba(0,0,0,0.1);
    border:1px solid #1e293b;
  }
  .small{
    font-size:0.9rem;
    color:var(--muted);
    font-weight:600;
    background:#fff;
    padding:6px 12px;
    border-radius:6px;
    margin-left:auto;
  }
  h3{
    color:#334155;
    font-size:1.2rem;
    margin-top:24px;
    margin-bottom:12px;
    padding-bottom:8px;
    border-bottom:2px solid #667eea;
  }
  @media(max-width:800px){ 
    table{min-width:700px}
    body{padding:10px}
    .card{padding:16px}
  }
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Bài 03: Hiển thị danh sách sinh viên từ Database</h1>
      <div class="note">Dữ liệu được lấy từ database <strong>baitap_web.students</strong>. Hãy chạy file <strong>database.sql</strong> để tạo database.</div>

      <?php if ($message): ?><div class="msg"><?= htmlspecialchars($message) ?></div><?php endif; ?>

      <?php if (empty($headers) || empty($rows)): ?>
        <div class="small">Không có dữ liệu để hiển thị. Hãy chắc rằng file CSV tồn tại và có dòng tiêu đề.</div>
        <?php if (file_exists($csvFile)): ?>
          <h3>Nội dung thô (CSV)</h3>
          <div class="raw"><?= htmlspecialchars(file_get_contents($csvFile)) ?></div>
        <?php endif; ?>
      <?php else: ?>
        <div class="toolbar">
          <input id="q" class="search" placeholder="Tìm theo username, firstname, lastname, city, email..." />
          <form method="post" style="display:inline;margin:0">
            <button type="submit" name="generate_sql" value="1">Sinh SQL INSERT</button>
          </form>
          <button onclick="downloadJSON()">Tải JSON</button>
          <button onclick="copyTable()">Sao chép bảng</button>
          <div class="small" style="margin-left:auto"><?= count($rows) ?> bản ghi</div>
        </div>

        <div class="table-wrap" id="table-wrap">
          <table id="csv-table" cellpadding="0" cellspacing="0" border="0">
            <thead>
              <tr>
                <?php foreach ($headers as $h): ?><th><?= htmlspecialchars($h) ?></th><?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $r): ?>
                <tr>
                  <?php foreach ($headers as $h): ?>
                    <td><?= htmlspecialchars(isset($r[$h]) ? $r[$h] : '') ?></td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <?php if ($sqlText !== ''): ?>
          <h3 style="margin-top:12px">SQL INSERT (mẫu)</h3>
          <pre class="sql"><?= htmlspecialchars($sqlText) ?></pre>
        <?php endif; ?>

        <?php if (file_exists($csvFile)): ?>
          <h3 style="margin-top:12px">Nội dung thô (CSV)</h3>
          <div class="raw"><?= htmlspecialchars(file_get_contents($csvFile)) ?></div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>

<script>
// ===================================
// 1. CHỨC NĂNG TÌM KIẾM
// ===================================
document.addEventListener('DOMContentLoaded', function() {
    var searchBox = document.getElementById('q');
    if (!searchBox) return;
    
    // Khi người dùng gõ vào ô tìm kiếm
    searchBox.addEventListener('input', function() {
        var keyword = this.value.trim().toLowerCase();  // Lấy từ khóa
        var allRows = document.querySelectorAll('#csv-table tbody tr');  // Lấy tất cả dòng
        
        // Duyệt qua từng dòng
        allRows.forEach(function(row) {
            if (!keyword) {
                row.style.display = '';  // Hiện tất cả nếu không có từ khóa
            } else {
                var rowText = row.textContent.toLowerCase();
                // Ẩn dòng nếu không chứa từ khóa
                row.style.display = rowText.indexOf(keyword) === -1 ? 'none' : '';
            }
        });
    });
});

// ===================================
// 2. CHỨC NĂNG TẢI JSON
// ===================================
function downloadJSON() {
    // Lấy tên các cột
    var headers = [];
    document.querySelectorAll('#csv-table thead th').forEach(function(th) {
        headers.push(th.textContent.trim());
    });
    
    // Lấy dữ liệu từng dòng
    var data = [];
    document.querySelectorAll('#csv-table tbody tr').forEach(function(tr) {
        var row = {};
        var cells = tr.querySelectorAll('td');
        for (var i = 0; i < headers.length; i++) {
            row[headers[i]] = cells[i] ? cells[i].textContent : '';
        }
        data.push(row);
    });
    
    // Tạo file JSON và download
    var json = JSON.stringify(data, null, 2);
    var link = document.createElement('a');
    link.href = "data:text/json;charset=utf-8," + encodeURIComponent(json);
    link.download = 'data.json';
    link.click();
}

// ===================================
// 3. CHỨC NĂNG SAO CHÉP BẢNG
// ===================================
function copyTable() {
    var table = document.getElementById('csv-table');
    if (!table) return;
    
    // Lấy header
    var text = '';
    table.querySelectorAll('thead th').forEach(function(th) {
        text += th.textContent + '\t';
    });
    text += '\n';
    
    // Lấy dữ liệu
    table.querySelectorAll('tbody tr').forEach(function(tr) {
        tr.querySelectorAll('td').forEach(function(td) {
            text += td.textContent + '\t';
        });
        text += '\n';
    });
    
    // Copy vào clipboard
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(
            function() { alert('Đã sao chép!'); },
            function() { alert('Lỗi khi sao chép.'); }
        );
    } else {
        prompt('Nhấn Ctrl+C để sao chép:', text);
    }
}
</script>
</body>
</html>
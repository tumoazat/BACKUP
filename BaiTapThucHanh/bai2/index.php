<?php
// ============================================================
// BÀI 2: QUIZ APP - KẾT NỐI DATABASE
// ============================================================

// --- BƯỚC 1: Kết nối database ---
require_once('../config.php');  // Include file kết nối database

$questions = [];  // Mảng chứa câu hỏi
$error = '';      // Thông báo lỗi

// --- BƯỚC 2: Truy vấn lấy danh sách câu hỏi từ database ---
$sql = "SELECT * FROM quiz_questions ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

// --- BƯỚC 3: Kiểm tra và lấy dữ liệu ---
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // Chuyển đổi dữ liệu từ database thành format phù hợp
        $question = [
            'text' => $row['question'],
            'options' => [],
            'answer' => explode(',', $row['answer'])  // Tách đáp án thành mảng
        ];
        
        // Thêm các lựa chọn vào mảng options
        if (!empty($row['option_a'])) $question['options']['A'] = $row['option_a'];
        if (!empty($row['option_b'])) $question['options']['B'] = $row['option_b'];
        if (!empty($row['option_c'])) $question['options']['C'] = $row['option_c'];
        if (!empty($row['option_d'])) $question['options']['D'] = $row['option_d'];
        if (!empty($row['option_e'])) $question['options']['E'] = $row['option_e'];
        
        $questions[] = $question;
    }
} else {
    $error = 'Không tìm thấy câu hỏi trong database';
}

// Tạo rawContent để hiển thị (tương thích với giao diện cũ)
$rawContent = '';
if (!empty($questions)) {
    foreach ($questions as $i => $q) {
        $rawContent .= "Câu " . ($i + 1) . ": " . $q['text'] . "\n";
        foreach ($q['options'] as $key => $value) {
            $rawContent .= $key . ". " . $value . "\n";
        }
        $rawContent .= "ANSWER: " . implode(', ', $q['answer']) . "\n\n";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Reader</title>
    <style>
        :root {
            --bg: #071124;
            --card: #0f1726;
            --accent: #06b6d4;
            --accent2: #7c3aed;
            --muted: #9aa7b8;
            --success: #16a34a;
            --danger: #ef4444;
            --text: #e6eef6;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 20px;
            font-family: Inter, system-ui, Arial;
            background: linear-gradient(180deg, var(--bg), #081122);
            color: var(--text);
        }
        
        .wrap {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }
        
        .panel {
            background: linear-gradient(180deg, rgba(255,255,255,0.02), transparent);
            padding: 14px;
            border-radius: 12px;
            box-shadow: 0 8px 28px rgba(0,0,0,0.6);
            flex: 1;
            min-width: 320px;
        }
        
        header h1 {
            margin: 0 0 6px 0;
        }
        
        header p {
            margin: 0;
            color: var(--muted);
        }
        
        .controls {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 10px;
        }
        
        .btn {
            appearance: none;
            border: 0;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            color: #021024;
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.06);
            color: var(--text);
        }
        
        .raw pre {
            background: #061025;
            color: #dff6fb;
            padding: 12px;
            border-radius: 8px;
            max-height: 520px;
            overflow: auto;
            white-space: pre-wrap;
        }
        
        .q-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 12px;
        }
        
        .q-card {
            background: rgba(255,255,255,0.02);
            padding: 12px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.03);
        }
        
        .q-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .q-no {
            font-weight: 800;
            background: linear-gradient(90deg, #ffd36b, #ff7aa2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .q-text {
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .opts {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        .opt {
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }
        
        .opt .label {
            font-weight: 800;
            color: #cbeef2;
            margin-right: 6px;
        }
        
        .answer {
            display: none;
            margin-top: 8px;
            padding: 8px;
            border-radius: 6px;
            background: rgba(255,255,255,0.02);
            border: 1px dashed rgba(255,255,255,0.03);
        }
        
        .answer strong {
            color: var(--accent);
        }
        
        .hint {
            color: var(--muted);
            font-size: 0.95rem;
        }
        
        @media (max-width: 900px) {
            .wrap {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <header style="max-width:1100px; margin:0 auto 10px;">
        <h1>Quiz Reader</h1>
        <p class="hint">Đọc file Quiz.txt và hiển thị câu hỏi + lựa chọn. Đáp án ẩn mặc định.</p>
    </header>

    <div class="wrap">
        <section class="panel">
            <?php if ($error): ?>
                <div style="padding:10px; background:rgba(239,68,68,0.06); border-radius:8px; color:var(--danger)">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="controls">
                <button id="toggleAnswers" class="btn btn-primary">Hiển thị đáp án</button>
                <a href="Quiz.txt" class="btn btn-outline" download>Tải file gốc</a>
            </div>

            <?php if (count($questions) > 0): ?>
                <div class="q-list">
                    <?php foreach ($questions as $i => $q): ?>
                        <article class="q-card">
                            <div class="q-head">
                                <div class="q-no">Câu <?php echo $i + 1; ?></div>
                                <div class="q-meta">
                                    <small class="hint">
                                        <?php echo count($q['options']); ?> lựa chọn
                                    </small>
                                </div>
                            </div>
                            
                            <div class="q-text">
                                <?php echo htmlspecialchars($q['text']); ?>
                            </div>
                            
                            <div class="opts">
                                <?php 
                                ksort($q['options']);
                                foreach ($q['options'] as $label => $txt): 
                                ?>
                                    <div class="opt">
                                        <span class="label"><?php echo htmlspecialchars($label); ?>.</span>
                                        <span class="txt"><?php echo htmlspecialchars($txt); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="answer">
                                <strong>ANSWER:</strong> 
                                <?php 
                                if (isset($q['answer'])) {
                                    echo htmlspecialchars(implode(', ', $q['answer']));
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="margin-top:12px; color:var(--muted)">
                    Không tìm thấy câu hỏi trong Quiz.txt
                </div>
            <?php endif; ?>
        </section>

        <aside class="panel raw">
            <h3>Nội dung gốc (Quiz.txt)</h3>
            <pre><?php echo htmlspecialchars($rawContent ?: 'Chưa có nội dung...'); ?></pre>
        </aside>
    </div>

    <script>
        // Nút bật/tắt hiển thị đáp án
        const toggleBtn = document.getElementById('toggleAnswers');
        let answersShown = false;
        
        toggleBtn.addEventListener('click', function() {
            answersShown = !answersShown;
            
            document.querySelectorAll('.answer').forEach(function(answer) {
                answer.style.display = answersShown ? 'block' : 'none';
            });
            
            toggleBtn.textContent = answersShown ? 'Ẩn đáp án' : 'Hiển thị đáp án';
        });
    </script>
</body>
</html>
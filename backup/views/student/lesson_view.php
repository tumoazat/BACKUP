<?php
$tiêu_đề = "Bài học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';
?>

<div class="container">
    <h1><?php echo htmlspecialchars($bài_học['title']); ?></h1>
    <p><strong>Khóa học:</strong> <?php echo htmlspecialchars($khóa_học['title']); ?></p>
    
    <?php if (!empty($bài_học['video_url'])): ?>
        <div style="margin: 2rem 0;">
            <h3>Video bài học</h3>
            <p>Video URL: <a href="<?php echo htmlspecialchars($bài_học['video_url']); ?>" target="_blank"><?php echo htmlspecialchars($bài_học['video_url']); ?></a></p>
        </div>
    <?php endif; ?>
    
    <div style="margin: 2rem 0;">
        <h3>Nội dung bài học</h3>
        <div style="background: white; padding: 2rem; border-radius: 8px;">
            <?php echo nl2br(htmlspecialchars($bài_học['content'])); ?>
        </div>
    </div>
    
    <?php if (!empty($danh_sách_tài_liệu)): ?>
        <div style="margin: 2rem 0;">
            <h3>Tài liệu học tập</h3>
            <ul>
                <?php foreach ($danh_sách_tài_liệu as $tài_liệu): ?>
                    <li>
                        <?php echo htmlspecialchars($tài_liệu['filename']); ?> 
                        (<?php echo htmlspecialchars($tài_liệu['file_type']); ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div style="margin: 2rem 0;">
        <h3>Danh sách bài học</h3>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($danh_sách_bài_học as $bài): ?>
                <li style="padding: 0.5rem; border-bottom: 1px solid #ddd;">
                    <?php if ($bài['id'] == $bài_học['id']): ?>
                        <strong><?php echo htmlspecialchars($bài['title']); ?> (Đang xem)</strong>
                    <?php else: ?>
                        <a href="index.php?controller=lesson&action=view&id=<?php echo $bài['id']; ?>">
                            <?php echo htmlspecialchars($bài['title']); ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <a href="index.php?controller=student&action=course_progress&course_id=<?php echo $khóa_học['id']; ?>" class="btn btn-secondary">Quay lại</a>
</div>

<?php require_once 'views/layouts/footer.php'; ?>

<?php
session_start();
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header("Location: gallery.php"); exit; }

$stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$place = $stmt->get_result()->fetch_assoc();
if (!$place) { header("Location: gallery.php"); exit; }

$landmarks  = array_filter(array_map('trim', explode(',', $place['landmarks'] ?? '')));
$activities = array_filter(array_map('trim', explode(',', $place['activities'] ?? '')));
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($place['name']); ?> - اكتشف السعودية</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <a class="nav-brand" href="index.php">اكتشف السعودية</a>
    <ul class="nav-links">
        <li><a href="index.php">الرئيسية</a></li>
        <li><a href="gallery.php" class="active">معرض المناطق</a></li>
        <li><a href="admin/login.php">دخول المشرف</a></li>
        <li><button class="night-toggle" onclick="toggleNightMode()" id="nightBtn">الوضع الليلي</button></li>
    </ul>
</nav>

<div class="section">
    <div class="container">

        <a href="gallery.php" class="back-link">← العودة إلى المعرض</a>

        <div class="detail-layout">

            <!-- Main content -->
            <div>
                <?php if ($place['main_image'] && file_exists('uploads/' . $place['main_image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($place['main_image']); ?>"
                         alt="<?php echo htmlspecialchars($place['name']); ?>"
                         class="detail-main-img">
                <?php else: ?>
                    <div class="detail-main-img"></div>
                <?php endif; ?>

                <h1 class="detail-title"><?php echo htmlspecialchars($place['name']); ?></h1>

                <div class="detail-meta">
                    <span class="tag-filled"><?php echo htmlspecialchars($place['category']); ?></span>
                    <span class="region-label">منطقة <?php echo htmlspecialchars($place['region']); ?></span>
                </div>

                <div class="info-box">
                    <h3>عن المنطقة</h3>
                    <p><?php echo htmlspecialchars($place['description']); ?></p>
                </div>

                <div class="info-box">
                    <h3>معرض الصور</h3>
                    <?php
                    $galleryImgs = array_filter([
                        $place['gallery_image1'],
                        $place['gallery_image2'],
                        $place['gallery_image3']
                    ]);
                    ?>
                    <div class="gallery-grid">
                        <?php if (!empty($galleryImgs)): ?>
                            <?php foreach ($galleryImgs as $img): ?>
                                <?php if (file_exists('uploads/' . $img)): ?>
                                <img src="uploads/<?php echo htmlspecialchars($img); ?>" alt="صورة">
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="gallery-ph"></div>
                            <div class="gallery-ph"></div>
                            <div class="gallery-ph"></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <?php if (!empty($activities)): ?>
                <div class="sidebar-card">
                    <h3>معلومات سريعة</h3>
                    <ul>
                        <?php foreach ($activities as $act): ?>
                        <li><?php echo htmlspecialchars($act); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <?php if (!empty($landmarks)): ?>
                <div class="sidebar-card">
                    <h3>أبرز المعالم</h3>
                    <ul>
                        <?php foreach ($landmarks as $lm): ?>
                        <li><?php echo htmlspecialchars($lm); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<footer>
    <p>اكتشف السعودية</p>
</footer>

<script>
function toggleNightMode() {
    document.body.classList.toggle('dark-mode');
    const btn = document.getElementById('nightBtn');
    const on = document.body.classList.contains('dark-mode');
    btn.textContent = on ? 'الوضع النهاري' : 'الوضع الليلي';
    localStorage.setItem('nightMode', on ? 'on' : 'off');
}
window.onload = function() {
    if (localStorage.getItem('nightMode') === 'on') {
        document.body.classList.add('dark-mode');
        document.getElementById('nightBtn').textContent = 'الوضع النهاري';
    }
};
</script>
</body>
</html>

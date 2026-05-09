<?php
session_start();
require_once 'config.php';

$result = $conn->query("SELECT id, name, region, category, description, main_image FROM places LIMIT 3");
$places = $result->fetch_all(MYSQLI_ASSOC);
$total  = $conn->query("SELECT COUNT(*) as c FROM places")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اكتشف السعودية</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <a class="nav-brand" href="index.php">اكتشف السعودية</a>
    <ul class="nav-links">
        <li><a href="index.php" class="active">الرئيسية</a></li>
        <li><a href="gallery.php">معرض المناطق</a></li>
        <li><a href="admin/login.php">دخول المشرف</a></li>
        <li><button class="night-toggle" onclick="toggleNightMode()" id="nightBtn">الوضع الليلي</button></li>
    </ul>
</nav>

<!-- Hero -->
<div class="home-hero">
    <div class="home-hero-text">
        <h1>موقع ثقافي تفاعلي للتعريف بالمملكة</h1>
        <p>استكشف مناطق المملكة العربية السعودية وتعرّف على أهم المعالم التاريخية والثقافية.</p>
        <a href="gallery.php" class="btn">استعرض المناطق</a>
    </div>
    <div class="home-hero-side"></div>
</div>

<!-- About -->
<div class="about-strip">
    <div class="about-inner">
        <div>
            <h2>عن المملكة العربية السعودية</h2>
            <p>المملكة العربية السعودية دولة عربية تقع في قلب شبه الجزيرة العربية، وتُعدّ من أكبر دول العالم مساحةً. تتميز بتراثها الإسلامي العريق وحضارتها الإنسانية الممتدة عبر آلاف السنين. تضم المملكة أهم المواقع الإسلامية في العالم، وتزخر بالمناطق الطبيعية الساحرة والمواقع الأثرية النادرة. في إطار رؤية 2030، تسعى المملكة إلى تطوير قطاع السياحة وإبراز ثقافتها المتنوعة للعالم.</p>
        </div>
        <div>
            <div class="stat-item">
                <span class="num"><?php echo $total; ?></span>
                <span class="label">منطقة مسجّلة</span>
            </div>
            <div class="stat-item">
                <span class="num">13</span>
                <span class="label">منطقة إدارية</span>
            </div>
        </div>
    </div>
</div>

<!-- Preview -->
<?php if (!empty($places)): ?>
<div class="section">
    <div class="container">
        <h2 class="section-heading">أبرز المناطق</h2>
        <div class="places-grid">
            <?php foreach ($places as $place): ?>
            <a href="details.php?id=<?php echo $place['id']; ?>" class="place-card">
                <?php if ($place['main_image'] && file_exists('uploads/' . $place['main_image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($place['main_image']); ?>" alt="<?php echo htmlspecialchars($place['name']); ?>">
                <?php else: ?>
                    <div class="place-card-img"></div>
                <?php endif; ?>
                <div class="place-card-body">
                    <span class="tag"><?php echo htmlspecialchars($place['category']); ?></span>
                    <h3><?php echo htmlspecialchars($place['name']); ?></h3>
                    <p><?php echo mb_substr(htmlspecialchars($place['description']), 0, 90); ?>...</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="margin-top:26px;">
            <a href="gallery.php" class="btn">عرض جميع المناطق</a>
        </div>
    </div>
</div>
<?php endif; ?>

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

<?php
session_start();
require_once 'config.php';

$result = $conn->query("SELECT id, name, region, category, description, main_image FROM places ORDER BY id ASC");
$places = $result->fetch_all(MYSQLI_ASSOC);

$cats = $conn->query("SELECT DISTINCT category FROM places ORDER BY category");
$categories = $cats->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معرض المناطق - اكتشف السعودية</title>
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

<div class="page-banner">
    <h1>معرض المناطق</h1>
    <p>ابحث أو صنّف، ثم اضغط على أي منطقة للانتقال إلى صفحة التفاصيل.</p>
</div>

<div class="section">
    <div class="container">

        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="ابحث عن منطقة..." oninput="filterPlaces()">
            <select id="categoryFilter" onchange="filterPlaces()">
                <option value="">كل التصنيفات</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?php echo htmlspecialchars($cat['category']); ?>"><?php echo htmlspecialchars($cat['category']); ?></option>
                <?php endforeach; ?>
            </select>
            <span class="results-count" id="resultsCount">عدد النتائج: <?php echo count($places); ?></span>
        </div>

        <div class="places-grid" id="placesGrid">
            <?php foreach ($places as $place): ?>
            <a href="details.php?id=<?php echo $place['id']; ?>"
               class="place-card place-item"
               data-name="<?php echo htmlspecialchars($place['name']); ?>"
               data-category="<?php echo htmlspecialchars($place['category']); ?>"
               data-region="<?php echo htmlspecialchars($place['region']); ?>">
                <?php if ($place['main_image'] && file_exists('uploads/' . $place['main_image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($place['main_image']); ?>" alt="<?php echo htmlspecialchars($place['name']); ?>">
                <?php else: ?>
                    <div class="place-card-img"></div>
                <?php endif; ?>
                <div class="place-card-body">
                    <span class="tag"><?php echo htmlspecialchars($place['category']); ?></span>
                    <h3><?php echo htmlspecialchars($place['name']); ?></h3>
                    <p><?php echo mb_substr(htmlspecialchars($place['description']), 0, 85); ?>...</p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="no-results" id="noResults">
            <p>لا توجد نتائج مطابقة للبحث.</p>
        </div>

    </div>
</div>

<footer>
    <p>اكتشف السعودية</p>
</footer>

<script>
function filterPlaces() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const category = document.getElementById('categoryFilter').value;
    const items = document.querySelectorAll('.place-item');
    let count = 0;

    items.forEach(item => {
        const name = item.dataset.name.toLowerCase();
        const itemCat = item.dataset.category;
        const region = item.dataset.region.toLowerCase();
        const matchSearch = name.includes(search) || region.includes(search);
        const matchCat = !category || itemCat === category;

        if (matchSearch && matchCat) {
            item.style.display = '';
            count++;
        } else {
            item.style.display = 'none';
        }
    });

    document.getElementById('resultsCount').textContent = 'عدد النتائج: ' + count;
    document.getElementById('noResults').style.display = count === 0 ? 'block' : 'none';
}

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

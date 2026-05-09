<?php
require_once 'auth_check.php';
require_once '../config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $name        = trim($_POST['name'] ?? '');
    $region      = trim($_POST['region'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $activities  = trim($_POST['activities'] ?? '');
    $landmarks   = trim($_POST['landmarks'] ?? '');

    if (empty($name))        $errors[] = 'اسم المكان مطلوب.';
    if (empty($region))      $errors[] = 'المنطقة مطلوبة.';
    if (empty($category))    $errors[] = 'التصنيف مطلوب.';
    if (empty($description)) $errors[] = 'الوصف مطلوب.';

    if (empty($errors)) {
        // Handle image uploads
        $main_image = null;
        $gallery1 = $gallery2 = $gallery3 = null;

        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        function uploadImg($file_key, $upload_dir) {
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === 0) {
                $allowed = ['jpg','jpeg','png','gif','webp'];
                $ext = strtolower(pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $filename = uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $filename);
                    return $filename;
                }
            }
            return null;
        }

        $main_image = uploadImg('main_image', $upload_dir);
        $gallery1   = uploadImg('gallery1', $upload_dir);
        $gallery2   = uploadImg('gallery2', $upload_dir);
        $gallery3   = uploadImg('gallery3', $upload_dir);

        $stmt = $conn->prepare("INSERT INTO places (name, region, category, description, activities, landmarks, main_image, gallery_image1, gallery_image2, gallery_image3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $name, $region, $category, $description, $activities, $landmarks, $main_image, $gallery1, $gallery2, $gallery3);

        if ($stmt->execute()) {
            header("Location: dashboard.php?msg=added");
            exit;
        } else {
            $errors[] = 'حدث خطأ أثناء الحفظ، حاول مرة أخرى.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة محتوى</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<nav class="admin-nav">
    <a class="admin-nav-brand" href="dashboard.php">لوحة المشرف</a>
    <ul class="admin-nav-links">
        <li><a href="dashboard.php">لوحة التحكم</a></li>
        <li><a href="../index.php">زيارة الموقع</a></li>
        <li><a href="logout.php" class="btn-logout">تسجيل الخروج</a></li>
    </ul>
</nav>

<div class="admin-container">
    <div class="admin-card">
        <h2>إضافة مكان جديد</h2>
        <p class="subtitle">أدخل معلومات المنطقة أو المكان الجديد</p>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
            <div>• <?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group">
                    <label>*اسم المكان</label>
                    <input type="text" name="name" placeholder="مثال: الرياض" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>*التصنيف</label>
                    <select name="category" required>
                        <option value="">اختر التصنيف</option>
                        <option value="وسطى" <?= ($_POST['category'] ?? '') === 'وسطى' ? 'selected' : '' ?>>وسطى</option>
                        <option value="غربية" <?= ($_POST['category'] ?? '') === 'غربية' ? 'selected' : '' ?>>غربية</option>
                        <option value="شرقية" <?= ($_POST['category'] ?? '') === 'شرقية' ? 'selected' : '' ?>>شرقية</option>
                        <option value="شمالية" <?= ($_POST['category'] ?? '') === 'شمالية' ? 'selected' : '' ?>>شمالية</option>
                        <option value="جنوبية" <?= ($_POST['category'] ?? '') === 'جنوبية' ? 'selected' : '' ?>>جنوبية</option>
                        <option value="دينية" <?= ($_POST['category'] ?? '') === 'دينية' ? 'selected' : '' ?>>دينية</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>*منطقة</label>
                <input type="text" name="region" placeholder="اسم المنطقة" value="<?= htmlspecialchars($_POST['region'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>*الوصف</label>
                <textarea name="description" placeholder="اكتب وصفًا عامًا عن المنطقة..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>المميزات (افصل بفواصل)</label>
                    <input type="text" name="activities" placeholder="مثال: شواطئ جميلة, طبيعة خلابة" value="<?= htmlspecialchars($_POST['activities'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>الأنشطة (افصل بفواصل)</label>
                    <input type="text" name="landmarks" placeholder="مثال: مشي سياحي, ركوب الخيل" value="<?= htmlspecialchars($_POST['landmarks'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label>*المعالم الأفضل (منها أبرزها)</label>
                <input type="text" name="landmarks" placeholder="مثال: برج المملكة, قلعة تاريخية, الدرعية" value="<?= htmlspecialchars($_POST['landmarks'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>*الصورة الرئيسية للمكان</label>
                <input type="file" name="main_image" accept="image/*">
            </div>

            <div style="font-weight:600; margin-bottom:10px; color:var(--text-mid);">صور المعرض</div>
            <div class="form-row">
                <div class="form-group">
                    <label>*صورة المعرض الأول</label>
                    <input type="file" name="gallery1" accept="image/*">
                </div>
                <div class="form-group">
                    <label>صورة المعرض الثانية (اختياري)</label>
                    <input type="file" name="gallery2" accept="image/*">
                </div>
            </div>
            <div class="form-group">
                <label>صورة المعرض الثالثة (اختياري)</label>
                <input type="file" name="gallery3" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">إضافة المكان</button>
            <a href="dashboard.php" class="btn btn-cancel" style="margin-right:10px;">إلغاء</a>
        </form>
    </div>
</div>

</body>
</html>

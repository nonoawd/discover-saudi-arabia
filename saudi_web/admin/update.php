<?php
require_once 'auth_check.php';
require_once '../config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header("Location: dashboard.php"); exit; }

// Fetch existing record
$stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$place = $stmt->get_result()->fetch_assoc();
if (!$place) { header("Location: dashboard.php"); exit; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

        function uploadImgUpdate($file_key, $upload_dir, $old_file) {
            if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'] === 0) {
                $allowed = ['jpg','jpeg','png','gif','webp'];
                $ext = strtolower(pathinfo($_FILES[$file_key]['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    // Delete old
                    if ($old_file && file_exists($upload_dir . $old_file)) unlink($upload_dir . $old_file);
                    $filename = uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES[$file_key]['tmp_name'], $upload_dir . $filename);
                    return $filename;
                }
            }
            return $old_file; // keep existing
        }

        $main_image = uploadImgUpdate('main_image', $upload_dir, $place['main_image']);
        $gallery1   = uploadImgUpdate('gallery1', $upload_dir, $place['gallery_image1']);
        $gallery2   = uploadImgUpdate('gallery2', $upload_dir, $place['gallery_image2']);
        $gallery3   = uploadImgUpdate('gallery3', $upload_dir, $place['gallery_image3']);

        $stmt = $conn->prepare("UPDATE places SET name=?, region=?, category=?, description=?, activities=?, landmarks=?, main_image=?, gallery_image1=?, gallery_image2=?, gallery_image3=? WHERE id=?");
        $stmt->bind_param("ssssssssssi", $name, $region, $category, $description, $activities, $landmarks, $main_image, $gallery1, $gallery2, $gallery3, $id);

        if ($stmt->execute()) {
            header("Location: dashboard.php?msg=updated");
            exit;
        } else {
            $errors[] = 'حدث خطأ أثناء التحديث.';
        }
    }

    // Update $place with posted values for re-display
    $place['name'] = $_POST['name'];
    $place['region'] = $_POST['region'];
    $place['category'] = $_POST['category'];
    $place['description'] = $_POST['description'];
    $place['activities'] = $_POST['activities'];
    $place['landmarks'] = $_POST['landmarks'];
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث المحتوى</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<nav class="admin-nav">
    <a class="admin-nav-brand" href="dashboard.php">لوحة المشرف</a>
    <ul class="admin-nav-links">
        <li><a href="dashboard.php">لوحة التحكم</a></li>
        <li><a href="add.php">إضافة محتوى</a></li>
        <li><a href="../index.php">زيارة الموقع</a></li>
        <li><a href="logout.php" class="btn-logout">تسجيل الخروج</a></li>
    </ul>
</nav>

<div class="admin-container">
    <div class="admin-card">
        <h2>تحديث مكان</h2>
        <p class="subtitle">
            ️ تعديل البيانات  
            <a href="dashboard.php" style="color:var(--primary);">العودة للوحة التحكم</a>
        </p>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $err): ?>
            <div>• <?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div style="display:grid; grid-template-columns:1fr 220px; gap:20px; align-items:start;">
        
        <!-- Form -->
        <form method="POST" action="" enctype="multipart/form-data">
            <div style="font-weight:bold; color:var(--text-mid); margin-bottom:12px;">تعديل البيانات</div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>اسم المكان</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($place['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>التصنيف</label>
                    <select name="category" required>
                        <?php foreach (['وسطى','غربية','شرقية','شمالية','جنوبية','دينية'] as $cat): ?>
                        <option value="<?= $cat ?>" <?= $place['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>منطقة</label>
                <input type="text" name="region" value="<?= htmlspecialchars($place['region']) ?>" required>
            </div>

            <div class="form-group">
                <label>الوصف</label>
                <textarea name="description" required><?= htmlspecialchars($place['description']) ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>المميزات</label>
                    <input type="text" name="activities" value="<?= htmlspecialchars($place['activities'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>الأنشطة</label>
                    <input type="text" name="landmarks" value="<?= htmlspecialchars($place['landmarks'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label>تحديث الصورة الرئيسية (اختياري)</label>
                <input type="file" name="main_image" accept="image/*">
            </div>

            <div style="font-weight:bold; color:var(--text-mid); margin-bottom:8px;">تحديث صور المعرض (اختياري)</div>
            <div class="form-row">
                <div class="form-group">
                    <label>صورة المعرض الأول</label>
                    <input type="file" name="gallery1" accept="image/*">
                </div>
                <div class="form-group">
                    <label>صورة المعرض الثانية</label>
                    <input type="file" name="gallery2" accept="image/*">
                </div>
            </div>
            <div class="form-group">
                <label>صورة المعرض الثالثة</label>
                <input type="file" name="gallery3" accept="image/*">
            </div>

            <button type="submit" class="btn btn-success">حفظ التعديلات</button>
            <a href="dashboard.php" class="btn btn-cancel" style="margin-right:10px;">إلغاء</a>
        </form>

        <!-- Current images sidebar -->
        <div>
            <div style="font-weight:bold; color:var(--text-mid); margin-bottom:12px;">الصورة الرئيسية الحالية</div>
            <?php if ($place['main_image'] && file_exists('../uploads/' . $place['main_image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($place['main_image']) ?>" 
                     style="width:100%; border-radius:6px; margin-bottom:12px; border:1px solid var(--border);">
            <?php else: ?>
                <div style="background:var(--cream-deep); height:110px; border-radius:6px; display:flex; align-items:center; justify-content:center; color:var(--text-light); margin-bottom:12px;">لا توجد صورة</div>
            <?php endif; ?>

            <div style="font-weight:bold; color:var(--text-mid); margin-bottom:8px;">صور المعرض الحالية</div>
            <div style="display:flex; gap:6px; flex-wrap:wrap;">
                <?php foreach (['gallery_image1','gallery_image2','gallery_image3'] as $gk): ?>
                    <?php if ($place[$gk] && file_exists('../uploads/' . $place[$gk])): ?>
                    <img src="../uploads/<?= htmlspecialchars($place[$gk]) ?>" 
                         style="width:60px; height:50px; object-fit:cover; border-radius:4px; border:1px solid var(--border);">
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        </div><!-- end grid -->
    </div>
</div>

</body>
</html>

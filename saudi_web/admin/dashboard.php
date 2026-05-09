<?php
require_once 'auth_check.php';
require_once '../config.php';

// Handle delete
$message = '';
$messageType = '';

if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    // Get images to delete files
    $res = $conn->prepare("SELECT main_image, gallery_image1, gallery_image2, gallery_image3 FROM places WHERE id = ?");
    $res->bind_param("i", $del_id);
    $res->execute();
    $imgs = $res->get_result()->fetch_assoc();
    
    $stmt = $conn->prepare("DELETE FROM places WHERE id = ?");
    $stmt->bind_param("i", $del_id);
    if ($stmt->execute()) {
        // Delete image files
        foreach ($imgs as $img) {
            if ($img && file_exists('../uploads/' . $img)) {
                unlink('../uploads/' . $img);
            }
        }
        $message = 'تم حذف السجل بنجاح.';
        $messageType = 'success';
    } else {
        $message = 'حدث خطأ أثناء الحذف.';
        $messageType = 'danger';
    }
}

// Check for success messages from add/update
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') { $message = 'تمت إضافة السجل بنجاح.'; $messageType = 'success'; }
    if ($_GET['msg'] === 'updated') { $message = 'تم تحديث السجل بنجاح.'; $messageType = 'success'; }
}

// Fetch all places
$result = $conn->query("SELECT id, name, region, category, description FROM places ORDER BY id ASC");
$places = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<!-- Admin Nav -->
<nav class="admin-nav">
    <a class="admin-nav-brand" href="dashboard.php">لوحة المشرف</a>
    <ul class="admin-nav-links">
        <li><a href="../index.php">زيارة الموقع</a></li>
        <li><a href="add.php">إضافة محتوى</a></li>
        <li><a href="logout.php" class="btn-logout">تسجيل الخروج</a></li>
    </ul>
</nav>

<div class="admin-container">

    <?php if ($message): ?>
    <div class="alert alert-<?= $messageType ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <div class="admin-card">
        <h2>إدارة المحتوى</h2>
        <p class="subtitle">استخدم هذه الصفحة لإدارة محتوى الموقع من خلال عرض السجلات وإضافتها أو تعديل أو حذف المحتوى</p>

        <a href="add.php" class="btn-add">+ إضافة محتوى جديد</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>المنطقة</th>
                    <th>التصنيف</th>
                    <th>الوصف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($places as $place): ?>
                <tr>
                    <td><?= $place['id'] ?></td>
                    <td><?= htmlspecialchars($place['name']) ?></td>
                    <td><?= htmlspecialchars($place['category']) ?></td>
                    <td><?= mb_substr(htmlspecialchars($place['description']), 0, 50) ?>...</td>
                    <td>
                        <a href="update.php?id=<?= $place['id'] ?>" class="btn btn-warning">تعديل</a>
                        <button class="btn btn-danger" onclick="confirmDelete(<?= $place['id'] ?>, '<?= htmlspecialchars($place['name'], ENT_QUOTES) ?>')">حذف</button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($places)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; color:var(--text-light); padding:30px;">لا توجد سجلات حالياً</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:#FAF3E8; border:1px solid #DEC89A; border-radius:8px; padding:30px; max-width:400px; width:90%; text-align:center;">
        <h3 style="margin-bottom:15px; color:#5C3A1E;">هل تريد حذف هذا السجل؟</h3>
        <p id="deleteMsg" style="color:#9A7A5A; margin-bottom:25px;"></p>
        <div style="display:flex; gap:10px; justify-content:center;">
            <a id="confirmDeleteBtn" href="#" class="btn btn-danger">نعم، احذف</a>
            <button onclick="closeModal()" class="btn btn-cancel">إلغاء</button>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteMsg').textContent = 'هل أنت متأكد من حذف "' + name + '"؟';
    document.getElementById('confirmDeleteBtn').href = 'dashboard.php?delete_id=' + id;
    const modal = document.getElementById('deleteModal');
    modal.style.display = 'flex';
}

function closeModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>

</body>
</html>

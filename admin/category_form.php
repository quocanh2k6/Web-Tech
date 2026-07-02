<?php
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$message = '';
$error = '';

$category = [
    'name' => '',
    'description' => '',
    'is_active' => 1
];

if ($isEdit) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $existing = $stmt->fetch();
    if ($existing) {
        $category = array_merge($category, $existing);
    } else {
        die("Không tìm thấy danh mục.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($name)) {
        $error = "Tên danh mục không được để trống.";
    } else {
        try {
            if ($isEdit) {
                // Check duplicate name
                $check = $conn->prepare("SELECT id FROM categories WHERE name = :name AND id != :id");
                $check->execute([':name' => $name, ':id' => $id]);
                if ($check->fetch()) {
                    $error = "Tên danh mục đã tồn tại.";
                } else {
                    $stmtUpdate = $conn->prepare("UPDATE categories SET name = :name, description = :description, is_active = :is_active WHERE id = :id");
                    $stmtUpdate->execute([
                        ':name' => $name,
                        ':description' => $description,
                        ':is_active' => $is_active,
                        ':id' => $id
                    ]);
                    $message = "Cập nhật danh mục thành công!";
                    $category['name'] = $name;
                    $category['description'] = $description;
                    $category['is_active'] = $is_active;
                }
            } else {
                // Check duplicate name
                $check = $conn->prepare("SELECT id FROM categories WHERE name = :name");
                $check->execute([':name' => $name]);
                if ($check->fetch()) {
                    $error = "Tên danh mục đã tồn tại.";
                } else {
                    $stmtInsert = $conn->prepare("INSERT INTO categories (name, description, is_active) VALUES (:name, :description, :is_active)");
                    $stmtInsert->execute([
                        ':name' => $name,
                        ':description' => $description,
                        ':is_active' => $is_active
                    ]);
                    // Redirect to avoid resubmission
                    echo "<script>window.location.href='categories.php';</script>";
                    exit;
                }
            }
        } catch (PDOException $e) {
            // Note: If columns description/is_active don't exist yet, we catch error
            $error = "Có lỗi xảy ra. Hãy chắc chắn bạn đã chạy lệnh cập nhật SQL cho bảng categories (Thêm cột description và is_active). Lỗi chi tiết: " . $e->getMessage();
        }
    }
}
?>

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-4">
        <a href="categories.php" class="text-gray-500 hover:text-brand-primary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800"><?= $isEdit ? 'Sửa Danh mục' : 'Thêm Danh mục mới' ?></h1>
    </div>
</div>

<?php if ($message): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-100 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form method="POST" action="">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required 
                       class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                <textarea name="description" rows="3" 
                          class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary"><?= htmlspecialchars($category['description']) ?></textarea>
            </div>
            
            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= $category['is_active'] ? 'checked' : '' ?>
                       class="w-4 h-4 text-brand-primary bg-gray-100 border-gray-300 rounded focus:ring-brand-primary">
                <label for="is_active" class="text-sm font-medium text-gray-700">Trạng thái hiển thị</label>
            </div>
            
            <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
                <a href="categories.php" class="px-5 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg transition-colors">
                    Hủy
                </a>
                <button type="submit" class="px-5 py-2.5 bg-brand-primary hover:bg-brand-secondary text-white font-medium rounded-lg transition-colors">
                    <?= $isEdit ? 'Lưu thay đổi' : 'Thêm danh mục' ?>
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

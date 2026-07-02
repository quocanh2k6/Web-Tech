<?php
require_once __DIR__ . '/includes/header.php';

// Handle deletion
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    // Check if category has products
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE category_id = :id");
    $checkStmt->execute([':id' => $deleteId]);
    if ($checkStmt->fetchColumn() > 0) {
        $message = "Không thể xóa danh mục đang có sản phẩm. Vui lòng chuyển sản phẩm sang danh mục khác trước.";
    } else {
        $stmtDelete = $conn->prepare("DELETE FROM categories WHERE id = :id");
        if ($stmtDelete->execute([':id' => $deleteId])) {
            $message = "Xóa danh mục thành công!";
        }
    }
}

// Fetch categories
$stmt = $conn->query("
    SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as product_count 
    FROM categories c 
    ORDER BY c.id DESC
");
$categories = $stmt->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Quản lý Danh mục</h1>
    <a href="category_form.php" class="bg-brand-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-brand-secondary transition-colors shadow-sm flex items-center gap-2">
        <i class="fas fa-plus"></i> Thêm danh mục
    </a>
</div>

<?php if ($message): ?>
    <div class="<?= strpos($message, 'Không thể') !== false ? 'bg-red-50 text-red-700 border-red-100' : 'bg-green-50 text-green-700 border-green-100' ?> p-4 rounded-lg mb-6 border flex items-center gap-2">
        <i class="fas <?= strpos($message, 'Không thể') !== false ? 'fa-exclamation-circle' : 'fa-check-circle' ?>"></i>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-sm">
                    <th class="py-4 px-6 font-semibold w-16">ID</th>
                    <th class="py-4 px-6 font-semibold">Tên Danh mục</th>
                    <th class="py-4 px-6 font-semibold">Số sản phẩm</th>
                    <th class="py-4 px-6 font-semibold">Trạng thái</th>
                    <th class="py-4 px-6 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (count($categories) > 0): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-sm text-gray-500"><?= $cat['id'] ?></td>
                            <td class="py-4 px-6">
                                <div class="font-medium text-gray-900"><?= htmlspecialchars($cat['name']) ?></div>
                                <?php if (!empty($cat['description'])): ?>
                                    <div class="text-xs text-gray-500 truncate max-w-xs"><?= htmlspecialchars($cat['description']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600"><?= $cat['product_count'] ?></td>
                            <td class="py-4 px-6">
                                <?php if (!isset($cat['is_active']) || $cat['is_active'] == 1): ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Hiển thị</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Ẩn</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="category_form.php?id=<?= $cat['id'] ?>" class="text-blue-500 hover:text-blue-700" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" class="inline-block">
                                        <input type="hidden" name="delete_id" value="<?= $cat['id'] ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">Chưa có danh mục nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

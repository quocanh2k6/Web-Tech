<?php
require_once __DIR__ . '/includes/header.php';

// Handle deletion
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    try {
        $stmtDelete = $conn->prepare("DELETE FROM coupons WHERE id = :id");
        if ($stmtDelete->execute([':id' => $deleteId])) {
            $message = "Xóa mã giảm giá thành công!";
        }
    } catch (PDOException $e) {
        $error = "Không thể xóa mã giảm giá này. Lỗi: " . $e->getMessage();
    }
}

// Fetch coupons
$coupons = [];
try {
    $stmt = $conn->query("SELECT * FROM coupons ORDER BY id DESC");
    $coupons = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Vui lòng chạy lệnh SQL tạo bảng coupons trước. Lỗi: " . $e->getMessage();
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Quản lý Mã giảm giá</h1>
    <a href="coupon_form.php" class="bg-[#C9A84C] hover:bg-[#b5953e] text-white px-5 py-2.5 rounded-lg font-semibold transition-all shadow-md hover:shadow-lg flex items-center gap-2 text-sm">
        <i class="fas fa-plus text-xs"></i> Thêm mã
    </a>
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

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-sm">
                    <th class="py-4 px-6 font-semibold w-16">ID</th>
                    <th class="py-4 px-6 font-semibold">Mã Code</th>
                    <th class="py-4 px-6 font-semibold">Mức giảm</th>
                    <th class="py-4 px-6 font-semibold">Đã dùng / Giới hạn</th>
                    <th class="py-4 px-6 font-semibold">Thời gian áp dụng</th>
                    <th class="py-4 px-6 font-semibold">Trạng thái</th>
                    <th class="py-4 px-6 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (count($coupons) > 0): ?>
                    <?php foreach ($coupons as $coupon): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-sm text-gray-500"><?= $coupon['id'] ?></td>
                            <td class="py-4 px-6 font-bold text-gray-900"><?= htmlspecialchars($coupon['code']) ?></td>
                            <td class="py-4 px-6 text-sm font-medium text-[#C9A84C]">
                                <?= $coupon['discount_type'] === 'percent' ? $coupon['discount_value'] . '%' : number_format($coupon['discount_value'], 0, ',', '.') . 'đ' ?>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">
                                <?= (int)$coupon['used_count'] ?> / <?= $coupon['usage_limit'] ? (int)$coupon['usage_limit'] : '∞' ?>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500">
                                <div><span class="text-xs text-gray-400">Từ:</span> <?= $coupon['start_date'] ? date('d/m/Y H:i', strtotime($coupon['start_date'])) : 'Không giới hạn' ?></div>
                                <div><span class="text-xs text-gray-400">Đến:</span> <?= $coupon['end_date'] ? date('d/m/Y H:i', strtotime($coupon['end_date'])) : 'Không giới hạn' ?></div>
                            </td>
                            <td class="py-4 px-6">
                                <?php 
                                $now = new DateTime();
                                $isActive = $coupon['is_active'] == 1;
                                $isExpired = $coupon['end_date'] && new DateTime($coupon['end_date']) < $now;
                                $isLimitReached = $coupon['usage_limit'] && $coupon['used_count'] >= $coupon['usage_limit'];
                                
                                if (!$isActive) {
                                    echo '<span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Đã tắt</span>';
                                } elseif ($isExpired) {
                                    echo '<span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Hết hạn</span>';
                                } elseif ($isLimitReached) {
                                    echo '<span class="px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Hết lượt</span>';
                                } else {
                                    echo '<span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Đang chạy</span>';
                                }
                                ?>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="coupon_form.php?id=<?= $coupon['id'] ?>" class="text-blue-500 hover:text-blue-700" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?');" class="inline-block">
                                        <input type="hidden" name="delete_id" value="<?= $coupon['id'] ?>">
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
                        <td colspan="7" class="py-8 text-center text-gray-500">Chưa có mã giảm giá nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

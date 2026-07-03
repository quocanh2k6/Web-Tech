<?php
require_once __DIR__ . '/includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$message = '';
$error = '';

$coupon = [
    'code' => '',
    'discount_type' => 'percent',
    'discount_value' => '',
    'usage_limit' => '',
    'start_date' => '',
    'end_date' => '',
    'is_active' => 1
];

if ($isEdit) {
    try {
        $stmt = $conn->prepare("SELECT * FROM coupons WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $existing = $stmt->fetch();
        if ($existing) {
            $coupon = array_merge($coupon, $existing);
            if ($coupon['start_date']) $coupon['start_date'] = date('Y-m-d\TH:i', strtotime($coupon['start_date']));
            if ($coupon['end_date']) $coupon['end_date'] = date('Y-m-d\TH:i', strtotime($coupon['end_date']));
        } else {
            die("Không tìm thấy mã giảm giá.");
        }
    } catch (PDOException $e) {
        die("Lỗi cơ sở dữ liệu. Vui lòng kiểm tra bảng coupons.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $discount_type = $_POST['discount_type'] ?? 'percent';
    $discount_value = (float)($_POST['discount_value'] ?? 0);
    $usage_limit = !empty($_POST['usage_limit']) ? (int)$_POST['usage_limit'] : null;
    $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
    $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($code)) {
        $error = "Mã code không được để trống.";
    } elseif ($discount_value <= 0) {
        $error = "Mức giảm giá phải lớn hơn 0.";
    } else {
        try {
            if ($isEdit) {
                // Check duplicate code
                $check = $conn->prepare("SELECT id FROM coupons WHERE code = :code AND id != :id");
                $check->execute([':code' => $code, ':id' => $id]);
                if ($check->fetch()) {
                    $error = "Mã code này đã tồn tại.";
                } else {
                    $stmtUpdate = $conn->prepare("UPDATE coupons SET code = :code, discount_type = :type, discount_value = :val, usage_limit = :limit, start_date = :start, end_date = :end, is_active = :active WHERE id = :id");
                    $stmtUpdate->execute([
                        ':code' => $code,
                        ':type' => $discount_type,
                        ':val' => $discount_value,
                        ':limit' => $usage_limit,
                        ':start' => $start_date,
                        ':end' => $end_date,
                        ':active' => $is_active,
                        ':id' => $id
                    ]);
                    $message = "Cập nhật mã giảm giá thành công!";
                    // Update current values
                    $coupon['code'] = $code;
                    $coupon['discount_type'] = $discount_type;
                    $coupon['discount_value'] = $discount_value;
                    $coupon['usage_limit'] = $usage_limit;
                    $coupon['start_date'] = $start_date;
                    $coupon['end_date'] = $end_date;
                    $coupon['is_active'] = $is_active;
                }
            } else {
                // Check duplicate code
                $check = $conn->prepare("SELECT id FROM coupons WHERE code = :code");
                $check->execute([':code' => $code]);
                if ($check->fetch()) {
                    $error = "Mã code này đã tồn tại.";
                } else {
                    $stmtInsert = $conn->prepare("INSERT INTO coupons (code, discount_type, discount_value, usage_limit, start_date, end_date, is_active) VALUES (:code, :type, :val, :limit, :start, :end, :active)");
                    $stmtInsert->execute([
                        ':code' => $code,
                        ':type' => $discount_type,
                        ':val' => $discount_value,
                        ':limit' => $usage_limit,
                        ':start' => $start_date,
                        ':end' => $end_date,
                        ':active' => $is_active
                    ]);
                    echo "<script>window.location.href='coupons.php';</script>";
                    exit;
                }
            }
        } catch (PDOException $e) {
            $error = "Có lỗi xảy ra: " . $e->getMessage();
        }
    }
}
?>

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-4">
        <a href="coupons.php" class="text-gray-500 hover:text-[#C9A84C]">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800"><?= $isEdit ? 'Sửa Mã giảm giá' : 'Thêm Mã giảm giá mới' ?></h1>
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mã code <span class="text-red-500">*</span></label>
                <input type="text" name="code" value="<?= htmlspecialchars($coupon['code']) ?>" required placeholder="VD: SUMMER2024"
                       class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/20 focus:border-[#C9A84C] uppercase">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Loại giảm giá</label>
                <select name="discount_type" class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/20 focus:border-[#C9A84C]">
                    <option value="percent" <?= $coupon['discount_type'] === 'percent' ? 'selected' : '' ?>>Theo phần trăm (%)</option>
                    <option value="fixed" <?= $coupon['discount_type'] === 'fixed' ? 'selected' : '' ?>>Số tiền cố định (VNĐ)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mức giảm <span class="text-red-500">*</span></label>
                <input type="number" name="discount_value" value="<?= htmlspecialchars($coupon['discount_value']) ?>" required min="1" step="0.01"
                       class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/20 focus:border-[#C9A84C]">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Giới hạn số lần sử dụng (Để trống = Không giới hạn)</label>
                <input type="number" name="usage_limit" value="<?= htmlspecialchars($coupon['usage_limit']) ?>" min="1"
                       class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/20 focus:border-[#C9A84C]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian bắt đầu</label>
                <input type="datetime-local" name="start_date" value="<?= htmlspecialchars($coupon['start_date']) ?>"
                       class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/20 focus:border-[#C9A84C]">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian kết thúc</label>
                <input type="datetime-local" name="end_date" value="<?= htmlspecialchars($coupon['end_date']) ?>"
                       class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/20 focus:border-[#C9A84C]">
            </div>
            
            <div class="md:col-span-2 flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= $coupon['is_active'] ? 'checked' : '' ?>
                       class="w-4 h-4 text-[#C9A84C] bg-gray-100 border-gray-300 rounded focus:ring-[#C9A84C]">
                <label for="is_active" class="text-sm font-medium text-gray-700">Kích hoạt mã giảm giá này</label>
            </div>
        </div>
        
        <div class="pt-4 border-t border-gray-100 mt-6 flex justify-end gap-3">
            <a href="coupons.php" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium rounded-lg transition-colors text-sm">
                Hủy
            </a>
            <button type="submit" class="px-5 py-2.5 bg-[#C9A84C] hover:bg-[#b5953e] text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg flex items-center gap-2 text-sm">
                <i class="fas fa-save text-xs"></i>
                <?= $isEdit ? 'Lưu thay đổi' : 'Lưu mã giảm giá' ?>
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

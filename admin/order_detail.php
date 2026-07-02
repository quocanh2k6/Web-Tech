<?php
require_once __DIR__ . '/includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID Đơn hàng không hợp lệ.");
}

$order_id = (int)$_GET['id'];

// Handle status update
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $newStatus = $_POST['status'];
    $allowedStatuses = ['Pending', 'Shipping', 'Completed', 'Cancelled'];
    
    if (in_array($newStatus, $allowedStatuses)) {
        $stmtUpdate = $conn->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmtUpdate->execute([':status' => $newStatus, ':id' => $order_id]);
        $message = "Cập nhật trạng thái thành công!";
    }
}

// Fetch order details
$stmt = $conn->prepare("
    SELECT o.*, u.full_name, u.email, u.phone, u.address 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.id = :id
");
$stmt->execute([':id' => $order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Không tìm thấy đơn hàng.");
}

// Fetch order items
$stmtItems = $conn->prepare("
    SELECT oi.*, p.name, p.image_url 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = :order_id
");
$stmtItems->execute([':order_id' => $order_id]);
$items = $stmtItems->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-4">
        <a href="orders.php" class="text-gray-500 hover:text-brand-primary">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Chi tiết Đơn hàng #<?= htmlspecialchars($order['id']) ?></h1>
    </div>
</div>

<?php if ($message): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-100 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left column: Order items -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800">Sản phẩm đã đặt</h2>
            </div>
            <div class="p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-gray-500 text-sm border-b border-gray-100">
                            <th class="pb-3 font-medium">Sản phẩm</th>
                            <th class="pb-3 font-medium text-center">Số lượng</th>
                            <th class="pb-3 font-medium text-right">Đơn giá</th>
                            <th class="pb-3 font-medium text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="../<?= htmlspecialchars($item['image_url'] ?? 'assets/images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-12 h-12 object-cover rounded bg-gray-50">
                                        <span class="font-medium text-gray-800 text-sm"><?= htmlspecialchars($item['name']) ?></span>
                                    </div>
                                </td>
                                <td class="py-4 text-center text-sm"><?= (int)$item['quantity'] ?></td>
                                <td class="py-4 text-right text-sm text-gray-600"><?= number_format($item['price'], 0, ',', '.') ?>đ</td>
                                <td class="py-4 text-right text-sm font-medium text-brand-primary"><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>đ</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-gray-100">
                            <td colspan="3" class="py-4 text-right font-bold text-gray-800">Tổng cộng:</td>
                            <td class="py-4 text-right font-bold text-xl text-brand-primary"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Right column: Customer Info & Status Update -->
    <div class="space-y-6">
        <!-- Customer Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Thông tin Khách hàng</h2>
            <div class="space-y-3 text-sm">
                <div class="flex items-start gap-3">
                    <i class="fas fa-user text-gray-400 mt-0.5 w-4 text-center"></i>
                    <div>
                        <div class="text-gray-500 text-xs">Họ tên</div>
                        <div class="font-medium text-gray-800"><?= htmlspecialchars($order['full_name']) ?></div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-envelope text-gray-400 mt-0.5 w-4 text-center"></i>
                    <div>
                        <div class="text-gray-500 text-xs">Email</div>
                        <div class="font-medium text-gray-800"><?= htmlspecialchars($order['email']) ?></div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-phone text-gray-400 mt-0.5 w-4 text-center"></i>
                    <div>
                        <div class="text-gray-500 text-xs">Số điện thoại</div>
                        <div class="font-medium text-gray-800"><?= htmlspecialchars($order['phone'] ?? 'Chưa cập nhật') ?></div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-map-marker-alt text-gray-400 mt-0.5 w-4 text-center"></i>
                    <div>
                        <div class="text-gray-500 text-xs">Địa chỉ giao hàng</div>
                        <div class="font-medium text-gray-800"><?= htmlspecialchars($order['address'] ?? 'Chưa cập nhật') ?></div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="fas fa-credit-card text-gray-400 mt-0.5 w-4 text-center"></i>
                    <div>
                        <div class="text-gray-500 text-xs">Phương thức thanh toán</div>
                        <div class="font-medium text-gray-800"><?= htmlspecialchars($order['payment_method']) ?></div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <i class="far fa-clock text-gray-400 mt-0.5 w-4 text-center"></i>
                    <div>
                        <div class="text-gray-500 text-xs">Ngày đặt</div>
                        <div class="font-medium text-gray-800"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Update -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Cập nhật Trạng thái</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái hiện tại</label>
                    <select name="status" class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary">
                        <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                        <option value="Shipping" <?= $order['status'] === 'Shipping' ? 'selected' : '' ?>>Đang giao</option>
                        <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Hoàn thành</option>
                        <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-brand-primary text-white py-2.5 rounded-lg font-medium hover:bg-brand-secondary transition-colors">
                    Lưu cập nhật
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

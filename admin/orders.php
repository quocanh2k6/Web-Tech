<?php
require_once __DIR__ . '/includes/header.php';

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get total orders
$stmtTotal = $conn->prepare("SELECT COUNT(*) FROM orders");
$stmtTotal->execute();
$totalOrders = $stmtTotal->fetchColumn();
$totalPages = ceil($totalOrders / $limit);

// Fetch orders with user info
$stmt = $conn->prepare("
    SELECT o.id, o.total_amount, o.status, o.created_at, u.full_name, u.email 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll();
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Quản lý Đơn hàng</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-sm">
                    <th class="py-4 px-6 font-semibold">Mã ĐH</th>
                    <th class="py-4 px-6 font-semibold">Khách hàng</th>
                    <th class="py-4 px-6 font-semibold">Tổng tiền</th>
                    <th class="py-4 px-6 font-semibold">Ngày đặt</th>
                    <th class="py-4 px-6 font-semibold">Trạng thái</th>
                    <th class="py-4 px-6 font-semibold text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900">#<?= htmlspecialchars($order['id']) ?></td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($order['full_name']) ?></div>
                                <div class="text-xs text-gray-500"><?= htmlspecialchars($order['email']) ?></div>
                            </td>
                            <td class="py-4 px-6 text-sm font-medium text-brand-primary"><?= number_format($order['total_amount'], 0, ',', '.') ?>đ</td>
                            <td class="py-4 px-6 text-sm text-gray-500"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td class="py-4 px-6">
                                <?php 
                                $statusClass = 'bg-gray-100 text-gray-700';
                                $statusLabel = htmlspecialchars($order['status']);
                                $normalizedStatus = strtolower($order['status']);
                                
                                if ($normalizedStatus === 'pending' || $normalizedStatus === 'thành công') {
                                    $statusClass = 'bg-orange-100 text-orange-800 font-bold';
                                    $statusLabel = 'Chờ xử lý';
                                } elseif ($normalizedStatus === 'shipping') {
                                    $statusClass = 'bg-blue-100 text-blue-800 font-medium';
                                    $statusLabel = 'Đang giao';
                                } elseif ($normalizedStatus === 'completed') {
                                    $statusClass = 'bg-green-100 text-green-800 font-medium';
                                    $statusLabel = 'Thành công';
                                } elseif ($normalizedStatus === 'cancelled') {
                                    $statusClass = 'bg-red-100 text-red-800 font-medium';
                                    $statusLabel = 'Đã hủy';
                                }
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs <?= $statusClass ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="order_detail.php?id=<?= $order['id'] ?>" class="text-brand-primary hover:text-brand-secondary font-medium text-sm">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">Không có đơn hàng nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Phân trang -->
    <?php if ($totalPages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <div class="text-sm text-gray-500">
            Hiển thị trang <span class="font-medium text-gray-900"><?= $page ?></span> / <span class="font-medium text-gray-900"><?= $totalPages ?></span>
        </div>
        <div class="flex gap-2">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="px-3 py-1 border border-gray-200 rounded text-sm hover:bg-gray-50">Trước</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="px-3 py-1 border <?= $i === $page ? 'border-brand-primary bg-brand-primary text-white' : 'border-gray-200 hover:bg-gray-50' ?> rounded text-sm">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="px-3 py-1 border border-gray-200 rounded text-sm hover:bg-gray-50">Sau</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
require_once 'db_connect.php';
require_once 'includes/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'orders.php';
    header('Location: auth.php');
    exit();
}

$userId = (int) $_SESSION['user_id'];

$stmt = $conn->prepare('
    SELECT id, total_amount, payment_method, status, created_at
    FROM orders
    WHERE user_id = :user_id
    ORDER BY created_at DESC, id DESC
');
$stmt->execute(['user_id' => $userId]);
$orders = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="max-w-7xl mx-auto py-12 px-6 min-h-[70vh] mt-8">
    <div class="mb-10 border-b border-brand-border pb-6">
        <p class="section-eyebrow mb-2">Bảng Điều Khiển</p>
        <h1 class="font-display text-4xl md:text-5xl font-black text-brand-white">Lịch Sử Đơn Hàng</h1>
    </div>

    <div class="flex gap-4 mb-6">
        <a href="profile.php" class="btn-outline rounded-md">Hồ Sơ</a>
        <a href="orders.php" class="btn-gold rounded-md">Đơn Hàng</a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="bg-brand-card border border-brand-border shadow-sm rounded-xl p-16 text-center">
            <div class="text-6xl text-brand-border mb-6"><i class="fas fa-clipboard-list"></i></div>
            <p class="text-brand-sub mb-8 font-body text-lg">Bạn chưa có đơn hàng nào.</p>
            <a href="shop.php" class="btn-gold inline-flex rounded-md shadow-md">
                Khám Phá Công Nghệ
            </a>
        </div>
    <?php else: ?>
        <div class="space-y-8">
            <?php foreach ($orders as $order): ?>
                <?php
                    $itemsStmt = $conn->prepare('
                        SELECT oi.quantity, oi.price, p.name, p.image_url
                        FROM order_items oi
                        INNER JOIN products p ON p.id = oi.product_id
                        WHERE oi.order_id = :order_id
                    ');
                    $itemsStmt->execute(['order_id' => (int) $order['id']]);
                    $items = $itemsStmt->fetchAll();
                ?>
                <div class="bg-brand-card border border-brand-border shadow-sm rounded-xl overflow-hidden hover:border-brand-gold/30 transition-colors duration-300">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between p-6 md:p-8 border-b border-brand-border bg-brand-surface">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-brand-sub mb-2">Đơn hàng #<?= (int) $order['id'] ?></p>
                            <h2 class="font-display text-2xl font-black text-brand-white"><?= number_format($order['total_amount'], 0, ',', '.') ?> VNĐ</h2>
                        </div>
                        <div class="flex flex-wrap gap-3 text-sm font-body font-medium">
                            <span class="rounded-md border border-brand-border bg-brand-card px-4 py-2 text-brand-white shadow-sm"><i class="fas fa-credit-card mr-2 text-brand-gold"></i><?= htmlspecialchars($order['payment_method']) ?></span>
                            <span class="rounded-md border border-brand-gold/30 bg-brand-gold/10 px-4 py-2 text-brand-gold shadow-sm"><i class="fas fa-box mr-2"></i><?= htmlspecialchars($order['status']) ?></span>
                            <span class="rounded-md border border-brand-border bg-brand-card px-4 py-2 text-brand-sub shadow-sm"><i class="far fa-calendar-alt mr-2 text-brand-gold"></i><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                        </div>
                    </div>
                    <div class="p-6 md:p-8">
                        <div class="grid gap-6">
                            <?php foreach ($items as $item): ?>
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 rounded-lg border border-brand-border p-4 hover:bg-brand-surface transition-colors">
                                    <div class="w-24 h-24 shrink-0 rounded-md overflow-hidden bg-brand-surface border border-brand-border">
                                        <img
                                            src="<?= htmlspecialchars(asset_image_url($item['image_url'])) ?>"
                                            alt="<?= htmlspecialchars($item['name']) ?>"
                                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';"
                                            class="w-full h-full object-cover"
                                        >
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-display text-xl font-bold mb-2 truncate text-brand-white"><?= htmlspecialchars($item['name']) ?></h3>
                                        <div class="flex gap-4 text-sm font-body text-brand-sub">
                                            <p>SL: <span class="font-bold text-brand-white"><?= (int) $item['quantity'] ?></span></p>
                                            <p>Giá: <span class="font-bold text-brand-white"><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</span></p>
                                        </div>
                                    </div>
                                    <div class="text-right sm:text-right w-full sm:w-auto mt-2 sm:mt-0">
                                        <div class="font-black text-lg text-brand-gold">
                                            <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?> VNĐ
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>

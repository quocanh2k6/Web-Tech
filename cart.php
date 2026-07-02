<?php
require_once 'db_connect.php';
require_once 'includes/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'cart.php';
    header("Location: auth.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    header("Location: cart.php");
    exit();
}

require_once 'includes/header.php';

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_amount = 0;

$cart_product_ids = array_keys($cart_items);
$cart_products = [];

if (!empty($cart_product_ids)) {
    $placeholders = implode(',', array_fill(0, count($cart_product_ids), '?'));
    $stmt = $conn->prepare("SELECT id, name, price, image_url FROM products WHERE id IN ($placeholders)");
    $stmt->execute($cart_product_ids);
    foreach ($stmt->fetchAll() as $product) {
        $cart_products[(int)$product['id']] = $product;
    }
}
?>

<div class="max-w-7xl mx-auto py-12 px-6 min-h-[70vh] mt-8">
    <h1 class="font-display text-4xl md:text-5xl font-black mb-10 border-b border-brand-border pb-6 text-brand-white">Giỏ Hàng Của Bạn</h1>
    
    <?php if(empty($cart_items)): ?>
        <div class="text-center py-20 bg-brand-card rounded-xl shadow-sm border border-brand-border">
            <div class="text-6xl text-brand-border mb-6"><i class="fas fa-box-open"></i></div>
            <p class="text-brand-sub mb-6 text-lg font-body">Giỏ hàng của bạn đang trống.</p>
            <a href="shop.php" class="btn-gold rounded-md inline-flex shadow-lg hover:-translate-y-1">Tiếp Tục Mua Sắm</a>
        </div>
    <?php else: ?>
        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Item List -->
            <div class="w-full lg:w-2/3">
                <div class="bg-brand-card rounded-xl shadow-sm border border-brand-border overflow-hidden">
                    <table class="w-full text-left font-body">
                        <thead class="bg-brand-surface border-b border-brand-border">
                            <tr class="text-xs uppercase tracking-widest text-brand-sub font-bold">
                                <th class="py-4 px-6">Sản Phẩm</th>
                                <th class="py-4 px-6 text-center">SL</th>
                                <th class="py-4 px-6 text-right">Tổng Tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-brand-border">
                            <?php foreach($cart_items as $item): ?>
                            <?php 
                                $current_product = $cart_products[(int)$item['id']] ?? $item;
                                $item_price = isset($current_product['price']) ? $current_product['price'] : $item['price'];
                                $item_name = isset($current_product['name']) ? $current_product['name'] : $item['name'];
                                $item_image = isset($current_product['image_url']) ? $current_product['image_url'] : $item['image_url'];
                                $item_total = $item_price * $item['quantity'];
                                $total_amount += $item_total;
                            ?>
                            <tr class="hover:bg-brand-surface transition-colors">
                                <td class="py-6 px-6">
                                    <div class="flex items-center gap-6">
                                        <div class="w-20 h-20 md:w-24 md:h-24 shrink-0 rounded-lg overflow-hidden border border-brand-border bg-brand-surface">
                                            <img src="<?= htmlspecialchars(asset_image_url($item_image)) ?>" alt="<?= htmlspecialchars($item_name) ?>" 
                                                  onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';"
                                                  class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h3 class="font-display font-bold text-lg mb-1 text-brand-white line-clamp-2"><?= htmlspecialchars($item_name) ?></h3>
                                            <p class="text-brand-gold font-bold text-sm"><?= number_format($item_price, 0, ',', '.') ?> VNĐ</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-6 text-center">
                                    <span class="bg-brand-surface border border-brand-border px-4 py-2 rounded-md font-bold text-brand-white"><?= $item['quantity'] ?></span>
                                </td>
                                <td class="py-6 px-6 text-right font-bold text-brand-white">
                                    <?= number_format($item_total, 0, ',', '.') ?> VNĐ
                                </td>
                                <td class="py-6 px-4 text-right">
                                    <a href="cart.php?action=remove&id=<?= $item['id'] ?>" class="text-brand-sub hover:text-red-500 hover:bg-brand-surface p-2 rounded-full transition-all" title="Remove">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-brand-card rounded-xl shadow-sm border border-brand-border p-8 sticky top-24">
                    <h2 class="font-display text-2xl font-black mb-6 border-b border-brand-border pb-4 text-brand-white">Tóm Tắt Đơn Hàng</h2>
                    
                    <div class="flex justify-between mb-4 text-sm font-body font-medium text-brand-sub">
                        <span>Tạm tính:</span>
                        <span><?= number_format($total_amount, 0, ',', '.') ?> VNĐ</span>
                    </div>
                    <div class="flex justify-between mb-6 text-sm font-body font-medium text-brand-sub">
                        <span>Phí vận chuyển:</span>
                        <span class="text-brand-gold">Miễn phí</span>
                    </div>
                    
                    <div class="flex justify-between mb-8 border-t border-brand-border pt-6">
                        <span class="font-display font-bold text-lg">Tổng cộng:</span>
                        <span class="font-display font-black text-2xl text-brand-gold"><?= number_format($total_amount, 0, ',', '.') ?> VNĐ</span>
                    </div>

                    <a href="checkout.php" class="btn-gold w-full justify-center rounded-md flex">
                        Thanh Toán An Toàn
                    </a>
                    
                    <div class="mt-6 flex items-center justify-center gap-2 text-xs text-brand-sub font-medium">
                        <i class="fas fa-lock"></i>
                        <span>Thanh toán mã hóa bảo mật</span>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>

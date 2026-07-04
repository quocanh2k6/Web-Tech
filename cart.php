<?php
require_once 'config/db_connect.php';
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
    
    // Calculate total amount early for coupon processing
    foreach ($cart_items as $id => $item) {
        $price = isset($cart_products[$id]['price']) ? $cart_products[$id]['price'] : $item['price'];
        $total_amount += $price * $item['quantity'];
    }
}

// Process session coupon
$discount_amount = 0;
$coupon_code = '';
if (isset($_SESSION['coupon'])) {
    $coupon_code = $_SESSION['coupon']['code'];
    if ($_SESSION['coupon']['discount_type'] === 'percent') {
        $discount_amount = $total_amount * ($_SESSION['coupon']['discount_value'] / 100);
    } else {
        $discount_amount = $_SESSION['coupon']['discount_value'];
    }
    if ($discount_amount > $total_amount) $discount_amount = $total_amount;
    $_SESSION['coupon']['discount_amount'] = $discount_amount;
}
$final_total = $total_amount - $discount_amount;
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
                    <div class="flex justify-between mb-4 text-sm font-body font-medium text-brand-sub">
                        <span>Phí vận chuyển:</span>
                        <span class="text-brand-gold">Miễn phí</span>
                    </div>
                    
                    <div id="discount-container" class="flex justify-between mb-6 text-sm font-body font-medium text-[#C9A84C]" <?= $discount_amount > 0 ? '' : 'style="display:none;"' ?>>
                        <span>Giảm giá (<span id="discount-code-display"><?= htmlspecialchars($coupon_code) ?></span>):</span>
                        <span id="discount-amount">- <?= number_format($discount_amount, 0, ',', '.') ?> VNĐ</span>
                    </div>

                    <!-- Promo Code Input -->
                    <div class="mb-6">
                        <form id="coupon-form" method="POST" action="cart.php" class="flex items-center gap-2">
                            <input type="text" id="coupon-input" name="coupon_code" value="<?= htmlspecialchars($coupon_code) ?>" placeholder="Nhập mã giảm giá..." 
                                   class="w-full bg-slate-800/80 border border-slate-700 rounded-lg py-2.5 px-3 text-sm text-white placeholder-slate-400 focus:outline-none focus:border-[#C9A84C] focus:ring-1 focus:ring-[#C9A84C] transition-all">
                            <button type="submit" name="apply_coupon" class="px-5 py-2.5 border border-[#C9A84C] text-[#C9A84C] text-sm font-bold rounded-lg hover:bg-[#C9A84C] hover:text-white transition-all shrink-0">
                                Áp dụng
                            </button>
                        </form>
                        <div id="coupon-message" class="text-xs mt-2 font-medium"></div>
                    </div>
                    
                    <div class="flex justify-between mb-8 border-t border-brand-border pt-6">
                        <span class="font-display font-bold text-lg text-brand-white">Tổng cộng:</span>
                        <span id="final-total" class="font-display font-black text-lg text-brand-gold"><?= number_format($final_total, 0, ',', '.') ?> VNĐ</span>
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

<script>
document.getElementById('coupon-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const code = document.getElementById('coupon-input').value.trim();
    const messageEl = document.getElementById('coupon-message');
    
    if (!code) {
        messageEl.textContent = 'Vui lòng nhập mã giảm giá.';
        messageEl.className = 'text-xs mt-2 font-medium text-red-500';
        return;
    }
    
    messageEl.textContent = 'Đang xử lý...';
    messageEl.className = 'text-xs mt-2 font-medium text-slate-400';
    
    fetch('ajax/apply_coupon.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'coupon_code=' + encodeURIComponent(code)
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            messageEl.textContent = data.message;
            messageEl.className = 'text-xs mt-2 font-medium text-green-500';
            
            // Update UI
            document.getElementById('discount-container').style.display = 'flex';
            document.getElementById('discount-code-display').textContent = code;
            document.getElementById('discount-amount').textContent = '- ' + new Intl.NumberFormat('vi-VN').format(data.discount_amount) + ' VNĐ';
            document.getElementById('final-total').textContent = new Intl.NumberFormat('vi-VN').format(data.new_total) + ' VNĐ';
        } else {
            messageEl.textContent = data.message;
            messageEl.className = 'text-xs mt-2 font-medium text-red-500';
        }
    })
    .catch(err => {
        messageEl.textContent = 'Có lỗi xảy ra, vui lòng thử lại sau.';
        messageEl.className = 'text-xs mt-2 font-medium text-red-500';
        console.error(err);
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>

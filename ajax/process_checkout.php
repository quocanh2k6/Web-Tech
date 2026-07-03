<?php
session_start();
require_once '../db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Bạn cần đăng nhập.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$payment_method = isset($input['payment_method']) ? $input['payment_method'] : '';

if (empty($payment_method)) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng chọn phương thức thanh toán.']);
    exit();
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if(empty($cart_items)){
    echo json_encode(['status' => 'error', 'message' => 'Giỏ hàng đang trống.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$total_amount = 0;
foreach($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

$discount_amount = 0;
$coupon_code = null;
if (isset($_SESSION['coupon'])) {
    $discount_amount = $_SESSION['coupon']['discount_amount'];
    $coupon_code = $_SESSION['coupon']['code'];
    if ($discount_amount > $total_amount) {
        $discount_amount = $total_amount;
    }
}
$final_total = $total_amount - $discount_amount;

try {
    // Begin transaction
    $conn->beginTransaction();

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, status) VALUES (:user_id, :total_amount, :payment_method, 'Pending')");
    $stmt->execute([
        'user_id' => $user_id,
        'total_amount' => $final_total,
        'payment_method' => $payment_method
    ]);
    $order_id = $conn->lastInsertId();

    // Insert order items
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
    foreach($cart_items as $item) {
        $stmt->execute([
            'order_id' => $order_id,
            'product_id' => $item['id'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ]);
    }

    // Update coupon used_count if a coupon was used
    if ($coupon_code) {
        $stmtCoupon = $conn->prepare("UPDATE coupons SET used_count = used_count + 1 WHERE code = :code");
        $stmtCoupon->execute(['code' => $coupon_code]);
    }

    // Commit
    $conn->commit();

    // Clear cart and coupon
    unset($_SESSION['cart']);
    if (isset($_SESSION['coupon'])) {
        unset($_SESSION['coupon']);
    }

    echo json_encode(['status' => 'success', 'message' => 'Thanh toán thành công.']);
} catch(PDOException $e) {
    $conn->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
}

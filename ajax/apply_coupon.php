<?php
session_start();
header('Content-Type: application/json');

require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
    exit;
}

$code = strtoupper(trim($_POST['coupon_code'] ?? ''));
if (empty($code)) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập mã giảm giá.']);
    exit;
}

// Calculate current cart total
$total_amount = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $cart_product_ids = array_keys($_SESSION['cart']);
    if (!empty($cart_product_ids)) {
        $placeholders = implode(',', array_fill(0, count($cart_product_ids), '?'));
        $stmt = $conn->prepare("SELECT id, price FROM products WHERE id IN ($placeholders)");
        $stmt->execute($cart_product_ids);
        $products = [];
        foreach ($stmt->fetchAll() as $product) {
            $products[(int)$product['id']] = $product;
        }
        
        foreach ($_SESSION['cart'] as $id => $item) {
            $price = isset($products[$id]['price']) ? $products[$id]['price'] : $item['price'];
            $total_amount += $price * $item['quantity'];
        }
    }
}

if ($total_amount == 0) {
    echo json_encode(['status' => 'error', 'message' => 'Giỏ hàng của bạn đang trống.']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM coupons WHERE code = ?");
    $stmt->execute([$code]);
    $coupon = $stmt->fetch();

    if (!$coupon) {
        echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá không tồn tại.']);
        exit;
    }

    if ($coupon['is_active'] != 1) {
        echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá đã bị khóa.']);
        exit;
    }

    $now = new DateTime();
    if ($coupon['start_date'] && new DateTime($coupon['start_date']) > $now) {
         echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá chưa đến thời gian áp dụng.']);
         exit;
    }
    
    if ($coupon['end_date'] && new DateTime($coupon['end_date']) < $now) {
         echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá đã hết hạn.']);
         exit;
    }

    if ($coupon['usage_limit'] !== null && $coupon['used_count'] >= $coupon['usage_limit']) {
         echo json_encode(['status' => 'error', 'message' => 'Mã giảm giá đã hết lượt sử dụng.']);
         exit;
    }

    // Calculate discount
    $discount_amount = 0;
    if ($coupon['discount_type'] === 'percent') {
        $discount_amount = $total_amount * ($coupon['discount_value'] / 100);
    } else {
        $discount_amount = $coupon['discount_value'];
    }

    // Discount cannot exceed total amount
    if ($discount_amount > $total_amount) {
        $discount_amount = $total_amount;
    }

    // Save to session
    $_SESSION['coupon'] = [
        'code' => $coupon['code'],
        'discount_amount' => $discount_amount,
        'discount_type' => $coupon['discount_type'],
        'discount_value' => $coupon['discount_value']
    ];

    $new_total = $total_amount - $discount_amount;

    echo json_encode([
        'status' => 'success',
        'message' => 'Áp dụng mã giảm giá thành công!',
        'discount_amount' => $discount_amount,
        'new_total' => $new_total
    ]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
}

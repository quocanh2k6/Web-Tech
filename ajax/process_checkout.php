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

try {
    // Begin transaction
    $conn->beginTransaction();

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_method, status) VALUES (:user_id, :total_amount, :payment_method, 'Thành công')");
    $stmt->execute([
        'user_id' => $user_id,
        'total_amount' => $total_amount,
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

    // Commit
    $conn->commit();

    // Clear cart
    unset($_SESSION['cart']);

    echo json_encode(['status' => 'success', 'message' => 'Thanh toán thành công.']);
} catch(PDOException $e) {
    $conn->rollBack();
    echo json_encode(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]);
}

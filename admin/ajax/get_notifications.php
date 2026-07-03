<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1, 2])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../../db_connect.php';

// Lấy danh sách các đơn hàng mới (Chờ xử lý)
$stmt = $conn->prepare("SELECT id, total_amount, created_at FROM orders WHERE LOWER(status) IN ('pending', 'thành công') ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$pending_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách các tin nhắn liên hệ mới
$stmt = $conn->prepare("SELECT id, name, created_at FROM contacts ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$new_contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$items = [];
$count = 0;

foreach ($pending_orders as $order) {
    $items[] = [
        'type' => 'order',
        'title' => 'Đơn hàng mới #' . $order['id'],
        'time' => date('d/m/Y H:i', strtotime($order['created_at'])),
        'link' => 'order_detail.php?id=' . $order['id'],
        'raw_time' => strtotime($order['created_at'])
    ];
    $count++;
}

foreach ($new_contacts as $contact) {
    $items[] = [
        'type' => 'contact',
        'title' => 'Tin nhắn từ ' . htmlspecialchars($contact['name']),
        'time' => date('d/m/Y H:i', strtotime($contact['created_at'])),
        'link' => '#', // Nếu có trang quản lý liên hệ thì thay vào
        'raw_time' => strtotime($contact['created_at'])
    ];
    $count++;
}

// Sắp xếp gộp theo thời gian mới nhất
usort($items, function($a, $b) {
    return $b['raw_time'] - $a['raw_time'];
});

header('Content-Type: application/json');
echo json_encode([
    'count' => $count,
    'items' => array_slice($items, 0, 5) // Chỉ hiển thị top 5 mới nhất
]);

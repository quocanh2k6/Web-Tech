<?php
require_once '../db_connect.php';
require_once '../includes/helpers.php';

header('Content-Type: application/json; charset=utf-8');

$query = isset($_GET['q']) ? trim((string) $_GET['q']) : '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$likeQuery = '%' . $query . '%';

try {
    $stmt = $conn->prepare("
        SELECT id, name, image_url, price
        FROM products
        WHERE name LIKE :query
        ORDER BY created_at DESC
        LIMIT 10
    ");
    $stmt->execute(['query' => $likeQuery]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results = [];
    foreach ($products as $product) {
        $results[] = [
            'name' => $product['name'],
            'price' => number_format($product['price'], 0, ',', '.') . 'đ',
            'image_url' => asset_image_url($product['image_url']),
            'link' => 'product_detail.php?id=' . $product['id']
        ];
    }

    echo json_encode($results, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    echo json_encode([]);
}

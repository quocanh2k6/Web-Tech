<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1, 2])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../../db_connect.php';

$query = $_GET['q'] ?? '';
$search_term = "%$query%";

$results = [
    'orders' => [],
    'products' => [],
    'users' => []
];

if (strlen($query) > 0) {
    // Search Orders (by ID)
    if (is_numeric($query)) {
        $stmt = $conn->prepare("SELECT id, total_amount, status FROM orders WHERE id = :id LIMIT 5");
        $stmt->execute(['id' => $query]);
        $results['orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search Products
    $stmt = $conn->prepare("SELECT id, name, price FROM products WHERE name LIKE :q LIMIT 5");
    $stmt->execute(['q' => $search_term]);
    $results['products'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Search Users
    $stmt = $conn->prepare("SELECT id, full_name, email FROM users WHERE full_name LIKE :q OR email LIKE :q LIMIT 5");
    $stmt->execute(['q' => $search_term]);
    $results['users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode(['results' => $results]);

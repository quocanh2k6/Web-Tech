<?php
require 'db_connect.php';
try {
    $stmt = $conn->query("SELECT id, full_name, phone, email, role_id FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($users);
    echo "</pre>";
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}

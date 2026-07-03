<?php
require_once __DIR__ . '/db_connect.php';

// Cập nhật link ảnh mới chất lượng cao từ Unsplash
$updates = [
    // Điện thoại
    1 => 'https://images.unsplash.com/photo-1610945265064-3234dac5382b?q=80&w=800&auto=format&fit=crop', // iPhone 16 Pro Max
    2 => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?q=80&w=800&auto=format&fit=crop', // Samsung Galaxy S25
    3 => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?q=80&w=800&auto=format&fit=crop', // iPad Pro
    
    // Laptop
    4 => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=800&auto=format&fit=crop', // MacBook Pro
    5 => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?q=80&w=800&auto=format&fit=crop', // ASUS ROG
    6 => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?q=80&w=800&auto=format&fit=crop', // Dell XPS
    
    // Tai nghe & Âm thanh
    7 => 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?q=80&w=800&auto=format&fit=crop', // Sony WH-1000XM5
    8 => 'https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?q=80&w=800&auto=format&fit=crop', // AirPods Pro 2
    9 => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?q=80&w=800&auto=format&fit=crop', // JBL Charge
    
    // Đồng hồ thông minh
    10 => 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?q=80&w=800&auto=format&fit=crop', // Apple Watch
    11 => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?q=80&w=800&auto=format&fit=crop', // Samsung Watch
    12 => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?q=80&w=800&auto=format&fit=crop', // Garmin
    
    // Phụ kiện
    13 => 'https://images.unsplash.com/photo-1609081219090-a6d81d3085bf?q=80&w=800&auto=format&fit=crop', // Sạc dự phòng
    14 => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?q=80&w=800&auto=format&fit=crop', // Chuột MX Master
    15 => 'https://images.unsplash.com/photo-1595225476474-87563907a212?q=80&w=800&auto=format&fit=crop', // Bàn phím cơ
];

$success_count = 0;
$stmt = $conn->prepare("UPDATE products SET image_url = :image_url WHERE id = :id");

try {
    $conn->beginTransaction();
    foreach ($updates as $id => $image_url) {
        $stmt->execute([
            'image_url' => $image_url,
            'id' => $id
        ]);
        $success_count += $stmt->rowCount();
    }
    $conn->commit();
    echo "<h1>Cập nhật thành công!</h1>";
    echo "<p>Đã thay thế hình ảnh cho {$success_count} sản phẩm.</p>";
    echo "<a href='index.php'>Quay về trang chủ</a>";
} catch (Exception $e) {
    $conn->rollBack();
    echo "<h1>Lỗi cập nhật</h1>";
    echo "<p>Chi tiết: " . $e->getMessage() . "</p>";
}

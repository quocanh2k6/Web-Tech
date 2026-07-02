<?php
require 'db_connect.php';
try {
    $password = password_hash('123456', PASSWORD_DEFAULT);
    
    // Reset mật khẩu cho toàn bộ tài khoản Admin (role_id = 1)
    $stmt = $conn->prepare("UPDATE users SET password = :pass WHERE role_id = 1");
    $stmt->execute(['pass' => $password]);
    
    // Lấy thông tin tài khoản admin để hiển thị cho người dùng biết chính xác tên đăng nhập
    $stmt = $conn->query("SELECT phone, email FROM users WHERE role_id = 1 LIMIT 1");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h1>Thành công (V2)!</h1>";
    echo "<p>Mật khẩu của tài khoản Admin đã được ép đổi thành: <strong>123456</strong></p>";
    echo "<hr>";
    echo "<h3>Hãy copy chính xác tài khoản sau để đăng nhập:</h3>";
    echo "<ul>";
    echo "<li><strong>Tài khoản (Tên đăng nhập):</strong> <span style='color:red; font-size:1.2rem; font-weight:bold'>" . htmlspecialchars($admin['email'] ?? $admin['phone']) . "</span></li>";
    echo "<li><strong>Mật khẩu:</strong> <span style='color:red; font-size:1.2rem; font-weight:bold'>123456</span></li>";
    echo "</ul>";
    echo "<p><a href='auth.php'>Bấm vào đây để quay lại trang đăng nhập</a></p>";
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}

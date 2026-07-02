<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$host     = 'sql206.infinityfree.com';
$db_name  = 'if0_42293041_technova_db';
$username = 'if0_42293041';
$password = 'hciDO8dn2e';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->exec("SET time_zone = '+07:00'");
    require_once __DIR__ . '/includes/helpers.php';
    ensure_user_profile_schema($conn);
} catch(PDOException $e) {
    die("Lỗi kết nối DB. Vui lòng thử lại sau.");
}
?>

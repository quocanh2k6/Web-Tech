<?php
session_start();
require_once '../config/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role_id'], [1, 2])) {
    header("Location: ../auth.php");
    exit;
}

if (isset($_GET['id'])) {
    $del_id = (int)$_GET['id'];
    
    // Attempt to delete image file first (optional, but good practice)
    $stmtImg = $conn->prepare("SELECT image_url FROM products WHERE id = :id");
    $stmtImg->execute(['id' => $del_id]);
    $prod = $stmtImg->fetch();
    
    try {
        $stmtDel = $conn->prepare("DELETE FROM products WHERE id = :id");
        if ($stmtDel->execute(['id' => $del_id])) {
            if ($prod && !empty($prod['image_url'])) {
                $imgPath = __DIR__ . '/../' . ltrim($prod['image_url'], '/');
                if (is_file($imgPath)) {
                    @unlink($imgPath);
                }
            }
            
            // Log action if function exists
            require_once '../includes/helpers.php';
            if (function_exists('log_admin_action')) {
                log_admin_action($conn, $_SESSION['user_id'], 'Delete Product', "Xóa sản phẩm ID $del_id");
            }
            
            header("Location: products.php?status=success");
            exit;
        }
    } catch (PDOException $e) {
        // Fallback for foreign key constraint errors
        header("Location: products.php?status=error");
        exit;
    }
}

header("Location: products.php?status=error");
exit;

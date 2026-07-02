<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Middleware: Must be Admin or Staff
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || !in_array($_SESSION['role_id'], [1, 2])) {
    header("Location: ../auth.php");
    exit();
}
require_once __DIR__ . '/../../db_connect.php';
require_once __DIR__ . '/../../includes/helpers.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNova Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#1A56DB',   // Tech Blue
                            secondary: '#2563EB', // Blue
                            accent: '#06B6D4',    // Cyan
                            black: '#111827',     // Dark
                            stone: '#F3F4F6',     // Light Gray
                            darkstone: '#1F2937', // Dark Gray
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">
<?php require_once 'sidebar.php'; ?>
<!-- Main Content Container -->
<div class="flex-1 flex flex-col overflow-y-auto">
    <!-- Top Header -->
    <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-10 sticky top-0 border-b border-gray-100">
        <h2 class="font-display text-xl font-bold text-gray-700">Bảng Điều Khiển Admin</h2>
        <div class="flex items-center gap-4">
            <a href="../index.php" class="text-sm text-gray-500 hover:text-brand-primary font-medium" target="_blank"><i class="fas fa-external-link-alt mr-1"></i> Xem Website</a>
            <div class="w-px h-6 bg-gray-200"></div>
            <a href="../profile.php" class="flex items-center gap-2 hover:text-brand-primary transition-colors">
                <img src="<?= htmlspecialchars(user_avatar_url($_SESSION['user_avatar'] ?? '', $_SESSION['user_name'] ?? 'Admin')) ?>" alt="Admin" class="w-8 h-8 rounded-full border border-gray-200 object-cover bg-brand-primary">
                <span class="text-sm font-bold text-gray-700"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
            </a>
            <a href="../auth.php?action=logout" class="text-gray-400 hover:text-red-500 ml-2 transition-colors" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>
    <main class="p-8">

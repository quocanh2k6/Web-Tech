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

// Page title mapping
$page_titles = [
    'index.php'         => 'Bảng Điều Khiển',
    'products.php'      => 'Quản Lý Sản Phẩm',
    'product_form.php'  => 'Thêm / Sửa Sản Phẩm',
    'users.php'         => 'Quản Lý Khách Hàng',
    'user_detail.php'   => 'Chi Tiết Khách Hàng',
    'user_form.php'     => 'Thêm / Sửa Người Dùng',
    'orders.php'        => 'Quản Lý Đơn Hàng',
    'order_detail.php'  => 'Chi Tiết Đơn Hàng',
    'categories.php'    => 'Quản Lý Danh Mục',
    'category_form.php' => 'Thêm / Sửa Danh Mục',
    'coupons.php'       => 'Mã Khuyến Mãi',
    'coupon_form.php'   => 'Thêm / Sửa Mã Giảm Giá',
    'newsletter.php'    => 'Đăng Ký Bản Tin',
    'logs.php'          => 'Nhật Ký Hệ Thống',
    'settings.php'      => 'Cài Đặt Website',
];
$current_file = basename($_SERVER['PHP_SELF']);
$page_title = $page_titles[$current_file] ?? 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> — TechNova Admin</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#3b82f6',
                            secondary: '#2563EB',
                            accent: '#06B6D4',
                            black: '#111827',
                            stone: '#F3F4F6',
                            darkstone: '#1F2937',
                        },
                        sidebar: {
                            DEFAULT: '#0f172a',
                            hover: '#1e293b',
                            active: '#1e3a5f',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                        display: ['Poppins', 'Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06)',
                        'card': '0 1px 3px rgba(0,0,0,0.04)',
                        'card-hover': '0 10px 25px -5px rgba(0,0,0,0.08), 0 4px 6px -2px rgba(0,0,0,0.04)',
                        'header': '0 1px 2px rgba(0,0,0,0.03)',
                    },
                    borderRadius: {
                        '2xl': '1rem',
                        '3xl': '1.25rem',
                    }
                }
            }
        }
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons & Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ============================================
           TECHNOVA ADMIN — PREMIUM DESIGN SYSTEM
           Stripe/Vercel-inspired Clean UI
           ============================================ */

        /* --- Base --- */
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* --- Typography Hierarchy --- */
        .font-display, h1, h2, h3 {
            font-family: 'Poppins', 'Inter', sans-serif;
        }
        h1 { font-weight: 700; color: #0f172a; letter-spacing: -0.025em; }
        h2 { font-weight: 600; color: #1e293b; letter-spacing: -0.015em; }
        h3 { font-weight: 600; color: #334155; }

        /* --- Scrollbar (Admin only) --- */
        .admin-scroll::-webkit-scrollbar { width: 4px; }
        .admin-scroll::-webkit-scrollbar-track { background: transparent; }
        .admin-scroll::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .admin-scroll::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* --- Card System --- */
        .admin-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .admin-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.07), 0 4px 6px -2px rgba(0,0,0,0.04);
            transform: translateY(-2px);
        }

        /* --- Sidebar Link Hover Slide --- */
        .sidebar-link {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-link:hover {
            transform: translateX(2px);
        }

        /* --- Stat Card Icon Pulse --- */
        .stat-icon {
            transition: transform 0.3s ease;
        }
        .admin-card:hover .stat-icon {
            transform: scale(1.1);
        }

        /* --- Table Styles --- */
        .admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .admin-table thead th {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            text-align: left;
            white-space: nowrap;
        }
        .admin-table tbody td {
            padding: 0.875rem 1rem;
            font-size: 0.8125rem;
            color: #475569;
            border-bottom: 1px solid #f8fafc;
        }
        .admin-table tbody tr {
            transition: background-color 0.15s ease;
        }
        .admin-table tbody tr:hover {
            background-color: #f8fafc;
        }
        .admin-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* --- Status Badges --- */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }
        .badge-success { background: #ecfdf5; color: #059669; }
        .badge-warning { background: #fffbeb; color: #d97706; }
        .badge-danger  { background: #fef2f2; color: #dc2626; }
        .badge-info    { background: #eff6ff; color: #2563eb; }
        .badge-gray    { background: #f1f5f9; color: #64748b; }

        /* --- Button System --- */
        .btn-admin {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
        }
        .btn-primary { background: #3b82f6; color: #fff; }
        .btn-primary:hover { background: #2563eb; box-shadow: 0 4px 12px rgba(59,130,246,0.3); }
        .btn-ghost { background: transparent; color: #64748b; }
        .btn-ghost:hover { background: #f1f5f9; color: #334155; }

        /* --- Search Input --- */
        .search-admin {
            background: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem 0.5rem 2.25rem;
            font-size: 0.8125rem;
            color: #334155;
            transition: all 0.2s ease;
            outline: none;
            width: 240px;
        }
        .search-admin::placeholder { color: #94a3b8; }
        .search-admin:focus {
            background: #ffffff;
            border-color: #cbd5e1;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
            width: 300px;
        }

        /* --- Notification dot animation --- */
        @keyframes notifPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
        }
        .notif-dot {
            animation: notifPulse 2s ease-in-out infinite;
        }

        /* --- Chart container --- */
        .chart-container {
            position: relative;
            width: 100%;
        }

        /* --- Utility: Fade in animation --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
        .animate-delay-1 { animation-delay: 0.05s; }
        .animate-delay-2 { animation-delay: 0.1s; }
        .animate-delay-3 { animation-delay: 0.15s; }
        .animate-delay-4 { animation-delay: 0.2s; }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

<?php require_once 'sidebar.php'; ?>

<!-- Main Content Container -->
<div class="flex-1 flex flex-col overflow-y-auto admin-scroll">
    
    <!-- Top Header Bar -->
    <header class="bg-white/80 backdrop-blur-lg h-[60px] flex items-center justify-between px-6 z-10 sticky top-0 border-b border-slate-100 shadow-header">
        
        <!-- Left: Breadcrumb -->
        <div class="flex items-center gap-3">
            <div class="flex items-center text-sm">
                <span class="text-slate-400 text-xs"><i class="fas fa-home"></i></span>
                <i class="fas fa-chevron-right text-[8px] text-slate-300 mx-2"></i>
                <span class="text-slate-700 font-semibold text-[13px]"><?= htmlspecialchars($page_title) ?></span>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2">
            
            <!-- Search -->
            <div class="relative hidden md:block">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" class="search-admin" placeholder="Tìm kiếm nhanh..." readonly>
                <kbd class="absolute right-2.5 top-1/2 -translate-y-1/2 px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 bg-white border border-slate-200 rounded">⌘K</kbd>
            </div>

            <div class="w-px h-5 bg-slate-200 mx-1"></div>
            
            <!-- Notification -->
            <button class="relative p-2 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-all" title="Thông báo">
                <i class="fas fa-bell text-[15px]"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full notif-dot"></span>
            </button>

            <!-- View Website -->
            <a href="../index.php" target="_blank" class="btn-admin btn-ghost text-xs hidden sm:inline-flex" title="Xem website">
                <i class="fas fa-external-link-alt text-[11px]"></i>
                <span>Xem Website</span>
            </a>

            <div class="w-px h-5 bg-slate-200 mx-1"></div>

            <!-- User Avatar -->
            <a href="../profile.php" class="flex items-center gap-2.5 pl-1 pr-2 py-1 rounded-lg hover:bg-slate-50 transition-all">
                <img src="<?= htmlspecialchars(user_avatar_url($_SESSION['user_avatar'] ?? '', $_SESSION['user_name'] ?? 'Admin')) ?>" 
                     alt="Admin" 
                     class="w-7 h-7 rounded-full object-cover ring-2 ring-slate-100">
                <div class="hidden lg:block">
                    <p class="text-[12px] font-semibold text-slate-700 leading-none"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
                    <p class="text-[10px] text-slate-400 mt-0.5"><?= $_SESSION['role_id'] == 1 ? 'Root Admin' : 'Nhân viên' ?></p>
                </div>
            </a>
        </div>
    </header>

    <!-- Page Content -->
    <main class="p-6 lg:p-8">

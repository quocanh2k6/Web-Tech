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
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/favicon.png">
    <link rel="apple-touch-icon" href="../assets/favicon.png">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            gold: '#C9A84C',
                            'gold-light': '#E8C97E',
                            'gold-muted': '#C9A84C20',
                            black: '#0a0a0a',
                            stone: '#F7F7F8',
                            darkstone: '#18181B',
                        },
                        sidebar: {
                            DEFAULT: '#111113',
                            hover: '#1c1c1f',
                            active: '#232326',
                        }
                    },
                    fontFamily: {
                        sans: ['"Be Vietnam Pro"', '"Inter"', 'system-ui', '-apple-system', 'sans-serif'],
                        display: ['"Be Vietnam Pro"', '"Inter"', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06)',
                        'card': '0 1px 4px rgba(0,0,0,0.04), 0 0 0 1px rgba(0,0,0,0.02)',
                        'card-hover': '0 12px 28px -6px rgba(0,0,0,0.08), 0 4px 8px -2px rgba(0,0,0,0.04)',
                        'header': '0 1px 2px rgba(0,0,0,0.03)',
                        'gold': '0 4px 14px rgba(201,168,76,0.25)',
                    },
                    borderRadius: {
                        '2xl': '1rem',
                        '3xl': '1.25rem',
                    }
                }
            }
        }
    </script>

    <!-- Fonts: Be Vietnam Pro (Vietnamese native) + Inter (Latin fallback) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&subset=vietnamese&display=swap" rel="stylesheet">
    
    <!-- Icons & Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ============================================
           TECHNOVA ADMIN — PREMIUM DESIGN SYSTEM
           Black + Gold brand identity
           Vietnamese-first typography
           ============================================ */

        /* --- Base --- */
        * { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }

        body {
            font-family: 'Be Vietnam Pro', 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #FAFAFA;
            color: #3f3f46;
            line-height: 1.65;
        }

        /* --- Typography: Vietnamese-safe, NO SERIF --- */
        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: 'Be Vietnam Pro', 'Inter', sans-serif !important;
            line-height: 1.4;
        }
        h1 { font-weight: 700; color: #18181b; letter-spacing: -0.02em; }
        h2 { font-weight: 600; color: #27272a; letter-spacing: -0.01em; }
        h3 { font-weight: 600; color: #3f3f46; }

        /* Force all text to sans-serif — nuke any inherited serif */
        *, *::before, *::after {
            font-family: inherit;
        }

        /* --- Scrollbar --- */
        .admin-scroll::-webkit-scrollbar { width: 4px; }
        .admin-scroll::-webkit-scrollbar-track { background: transparent; }
        .admin-scroll::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 10px; }
        .admin-scroll::-webkit-scrollbar-thumb:hover { background: #52525b; }

        /* --- Card System (Depth & Warmth) --- */
        .admin-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #f0f0f0;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03), 0 0 0 1px rgba(0,0,0,0.01);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .admin-card:hover {
            box-shadow: 0 12px 28px -6px rgba(0,0,0,0.07), 0 4px 8px -2px rgba(0,0,0,0.03);
            border-color: #e4e4e7;
            transform: translateY(-2px);
        }

        /* --- Sidebar Link --- */
        .sidebar-link { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-link:hover { transform: translateX(2px); }

        /* --- Stat Icon Pulse --- */
        .stat-icon { transition: transform 0.3s ease; }
        .admin-card:hover .stat-icon { transform: scale(1.08); }

        /* --- Table Styles --- */
        .admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .admin-table thead th {
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #a1a1aa;
            padding: 0.875rem 1.125rem;
            border-bottom: 1px solid #f4f4f5;
            text-align: left;
            white-space: nowrap;
        }
        .admin-table tbody td {
            font-family: 'Be Vietnam Pro', sans-serif;
            padding: 1rem 1.125rem;
            font-size: 0.8125rem;
            color: #52525b;
            border-bottom: 1px solid #fafafa;
            line-height: 1.5;
        }
        .admin-table tbody tr { transition: background-color 0.15s ease; }
        .admin-table tbody tr:hover { background-color: #FAFAFA; }
        .admin-table tbody tr:last-child td { border-bottom: none; }

        /* --- Status Badges (Pill-shaped, Pastel) --- */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.7rem;
            border-radius: 9999px;
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            line-height: 1.5;
        }
        .badge-success { background: #ecfdf5; color: #059669; }
        .badge-warning { background: #fef9ee; color: #b45309; }
        .badge-danger  { background: #fef2f2; color: #dc2626; }
        .badge-info    { background: #fef9ee; color: #C9A84C; }
        .badge-gray    { background: #f4f4f5; color: #71717a; }

        /* --- Button System --- */
        .btn-admin {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 0.625rem;
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            line-height: 1.5;
        }
        .btn-primary { background: #C9A84C; color: #fff; }
        .btn-primary:hover { background: #b89738; box-shadow: 0 4px 14px rgba(201,168,76,0.3); }
        .btn-ghost { background: transparent; color: #71717a; }
        .btn-ghost:hover { background: #f4f4f5; color: #3f3f46; }

        /* --- Search Input --- */
        .search-admin {
            background: #f4f4f5;
            border: 1px solid transparent;
            border-radius: 0.75rem;
            padding: 0.5rem 0.75rem 0.5rem 2.25rem;
            font-family: 'Be Vietnam Pro', sans-serif;
            font-size: 0.8125rem;
            color: #3f3f46;
            transition: all 0.2s ease;
            outline: none;
            width: 240px;
        }
        .search-admin::placeholder { color: #a1a1aa; }
        .search-admin:focus {
            background: #ffffff;
            border-color: #d4d4d8;
            box-shadow: 0 0 0 3px rgba(201,168,76,0.1);
            width: 300px;
        }

        /* --- Notification dot --- */
        @keyframes notifPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.3); opacity: 0.7; }
        }
        .notif-dot { animation: notifPulse 2s ease-in-out infinite; }

        /* --- Chart container --- */
        .chart-container { position: relative; width: 100%; }

        /* --- Fade in animation --- */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
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
    <header class="bg-white/80 backdrop-blur-lg h-[60px] flex items-center justify-between px-6 z-10 sticky top-0 border-b border-zinc-100 shadow-header">
        
        <!-- Left: Breadcrumb -->
        <div class="flex items-center gap-3">
            <div class="flex items-center text-sm">
                <span class="text-zinc-400 text-xs"><i class="fas fa-home"></i></span>
                <i class="fas fa-chevron-right text-[8px] text-zinc-300 mx-2"></i>
                <span class="text-zinc-700 font-semibold text-[13px]"><?= htmlspecialchars($page_title) ?></span>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="flex items-center gap-2">
            
            <!-- Search -->
            <div class="relative hidden md:block" id="search-container">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
                <input type="text" id="quick-search-input" class="search-admin" placeholder="Tìm kiếm nhanh...">
                <kbd class="absolute right-2.5 top-1/2 -translate-y-1/2 px-1.5 py-0.5 text-[10px] font-semibold text-zinc-400 bg-white border border-zinc-200 rounded">⌘K</kbd>
                
                <!-- Search Dropdown -->
                <div id="search-dropdown" class="absolute top-full left-0 mt-2 w-[350px] bg-white border border-zinc-200 rounded-xl shadow-card-hover z-50 hidden max-h-[400px] overflow-y-auto admin-scroll">
                    <!-- Results will be injected here -->
                </div>
            </div>

            <div class="w-px h-5 bg-zinc-200 mx-1"></div>
            
            <div class="relative" id="notif-container">
                <button id="notif-btn" class="relative p-2 rounded-lg text-zinc-400 hover:text-zinc-600 hover:bg-zinc-50 transition-all" title="Thông báo">
                    <i class="fas fa-bell text-[15px]"></i>
                    <span id="notif-badge" class="absolute top-1.5 right-1.5 w-2 h-2 bg-brand-gold rounded-full notif-dot hidden"></span>
                </button>

                <!-- Notification Dropdown -->
                <div id="notif-dropdown" class="absolute top-full right-0 mt-2 w-[320px] bg-white border border-zinc-200 rounded-xl shadow-card-hover z-50 hidden">
                    <div class="p-3 border-b border-zinc-100 flex justify-between items-center">
                        <span class="font-semibold text-sm text-zinc-800">Thông báo mới</span>
                        <span id="notif-count-text" class="text-xs bg-brand-gold/10 text-brand-gold px-2 py-0.5 rounded-full font-semibold">0</span>
                    </div>
                    <div id="notif-list" class="max-h-[350px] overflow-y-auto admin-scroll">
                        <!-- Notifications injected here -->
                    </div>
                </div>
            </div>

            <!-- View Website -->
            <a href="../index.php" target="_blank" class="btn-admin btn-ghost text-xs hidden sm:inline-flex" title="Xem website">
                <i class="fas fa-external-link-alt text-[11px]"></i>
                <span>Xem Website</span>
            </a>

            <div class="w-px h-5 bg-zinc-200 mx-1"></div>

            <!-- User Avatar -->
            <a href="../profile.php" class="flex items-center gap-2.5 pl-1 pr-2 py-1 rounded-lg hover:bg-zinc-50 transition-all">
                <img src="<?= htmlspecialchars(user_avatar_url($_SESSION['user_avatar'] ?? '', $_SESSION['user_name'] ?? 'Admin')) ?>" 
                     alt="Admin" 
                     class="w-7 h-7 rounded-full object-cover ring-2 ring-zinc-100">
                <div class="hidden lg:block">
                    <p class="text-[12px] font-semibold text-zinc-700 leading-snug"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
                    <p class="text-[10px] text-zinc-400 mt-0.5"><?= $_SESSION['role_id'] == 1 ? 'Root Admin' : 'Nhân viên' ?></p>
                </div>
            </a>
        </div>
    </header>

    <!-- Page Content -->
    <main class="p-6 lg:p-8">
        
<script>
document.addEventListener('DOMContentLoaded', () => {
    // === Quick Search Logic ===
    const searchInput = document.getElementById('quick-search-input');
    const searchDropdown = document.getElementById('search-dropdown');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();
            
            if (query.length < 2) {
                searchDropdown.classList.add('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`ajax/search.php?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';
                        const r = data.results;
                        
                        if (r.orders && r.orders.length > 0) {
                            html += `<div class="px-3 py-2 text-xs font-semibold text-zinc-400 uppercase tracking-wider bg-zinc-50">Đơn hàng</div>`;
                            r.orders.forEach(o => {
                                html += `<a href="order_detail.php?id=${o.id}" class="block px-4 py-2.5 hover:bg-zinc-50 border-b border-zinc-50 last:border-0 transition-colors">
                                    <div class="text-sm font-medium text-zinc-800">Đơn hàng #${o.id}</div>
                                    <div class="text-xs text-brand-gold font-semibold">${new Intl.NumberFormat('vi-VN').format(o.total_amount)}đ</div>
                                </a>`;
                            });
                        }
                        if (r.products && r.products.length > 0) {
                            html += `<div class="px-3 py-2 text-xs font-semibold text-zinc-400 uppercase tracking-wider bg-zinc-50">Sản phẩm</div>`;
                            r.products.forEach(p => {
                                html += `<a href="product_form.php?id=${p.id}" class="block px-4 py-2.5 hover:bg-zinc-50 border-b border-zinc-50 last:border-0 transition-colors">
                                    <div class="text-sm font-medium text-zinc-800">${p.name}</div>
                                    <div class="text-xs text-brand-gold font-semibold">${new Intl.NumberFormat('vi-VN').format(p.price)}đ</div>
                                </a>`;
                            });
                        }
                        if (r.users && r.users.length > 0) {
                            html += `<div class="px-3 py-2 text-xs font-semibold text-zinc-400 uppercase tracking-wider bg-zinc-50">Khách hàng</div>`;
                            r.users.forEach(u => {
                                html += `<a href="user_detail.php?id=${u.id}" class="block px-4 py-2.5 hover:bg-zinc-50 border-b border-zinc-50 last:border-0 transition-colors">
                                    <div class="text-sm font-medium text-zinc-800">${u.full_name}</div>
                                    <div class="text-xs text-zinc-500">${u.email}</div>
                                </a>`;
                            });
                        }

                        if (html === '') {
                            html = `<div class="p-4 text-center text-sm text-zinc-500">Không tìm thấy kết quả phù hợp.</div>`;
                        }

                        searchDropdown.innerHTML = html;
                        searchDropdown.classList.remove('hidden');
                    })
                    .catch(err => console.error('Search error:', err));
            }, 300); // 300ms debounce
        });

        // Keyboard shortcut ⌘K
        document.addEventListener('keydown', (e) => {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }

    // === Notification Logic ===
    const notifBtn = document.getElementById('notif-btn');
    const notifDropdown = document.getElementById('notif-dropdown');
    const notifBadge = document.getElementById('notif-badge');
    const notifList = document.getElementById('notif-list');
    const notifCountText = document.getElementById('notif-count-text');

    const fetchNotifications = () => {
        fetch('ajax/get_notifications.php')
            .then(res => res.json())
            .then(data => {
                if (data.count > 0) {
                    notifBadge.classList.remove('hidden');
                    notifCountText.textContent = data.count + ' Mới';
                } else {
                    notifBadge.classList.add('hidden');
                    notifCountText.textContent = '0';
                }

                let html = '';
                if (data.items && data.items.length > 0) {
                    data.items.forEach(item => {
                        html += `<a href="${item.link}" class="flex gap-3 p-3 border-b border-zinc-50 hover:bg-zinc-50 transition-colors">
                            <div class="mt-1 flex-shrink-0">
                                <i class="${item.type === 'order' ? 'fas fa-box text-blue-500' : 'fas fa-envelope text-brand-gold'}"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-zinc-800">${item.title}</div>
                                <div class="text-[10px] text-zinc-400 mt-0.5">${item.time}</div>
                            </div>
                        </a>`;
                    });
                } else {
                    html = `<div class="p-6 text-center text-zinc-400 text-sm">Không có thông báo mới</div>`;
                }
                notifList.innerHTML = html;
            })
            .catch(err => console.error('Notif error:', err));
    };

    if (notifBtn) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
            if (!notifDropdown.classList.contains('hidden')) {
                fetchNotifications();
            }
        });

        // Initial fetch & set interval (e.g., 30 seconds)
        fetchNotifications();
        setInterval(fetchNotifications, 30000);
    }

    // === Click Outside to Close ===
    document.addEventListener('click', (e) => {
        if (searchDropdown && searchInput && !searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.add('hidden');
        }
        if (notifDropdown && notifBtn && !notifBtn.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.add('hidden');
        }
    });
});
</script>

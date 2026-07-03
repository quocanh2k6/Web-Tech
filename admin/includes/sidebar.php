<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<aside class="sidebar-admin w-[260px] bg-[#111113] text-zinc-400 flex flex-col sticky top-0 h-screen z-20 border-r border-zinc-800/40">
    
    <!-- Logo Area -->
    <div class="h-16 flex items-center px-5 border-b border-zinc-800/40">
        <a href="index.php" class="flex items-center gap-2 group">
            <span class="text-[17px] font-extrabold tracking-[0.04em] leading-none select-none">
                <span class="text-white">Tech</span><span class="text-[#C9A84C]">Nova</span>
            </span>
            <span class="relative flex h-2 w-2 ml-0.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#C9A84C] opacity-40"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#C9A84C]"></span>
            </span>
            <span class="ml-1 text-[9px] font-semibold uppercase tracking-[0.14em] text-zinc-500 border border-zinc-700 rounded px-1.5 py-[1px] leading-tight">Admin</span>
        </a>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 py-5 px-3 overflow-y-auto admin-scroll">

        <!-- Main Group -->
        <div class="mb-6">
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.15em] text-zinc-600">Chính</p>
            
            <a href="index.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= $current_page == 'index.php' ? 'bg-zinc-800/60 text-white shadow-sm' : 'hover:bg-zinc-800/40 hover:text-zinc-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $current_page == 'index.php' ? 'bg-brand-gold/15 text-brand-gold' : 'text-zinc-500' ?>">
                    <i class="fas fa-chart-pie text-[15px]"></i>
                </div>
                <span>Bảng Điều Khiển</span>
                <?php if($current_page == 'index.php'): ?>
                <div class="ml-auto w-1.5 h-1.5 rounded-full bg-brand-gold"></div>
                <?php endif; ?>
            </a>
            
            <a href="products.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'products.php' || $current_page == 'product_form.php') ? 'bg-zinc-800/60 text-white shadow-sm' : 'hover:bg-zinc-800/40 hover:text-zinc-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'products.php' || $current_page == 'product_form.php') ? 'bg-brand-gold/15 text-brand-gold' : 'text-zinc-500' ?>">
                    <i class="fas fa-box text-[15px]"></i>
                </div>
                <span>Sản Phẩm</span>
            </a>
            
            <a href="orders.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'orders.php' || $current_page == 'order_detail.php') ? 'bg-zinc-800/60 text-white shadow-sm' : 'hover:bg-zinc-800/40 hover:text-zinc-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'orders.php' || $current_page == 'order_detail.php') ? 'bg-brand-gold/15 text-brand-gold' : 'text-zinc-500' ?>">
                    <i class="fas fa-shopping-bag text-[15px]"></i>
                </div>
                <span>Đơn Hàng</span>
            </a>

            <a href="users.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'users.php' || $current_page == 'user_detail.php' || $current_page == 'user_form.php') ? 'bg-zinc-800/60 text-white shadow-sm' : 'hover:bg-zinc-800/40 hover:text-zinc-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'users.php' || $current_page == 'user_detail.php' || $current_page == 'user_form.php') ? 'bg-brand-gold/15 text-brand-gold' : 'text-zinc-500' ?>">
                    <i class="fas fa-users text-[15px]"></i>
                </div>
                <span>Khách Hàng</span>
            </a>

            <a href="categories.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'categories.php' || $current_page == 'category_form.php') ? 'bg-zinc-800/60 text-white shadow-sm' : 'hover:bg-zinc-800/40 hover:text-zinc-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'categories.php' || $current_page == 'category_form.php') ? 'bg-brand-gold/15 text-brand-gold' : 'text-zinc-500' ?>">
                    <i class="fas fa-tags text-[15px]"></i>
                </div>
                <span>Danh Mục</span>
            </a>

            <a href="coupons.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'coupons.php' || $current_page == 'coupon_form.php') ? 'bg-zinc-800/60 text-white shadow-sm' : 'hover:bg-zinc-800/40 hover:text-zinc-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'coupons.php' || $current_page == 'coupon_form.php') ? 'bg-brand-gold/15 text-brand-gold' : 'text-zinc-500' ?>">
                    <i class="fas fa-ticket-alt text-[15px]"></i>
                </div>
                <span>Khuyến Mãi</span>
            </a>
        </div>

        <!-- System Group -->
        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.15em] text-zinc-600">Hệ Thống</p>

            <a href="newsletter.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= $current_page == 'newsletter.php' ? 'bg-zinc-800/60 text-white shadow-sm' : 'hover:bg-zinc-800/40 hover:text-zinc-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $current_page == 'newsletter.php' ? 'bg-brand-gold/15 text-brand-gold' : 'text-zinc-500' ?>">
                    <i class="fas fa-envelope text-[15px]"></i>
                </div>
                <span>Newsletter</span>
            </a>
            
            <a href="logs.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= $current_page == 'logs.php' ? 'bg-zinc-800/60 text-white shadow-sm' : 'hover:bg-zinc-800/40 hover:text-zinc-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $current_page == 'logs.php' ? 'bg-brand-gold/15 text-brand-gold' : 'text-zinc-500' ?>">
                    <i class="fas fa-history text-[15px]"></i>
                </div>
                <span>Nhật Ký</span>
            </a>
            
            <a href="settings.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= $current_page == 'settings.php' ? 'bg-zinc-800/60 text-white shadow-sm' : 'hover:bg-zinc-800/40 hover:text-zinc-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $current_page == 'settings.php' ? 'bg-brand-gold/15 text-brand-gold' : 'text-zinc-500' ?>">
                    <i class="fas fa-cog text-[15px]"></i>
                </div>
                <span>Cài Đặt</span>
            </a>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-zinc-800/40">
        <div class="flex items-center gap-3 px-2">
            <img src="<?= htmlspecialchars(user_avatar_url($_SESSION['user_avatar'] ?? '', $_SESSION['user_name'] ?? 'Admin')) ?>" alt="Admin" class="w-8 h-8 rounded-full object-cover ring-2 ring-zinc-700">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-zinc-200 truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
                <p class="text-[10px] text-zinc-500">Quản trị viên</p>
            </div>
            <a href="../auth.php?action=logout" class="text-zinc-500 hover:text-red-400 transition-colors" title="Đăng xuất">
                <i class="fas fa-sign-out-alt text-sm"></i>
            </a>
        </div>
    </div>
</aside>

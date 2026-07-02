<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<aside class="sidebar-admin w-[260px] bg-[#0f172a] text-slate-400 flex flex-col sticky top-0 h-screen z-20 border-r border-slate-800/60">
    
    <!-- Logo Area -->
    <div class="h-16 flex items-center px-5 border-b border-slate-800/60">
        <a href="index.php" class="flex items-center gap-2.5 group">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-bolt text-white text-sm"></i>
            </div>
            <div>
                <span class="font-display text-[15px] font-bold text-white tracking-wide">TechNova</span>
                <span class="text-[10px] font-semibold text-cyan-400 ml-1 uppercase tracking-widest">Admin</span>
            </div>
        </a>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 py-5 px-3 overflow-y-auto admin-scroll">

        <!-- Main Group -->
        <div class="mb-6">
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500/80">Chính</p>
            
            <a href="index.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= $current_page == 'index.php' ? 'bg-slate-700/50 text-white shadow-sm' : 'hover:bg-slate-800/50 hover:text-slate-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $current_page == 'index.php' ? 'bg-blue-500/15 text-blue-400' : 'text-slate-500' ?>">
                    <i class="fas fa-chart-pie text-[15px]"></i>
                </div>
                <span>Bảng Điều Khiển</span>
                <?php if($current_page == 'index.php'): ?>
                <div class="ml-auto w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                <?php endif; ?>
            </a>
            
            <a href="products.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'products.php' || $current_page == 'product_form.php') ? 'bg-slate-700/50 text-white shadow-sm' : 'hover:bg-slate-800/50 hover:text-slate-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'products.php' || $current_page == 'product_form.php') ? 'bg-blue-500/15 text-blue-400' : 'text-slate-500' ?>">
                    <i class="fas fa-box text-[15px]"></i>
                </div>
                <span>Sản Phẩm</span>
            </a>
            
            <a href="orders.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'orders.php' || $current_page == 'order_detail.php') ? 'bg-slate-700/50 text-white shadow-sm' : 'hover:bg-slate-800/50 hover:text-slate-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'orders.php' || $current_page == 'order_detail.php') ? 'bg-blue-500/15 text-blue-400' : 'text-slate-500' ?>">
                    <i class="fas fa-shopping-bag text-[15px]"></i>
                </div>
                <span>Đơn Hàng</span>
            </a>

            <a href="users.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'users.php' || $current_page == 'user_detail.php' || $current_page == 'user_form.php') ? 'bg-slate-700/50 text-white shadow-sm' : 'hover:bg-slate-800/50 hover:text-slate-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'users.php' || $current_page == 'user_detail.php' || $current_page == 'user_form.php') ? 'bg-blue-500/15 text-blue-400' : 'text-slate-500' ?>">
                    <i class="fas fa-users text-[15px]"></i>
                </div>
                <span>Khách Hàng</span>
            </a>

            <a href="categories.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'categories.php' || $current_page == 'category_form.php') ? 'bg-slate-700/50 text-white shadow-sm' : 'hover:bg-slate-800/50 hover:text-slate-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'categories.php' || $current_page == 'category_form.php') ? 'bg-blue-500/15 text-blue-400' : 'text-slate-500' ?>">
                    <i class="fas fa-tags text-[15px]"></i>
                </div>
                <span>Danh Mục</span>
            </a>

            <a href="coupons.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= ($current_page == 'coupons.php' || $current_page == 'coupon_form.php') ? 'bg-slate-700/50 text-white shadow-sm' : 'hover:bg-slate-800/50 hover:text-slate-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= ($current_page == 'coupons.php' || $current_page == 'coupon_form.php') ? 'bg-blue-500/15 text-blue-400' : 'text-slate-500' ?>">
                    <i class="fas fa-ticket-alt text-[15px]"></i>
                </div>
                <span>Khuyến Mãi</span>
            </a>
        </div>

        <!-- System Group -->
        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.15em] text-slate-500/80">Hệ Thống</p>

            <a href="newsletter.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= $current_page == 'newsletter.php' ? 'bg-slate-700/50 text-white shadow-sm' : 'hover:bg-slate-800/50 hover:text-slate-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $current_page == 'newsletter.php' ? 'bg-blue-500/15 text-blue-400' : 'text-slate-500' ?>">
                    <i class="fas fa-envelope text-[15px]"></i>
                </div>
                <span>Newsletter</span>
            </a>
            
            <a href="logs.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= $current_page == 'logs.php' ? 'bg-slate-700/50 text-white shadow-sm' : 'hover:bg-slate-800/50 hover:text-slate-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $current_page == 'logs.php' ? 'bg-blue-500/15 text-blue-400' : 'text-slate-500' ?>">
                    <i class="fas fa-history text-[15px]"></i>
                </div>
                <span>Nhật Ký</span>
            </a>
            
            <a href="settings.php" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-0.5 text-[13px] font-medium transition-all duration-200 <?= $current_page == 'settings.php' ? 'bg-slate-700/50 text-white shadow-sm' : 'hover:bg-slate-800/50 hover:text-slate-200' ?>">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center <?= $current_page == 'settings.php' ? 'bg-blue-500/15 text-blue-400' : 'text-slate-500' ?>">
                    <i class="fas fa-cog text-[15px]"></i>
                </div>
                <span>Cài Đặt</span>
            </a>
        </div>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 border-t border-slate-800/60">
        <div class="flex items-center gap-3 px-2">
            <img src="<?= htmlspecialchars(user_avatar_url($_SESSION['user_avatar'] ?? '', $_SESSION['user_name'] ?? 'Admin')) ?>" alt="Admin" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-700">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-slate-200 truncate"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></p>
                <p class="text-[10px] text-slate-500">Quản trị viên</p>
            </div>
            <a href="../auth.php?action=logout" class="text-slate-500 hover:text-red-400 transition-colors" title="Đăng xuất">
                <i class="fas fa-sign-out-alt text-sm"></i>
            </a>
        </div>
    </div>
</aside>

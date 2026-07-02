<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!-- Sidebar -->
<aside class="w-64 bg-brand-black text-gray-300 flex flex-col transition-all duration-300 sticky top-0 h-screen">
    <div class="h-16 flex items-center justify-center border-b border-gray-800">
        <a href="index.php" class="font-display text-2xl text-white tracking-widest font-black">TECHNOVA <span class="text-brand-accent">ADMIN</span></a>
    </div>
    
    <nav class="flex-1 py-8 px-4 space-y-2">
        <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $current_page == 'index.php' ? 'bg-brand-primary text-white' : 'hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-chart-pie w-5"></i>
            <span class="text-sm uppercase tracking-widest font-bold">Bảng Điều Khiển</span>
        </a>
        <a href="products.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $current_page == 'products.php' || $current_page == 'product_form.php' ? 'bg-brand-primary text-white' : 'hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-box w-5"></i>
            <span class="text-sm uppercase tracking-widest font-bold">Sản Phẩm</span>
        </a>
        <a href="users.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $current_page == 'users.php' || $current_page == 'user_detail.php' || $current_page == 'user_form.php' ? 'bg-brand-primary text-white' : 'hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-users w-5"></i>
            <span class="text-sm uppercase tracking-widest font-bold">Khách Hàng</span>
        </a>
        <a href="orders.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $current_page == 'orders.php' || $current_page == 'order_detail.php' ? 'bg-brand-primary text-white' : 'hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-shopping-cart w-5"></i>
            <span class="text-sm uppercase tracking-widest font-bold">Đơn Hàng</span>
        </a>
        <a href="categories.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $current_page == 'categories.php' || $current_page == 'category_form.php' ? 'bg-brand-primary text-white' : 'hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-tags w-5"></i>
            <span class="text-sm uppercase tracking-widest font-bold">Danh Mục</span>
        </a>
        <a href="coupons.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $current_page == 'coupons.php' || $current_page == 'coupon_form.php' ? 'bg-brand-primary text-white' : 'hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-ticket-alt w-5"></i>
            <span class="text-sm uppercase tracking-widest font-bold">Khuyến Mãi</span>
        </a>
        <a href="newsletter.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $current_page == 'newsletter.php' ? 'bg-brand-primary text-white' : 'hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-envelope w-5"></i>
            <span class="text-sm uppercase tracking-widest font-bold">Newsletter</span>
        </a>
        <a href="logs.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $current_page == 'logs.php' ? 'bg-brand-primary text-white' : 'hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-history w-5"></i>
            <span class="text-sm uppercase tracking-widest font-bold">Nhật Ký</span>
        </a>
        <a href="settings.php" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors <?= $current_page == 'settings.php' ? 'bg-brand-primary text-white' : 'hover:bg-gray-800 hover:text-white' ?>">
            <i class="fas fa-cog w-5"></i>
            <span class="text-sm uppercase tracking-widest font-bold">Cài Đặt</span>
        </a>
    </nav>

    <div class="p-4 border-t border-gray-800 text-xs text-center text-gray-500 font-bold uppercase tracking-widest">
        &copy; 2026 TECHNOVA
    </div>
</aside>

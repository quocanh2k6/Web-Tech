<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db_connect.php';

$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNova Store - Công Nghệ Đỉnh Cao</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700;800;900&family=Inter:wght@300;400;500;600&subset=vietnamese&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              black:  '#050505',
              card:   '#0f0f0f',
              surface:'#1a1a1a',
              border: '#2a2a2a',
              gold:   '#C9A84C',
              'gold-light': '#E8C97E',
              silver: '#A8B2C3',
              white:  '#F5F5F7',
              sub:    '#888888',
            }
          },
          fontFamily: {
            display: ['"Be Vietnam Pro"', 'sans-serif'],
            body:    ['"Inter"', 'sans-serif'],
          }
        }
      }
    }
    </script>

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-brand-black text-brand-white font-body">

<!-- LOADING SCREEN -->
<div id="loader">
  <div>
    <div class="loader-logo">TECHNOVA</div>
    <div class="loader-bar" style="width:100%"></div>
  </div>
</div>

<!-- TOAST -->
<div id="toast" role="alert">
  <i id="toast-icon" class="fas fa-check-circle text-brand-gold"></i>
  <span id="toast-message"></span>
</div>

<!-- HEADER -->
<header id="header">
  <div class="max-w-screen-xl mx-auto flex items-center justify-between">
    <!-- Logo -->
    <a href="index.php" class="brand-logo">TECHNOVA</a>

    <!-- Nav desktop -->
    <nav class="hidden md:flex items-center gap-8">
      <a href="index.php" class="nav-link">Trang Chủ</a>
      <a href="shop.php" class="nav-link">Sản Phẩm</a>
      <a href="about.php" class="nav-link">Về Chúng Tôi</a>
      <a href="careers.php" class="nav-link">Tuyển Dụng</a>
      <a href="support.php" class="nav-link">Liên Hệ</a>
    </nav>

    <!-- Right icons -->
    <div class="flex items-center gap-5">
      <!-- Search trigger -->
      <button id="search-btn" class="text-brand-sub hover:text-brand-white transition-colors">
        <i class="fas fa-search text-sm"></i>
      </button>
      <!-- Cart -->
      <a href="cart.php" class="relative text-brand-sub hover:text-brand-white transition-colors">
        <i class="fas fa-shopping-bag text-sm"></i>
        <span id="cart-badge" class="absolute -top-2 -right-2 bg-brand-gold text-black text-[10px] font-bold w-4 h-4 flex items-center justify-center rounded-full <?= $cart_count > 0 ? '' : 'hidden' ?> transition-transform duration-300">
            <?= $cart_count ?>
        </span>
      </a>
      <!-- User -->
      <a href="profile.php" class="text-brand-sub hover:text-brand-white transition-colors">
        <i class="fas fa-user text-sm"></i>
      </a>
      <!-- Mobile menu -->
      <button id="menu-btn" class="md:hidden text-brand-sub hover:text-brand-white">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </div>
</header>

<!-- MOBILE SIDEBAR -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 opacity-0 pointer-events-none transition-opacity duration-300"></div>
<aside id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-brand-card z-50 transform -translate-x-full transition-transform duration-300 flex flex-col border-r border-brand-border">
    <div class="p-6 border-b border-brand-border flex justify-between items-center">
        <a href="index.php" class="brand-logo">TECHNOVA</a>
        <button id="close-menu-btn" class="text-brand-sub hover:text-brand-white focus:outline-none">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    
    <nav class="flex-grow p-6 flex flex-col space-y-4">
        <a href="index.php" class="text-brand-white font-medium hover:text-brand-gold transition-colors">Trang Chủ</a>
        <a href="shop.php" class="text-brand-white font-medium hover:text-brand-gold transition-colors">Sản Phẩm</a>
        <a href="about.php" class="text-brand-white font-medium hover:text-brand-gold transition-colors">Về Chúng Tôi</a>
        <a href="careers.php" class="text-brand-white font-medium hover:text-brand-gold transition-colors">Tuyển Dụng</a>
        <a href="support.php" class="text-brand-white font-medium hover:text-brand-gold transition-colors">Liên Hệ</a>
        <hr class="border-brand-border">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php" class="text-brand-white font-medium hover:text-brand-gold transition-colors">Tài Khoản</a>
            <?php if($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2): ?>
                <a href="admin/index.php" class="text-brand-gold font-bold transition-colors">Quản Trị Viên</a>
            <?php endif; ?>
            <a href="logout.php" class="text-red-400 font-medium hover:text-red-300 transition-colors">Đăng Xuất</a>
        <?php else: ?>
            <a href="auth.php" class="text-brand-white font-medium hover:text-brand-gold transition-colors">Đăng Nhập</a>
        <?php endif; ?>
    </nav>
</aside>

<!-- SEARCH OVERLAY -->
<div id="search-overlay" class="fixed inset-0 bg-black/95 backdrop-blur-sm z-50 hidden flex items-center justify-center">
  <div class="w-full max-w-2xl px-6">
    <div class="border-b border-brand-border pb-4 flex items-center gap-4">
      <i class="fas fa-search text-brand-gold"></i>
      <input type="text" id="search-input" placeholder="Tìm sản phẩm..." 
        class="flex-1 bg-transparent text-brand-white text-xl outline-none font-body placeholder-brand-sub">
      <button id="close-search" class="text-brand-sub hover:text-brand-white">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <!-- search results -->
    <div id="search-results" class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4 max-h-[60vh] overflow-y-auto pr-2"></div>
  </div>
</div>

<script>
const searchBtn = document.getElementById('search-btn');
const searchOverlay = document.getElementById('search-overlay');
const closeSearch = document.getElementById('close-search');
const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');
let searchTimeout;

if(searchBtn && searchOverlay) {
  searchBtn.addEventListener('click', () => { searchOverlay.classList.remove('hidden'); searchInput?.focus(); });
  closeSearch?.addEventListener('click', () => searchOverlay.classList.add('hidden'));
  searchOverlay.addEventListener('click', e => { if(e.target === searchOverlay) searchOverlay.classList.add('hidden'); });

  searchInput?.addEventListener('input', (e) => {
      clearTimeout(searchTimeout);
      const query = e.target.value.trim();
      
      if(query.length < 2) {
          searchResults.innerHTML = '';
          return;
      }
      
      searchTimeout = setTimeout(() => {
          fetch(`ajax/search_products.php?q=${encodeURIComponent(query)}`)
              .then(r => r.json())
              .then(data => {
                  if(data.length === 0) {
                      searchResults.innerHTML = '<p class="col-span-full text-brand-sub text-center py-4">Không tìm thấy sản phẩm</p>';
                      return;
                  }
                  
                  searchResults.innerHTML = data.map(product => `
                      <a href="product_detail.php?id=${product.id}" class="group block bg-brand-surface rounded-lg p-2 hover:bg-brand-border transition-colors">
                          <img src="${product.image_url}" class="w-full aspect-square object-cover rounded mb-2" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?w=500&q=80';">
                          <h4 class="text-brand-white text-sm font-medium truncate group-hover:text-brand-gold transition-colors">${product.name}</h4>
                          <p class="text-brand-gold font-display text-sm font-bold mt-1">${new Intl.NumberFormat('vi-VN').format(product.price)}đ</p>
                      </a>
                  `).join('');
              });
      }, 300);
  });
}
</script>

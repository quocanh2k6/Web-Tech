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
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link rel="apple-touch-icon" href="assets/favicon.png">

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
<div id="search-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 hidden flex flex-col items-center justify-start pt-32 transition-opacity duration-300 opacity-0">
  <div class="w-full max-w-2xl px-6 relative">
    <!-- Search Input Area -->
    <div class="bg-brand-surface border border-brand-border rounded-xl flex items-center gap-4 px-6 py-4 shadow-lg relative z-10">
      <i class="fas fa-search text-brand-gold text-xl"></i>
      <input type="text" id="search-input" placeholder="Nhập từ khóa tìm kiếm..." 
        class="flex-1 bg-transparent text-brand-white text-lg outline-none font-body placeholder-brand-sub" autocomplete="off">
      <button id="close-search" class="text-brand-sub hover:text-brand-gold transition-colors">
        <i class="fas fa-times text-xl"></i>
      </button>
    </div>
    
    <!-- Search Dropdown Results -->
    <div id="search-results" class="absolute top-full left-6 right-6 mt-2 bg-brand-surface border border-brand-border rounded-xl shadow-2xl z-0 max-h-[400px] overflow-y-auto transform translate-y-[-10px] opacity-0 pointer-events-none transition-all duration-300">
      <!-- Results injected here -->
    </div>
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
  const showSearch = () => {
    searchOverlay.classList.remove('hidden');
    // slight delay for transition
    setTimeout(() => {
      searchOverlay.classList.remove('opacity-0');
      searchOverlay.classList.add('opacity-100');
      searchInput?.focus();
    }, 10);
  };
  
  const hideSearch = () => {
    searchOverlay.classList.remove('opacity-100');
    searchOverlay.classList.add('opacity-0');
    setTimeout(() => {
      searchOverlay.classList.add('hidden');
      searchInput.value = '';
      hideDropdown();
    }, 300);
  };

  const showDropdown = () => {
    searchResults.classList.remove('translate-y-[-10px]', 'opacity-0', 'pointer-events-none');
    searchResults.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
  };

  const hideDropdown = () => {
    searchResults.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
    searchResults.classList.add('translate-y-[-10px]', 'opacity-0', 'pointer-events-none');
  };

  searchBtn.addEventListener('click', showSearch);
  closeSearch?.addEventListener('click', hideSearch);
  searchOverlay.addEventListener('click', e => { if(e.target === searchOverlay) hideSearch(); });

  searchInput?.addEventListener('input', (e) => {
      clearTimeout(searchTimeout);
      const query = e.target.value.trim();
      
      if(query.length < 2) {
          hideDropdown();
          return;
      }
      
      searchTimeout = setTimeout(() => {
          fetch(`ajax/search_products.php?q=${encodeURIComponent(query)}`)
              .then(r => r.json())
              .then(data => {
                  if(data.length === 0) {
                      searchResults.innerHTML = '<div class="p-6 text-center text-brand-sub">Không tìm thấy kết quả phù hợp.</div>';
                      showDropdown();
                      return;
                  }
                  
                  searchResults.innerHTML = data.map(product => `
                      <a href="${product.link}" class="flex items-center gap-4 p-4 hover:bg-brand-border border-b border-brand-border last:border-0 transition-colors">
                          <img src="${product.image_url}" alt="${product.name}" class="w-14 h-14 object-cover rounded-md bg-brand-card">
                          <div class="flex-1">
                              <h4 class="text-brand-white text-sm font-medium hover:text-brand-gold transition-colors">${product.name}</h4>
                          </div>
                          <div class="text-brand-gold font-bold text-sm">
                              ${product.price}
                          </div>
                      </a>
                  `).join('');
                  showDropdown();
              });
      }, 300);
  });
}
</script>

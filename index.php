<?php
require_once 'config/db_connect.php';
require_once 'includes/helpers.php';

// Fetch Featured Products (Random 8)
$stmt = $conn->query("SELECT * FROM products ORDER BY RAND() LIMIT 8");
$featured_products = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<!-- HERO SECTION -->
<section class="hero">
  <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=2070&q=80')"></div>
  <div class="hero-overlay"></div>
  <div class="hero-corner"></div>

  <div class="relative z-10 max-w-screen-xl mx-auto px-6 h-full flex flex-col justify-center">
    <div class="max-w-3xl">
      <!-- eyebrow tag -->
      <p class="hero-tag mb-6">✦ BST Công Nghệ 2026</p>

      <!-- title với word-by-word animation -->
      <h1 class="hero-title mb-8">
        <span class="line"><span class="word">Công</span></span>
        <span class="line"><span class="word text-brand-gold">Nghệ</span></span>
        <span class="line"><span class="word">Đỉnh Cao</span></span>
      </h1>

      <!-- sub -->
      <p class="font-body text-brand-sub text-lg mb-10 max-w-md leading-relaxed" style="opacity:0" id="hero-sub">
        Khám phá bộ sưu tập thiết bị công nghệ cao cấp — từ iPhone, MacBook đến đồng hồ thông minh mới nhất.
      </p>

      <!-- CTAs -->
      <div class="flex flex-wrap gap-4">
        <a href="shop.php" class="hero-btn-primary">
          <span>Mua Sắm Ngay</span>
          <i class="fas fa-arrow-right text-xs"></i>
        </a>
        <a href="about.php" class="hero-btn-secondary">
          <span>Về TechNova</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Scroll indicator -->
  <div class="scroll-indicator">
    <div class="scroll-line"></div>
    <span class="font-body text-brand-sub" style="font-size:0.6rem;letter-spacing:0.2em;text-transform:uppercase">Cuộn xuống</span>
  </div>
</section>

<!-- MARQUEE -->
<div class="marquee-wrap bg-brand-card">
  <div class="marquee-track">
    <!-- Block 1 -->
    <span class="marquee-item">iPhone 16 Pro Max <span class="marquee-dot"></span></span>
    <span class="marquee-item">MacBook Pro M4 <span class="marquee-dot"></span></span>
    <span class="marquee-item">Samsung Galaxy S25 Ultra <span class="marquee-dot"></span></span>
    <span class="marquee-item">Sony WH-1000XM5 <span class="marquee-dot"></span></span>
    <span class="marquee-item">Apple Watch Series 10 <span class="marquee-dot"></span></span>
    <span class="marquee-item">ASUS ROG Zephyrus <span class="marquee-dot"></span></span>
    <span class="marquee-item">AirPods Pro 2 <span class="marquee-dot"></span></span>
    <!-- Block 2 -->
    <span class="marquee-item">iPhone 16 Pro Max <span class="marquee-dot"></span></span>
    <span class="marquee-item">MacBook Pro M4 <span class="marquee-dot"></span></span>
    <span class="marquee-item">Samsung Galaxy S25 Ultra <span class="marquee-dot"></span></span>
    <span class="marquee-item">Sony WH-1000XM5 <span class="marquee-dot"></span></span>
    <span class="marquee-item">Apple Watch Series 10 <span class="marquee-dot"></span></span>
    <span class="marquee-item">ASUS ROG Zephyrus <span class="marquee-dot"></span></span>
    <span class="marquee-item">AirPods Pro 2 <span class="marquee-dot"></span></span>
  </div>
</div>

<!-- CATEGORIES -->
<section class="py-24 px-6 bg-brand-black">
  <div class="max-w-screen-xl mx-auto">
    <div class="mb-14" data-aos="fade-up">
      <p class="section-eyebrow">Danh Mục</p>
      <div class="section-divider"></div>
      <h2 class="section-title">Khám Phá<br>Theo Dòng Sản Phẩm</h2>
    </div>

    <div class="cat-grid grid grid-cols-1 md:grid-cols-3 gap-6">
      <a href="shop.php?category=1" class="cat-card" data-aos="fade-up" data-aos-delay="0">
        <div class="cat-card-bg" style="background-image:url('https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=800&q=80')"></div>
        <div class="cat-card-overlay"></div>
        <div class="cat-card-content">
          <p class="section-eyebrow mb-2">01</p>
          <h3 class="cat-card-title">Điện Thoại & Tablet</h3>
          <div class="cat-card-cta">Khám phá <i class="fas fa-arrow-right text-xs"></i></div>
        </div>
      </a>
      <a href="shop.php?category=2" class="cat-card" data-aos="fade-up" data-aos-delay="100">
        <div class="cat-card-bg" style="background-image:url('https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=800&q=80')"></div>
        <div class="cat-card-overlay"></div>
        <div class="cat-card-content">
          <p class="section-eyebrow mb-2">02</p>
          <h3 class="cat-card-title">Laptop & Máy Tính</h3>
          <div class="cat-card-cta">Khám phá <i class="fas fa-arrow-right text-xs"></i></div>
        </div>
      </a>
      <a href="shop.php?category=3" class="cat-card" data-aos="fade-up" data-aos-delay="200">
        <div class="cat-card-bg" style="background-image:url('https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80')"></div>
        <div class="cat-card-overlay"></div>
        <div class="cat-card-content">
          <p class="section-eyebrow mb-2">03</p>
          <h3 class="cat-card-title">Âm Thanh & Phụ Kiện</h3>
          <div class="cat-card-cta">Khám phá <i class="fas fa-arrow-right text-xs"></i></div>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="py-24 px-6 bg-brand-black">
    <div class="max-w-screen-xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-14 gap-6">
            <div data-aos="fade-right">
                <p class="section-eyebrow">Sản Phẩm</p>
                <div class="section-divider"></div>
                <h2 class="section-title">Nổi Bật Nhất</h2>
            </div>
            
            <div class="flex gap-4" data-aos="fade-left">
                <button id="prev-btn" class="w-12 h-12 rounded-full border border-brand-border flex items-center justify-center text-brand-white hover:border-brand-gold hover:text-brand-gold transition-colors focus:outline-none">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <button id="next-btn" class="w-12 h-12 rounded-full border border-brand-border flex items-center justify-center text-brand-white hover:border-brand-gold hover:text-brand-gold transition-colors focus:outline-none">
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <div class="swiper featured-swiper" data-aos="fade-up">
            <div class="swiper-wrapper">
                <?php foreach($featured_products as $product): ?>
                    <div class="swiper-slide !w-[280px] md:!w-[320px]">
                        <div class="product-card">
                            <div class="product-img-wrap">
                                <a href="product_detail.php?id=<?= $product['id'] ?>">
                                    <img src="<?= htmlspecialchars(asset_image_url($product['image_url'])) ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';">
                                </a>
                                <!-- <div class="product-badge">HOT</div> -->
                                <div class="product-quick-add" onclick="addToCart(<?= $product['id'] ?>)">THÊM VÀO GIỎ</div>
                            </div>
                            <div class="p-5">
                                <a href="product_detail.php?id=<?= $product['id'] ?>" class="block">
                                    <h3 class="product-name mb-2 truncate"><?= htmlspecialchars($product['name']) ?></h3>
                                    <div class="product-price"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</div>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="py-20 bg-brand-card border-y border-brand-border">
  <div class="max-w-screen-xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
    <div data-aos="fade-up" data-aos-delay="0">
      <p class="font-display font-800 text-4xl text-brand-gold" data-count="15000">0</p>
      <p class="font-body text-brand-sub text-sm mt-2 uppercase tracking-widest">Khách Hàng</p>
    </div>
    <div data-aos="fade-up" data-aos-delay="100">
      <p class="font-display font-800 text-4xl text-brand-gold" data-count="500">0</p>
      <p class="font-body text-brand-sub text-sm mt-2 uppercase tracking-widest">Sản Phẩm</p>
    </div>
    <div data-aos="fade-up" data-aos-delay="200">
      <p class="font-display font-800 text-4xl text-brand-gold" data-count="99">0</p>
      <p class="font-body text-brand-sub text-sm mt-2 uppercase tracking-widest">% Chính Hãng</p>
    </div>
    <div data-aos="fade-up" data-aos-delay="300">
      <p class="font-display font-800 text-4xl text-brand-gold" data-count="5">0</p>
      <p class="font-body text-brand-sub text-sm mt-2 uppercase tracking-widest">Năm Kinh Nghiệm</p>
    </div>
  </div>
</section>

<!-- PARALLAX BANNER -->
<section class="parallax-banner">
  <div class="parallax-bg" style="background-image:url('https://images.unsplash.com/photo-1585792180666-f7347c490ee2?auto=format&fit=crop&w=2070&q=80')"></div>
  <div class="parallax-overlay"></div>
  <div class="parallax-text relative z-10 h-full flex flex-col items-center justify-center text-center px-6">
    <p class="section-eyebrow mb-4">Ưu Đãi Đặc Biệt</p>
    <h2 class="font-display font-800 text-5xl md:text-7xl text-brand-white mb-6">
      Trải Nghiệm<br><span class="text-brand-gold">Khác Biệt</span>
    </h2>
    <p class="font-body text-brand-sub text-lg mb-10 max-w-lg">
      Bảo hành chính hãng toàn quốc — Giao hàng trong 24 giờ — Đổi trả 30 ngày
    </p>
    <a href="shop.php" class="btn-gold">
      <span>Mua Ngay</span>
      <i class="fas fa-arrow-right text-xs"></i>
    </a>
  </div>
</section>

<!-- NEWSLETTER SECTION -->
<section class="py-20 bg-brand-black px-6">
  <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row items-center justify-between gap-10">
    <div data-aos="fade-right">
      <p class="section-eyebrow mb-3">Newsletter</p>
      <h2 class="font-display font-700 text-3xl text-brand-white">Nhận Ưu Đãi<br>Mới Nhất</h2>
    </div>
    <form id="newsletter-form" class="flex gap-0 w-full max-w-md" data-aos="fade-left">
      <input type="email" id="newsletter-email" placeholder="Email của bạn" 
        class="form-input flex-1" required>
      <button type="submit" id="newsletter-submit" class="btn-gold whitespace-nowrap">
        Đăng Ký
      </button>
    </form>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

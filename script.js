// ===== LOADING SCREEN =====
window.addEventListener('load', () => {
  setTimeout(() => {
    const loader = document.getElementById('loader');
    if (loader) loader.classList.add('hidden');
  }, 800);
});

// ===== AOS INIT =====
AOS.init({
  duration: 900,
  easing: 'cubic-bezier(0.16, 1, 0.3, 1)',
  once: true,
  offset: 60,
});

// ===== GSAP + ScrollTrigger =====
gsap.registerPlugin(ScrollTrigger);

// Header scroll effect
const header = document.getElementById('header');
if (header) {
  window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 60);
  });
}

// Mobile sidebar
const menuBtn = document.getElementById('menu-btn');
const closeMenuBtn = document.getElementById('close-menu-btn');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');
if (menuBtn && sidebar) {
  menuBtn.addEventListener('click', () => {
    sidebar.classList.remove('-translate-x-full');
    sidebarOverlay?.classList.remove('opacity-0', 'pointer-events-none');
    document.body.style.overflow = 'hidden';
  });
  const close = () => {
    sidebar.classList.add('-translate-x-full');
    sidebarOverlay?.classList.add('opacity-0', 'pointer-events-none');
    document.body.style.overflow = '';
  };
  closeMenuBtn?.addEventListener('click', close);
  sidebarOverlay?.addEventListener('click', close);
}

// ===== HERO ANIMATIONS =====
const heroTl = gsap.timeline({ delay: 0.9 });
heroTl
  .to('.hero-tag', { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' })
  .to('.hero-title .word', {
    y: '0%', opacity: 1, duration: 1.1, stagger: 0.15, ease: 'power4.out'
  }, '-=0.4')
  .to('.hero-btn-primary', { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }, '-=0.5')
  .to('.hero-btn-secondary', { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }, '-=0.6')
  .to('.scroll-indicator', { opacity: 1, duration: 0.6 }, '-=0.4');

// Parallax hero bg
gsap.to('.hero-bg', {
  yPercent: 25, ease: 'none',
  scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true }
});

// ===== PARALLAX BANNER =====
gsap.to('.parallax-bg', {
  yPercent: 20, ease: 'none',
  scrollTrigger: { trigger: '.parallax-banner', start: 'top bottom', end: 'bottom top', scrub: true }
});
gsap.to('.parallax-text', {
  yPercent: -40, ease: 'none',
  scrollTrigger: { trigger: '.parallax-banner', start: 'top bottom', end: 'bottom top', scrub: true }
});

// ===== NUMBER COUNTER ANIMATION =====
// Áp dụng cho các số liệu thống kê (vd: 10,000+ khách hàng)
const counters = document.querySelectorAll('[data-count]');
counters.forEach(el => {
  const target = +el.getAttribute('data-count');
  ScrollTrigger.create({
    trigger: el,
    start: 'top 85%',
    once: true,
    onEnter: () => {
      gsap.fromTo(el, { textContent: 0 }, {
        textContent: target, duration: 2, ease: 'power2.out',
        snap: { textContent: 1 },
        onUpdate: function() { el.textContent = Math.round(this.targets()[0].textContent).toLocaleString('vi-VN'); }
      });
    }
  });
});

// ===== SWIPER CAROUSELS =====
if (document.querySelector('.featured-swiper')) {
  new Swiper('.featured-swiper', {
    loop: true,
    slidesPerView: 'auto',
    spaceBetween: 24,
    grabCursor: true,
    speed: 900,
    navigation: { nextEl: '#next-btn', prevEl: '#prev-btn' },
  });
}
if (document.querySelector('.accessory-swiper')) {
  new Swiper('.accessory-swiper', {
    loop: true,
    slidesPerView: 'auto',
    spaceBetween: 24,
    grabCursor: true,
    speed: 900,
    navigation: { nextEl: '#next-acc-btn', prevEl: '#prev-acc-btn' },
  });
}

// ===== VANILLA TILT (category cards) =====
const tiltTargets = document.querySelectorAll('.cat-card');
if (tiltTargets.length) {
  VanillaTilt.init(tiltTargets, {
    max: 6, speed: 500, glare: true, 'max-glare': 0.15
  });
}

// ===== TOAST =====
function showToast(message, type = 'success') {
  const toast = document.getElementById('toast');
  const toastMsg = document.getElementById('toast-message');
  const icon = document.getElementById('toast-icon');
  if (!toast) return;
  toastMsg.textContent = message;
  if (icon) {
    icon.className = type === 'success'
      ? 'fas fa-check-circle text-brand-gold'
      : 'fas fa-exclamation-circle text-red-500';
  }
  toast.classList.add('show');
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove('show'), 3200);
}

// ===== CART =====
function addToCart(productId, quantity = 1) {
  fetch('ajax/add_to_cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ product_id: productId, quantity })
  })
  .then(r => r.json())
  .then(data => {
    if (data.redirect) { window.location.href = data.redirect; return; }
    showToast(data.message, data.status === 'success' ? 'success' : 'error');
    updateCartBadge(data.cart_count);
  })
  .catch(() => showToast('Đã có lỗi xảy ra. Vui lòng thử lại.', 'error'));
}

function updateCartBadge(count) {
  const badge = document.getElementById('cart-badge');
  if (!badge) return;
  badge.textContent = count;
  badge.classList.toggle('hidden', count <= 0);
  // Hiệu ứng bounce nhỏ khi thêm vào giỏ
  badge.classList.add('scale-125');
  setTimeout(() => badge.classList.remove('scale-125'), 300);
}

// ===== NEWSLETTER =====
function handleNewsletterSignup() {
  const form = document.getElementById('newsletter-form');
  const emailInput = document.getElementById('newsletter-email');
  const submitBtn = document.getElementById('newsletter-submit');
  if (!form || !emailInput || !submitBtn) return;
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    submitBtn.disabled = true;
    submitBtn.textContent = 'Đang gửi...';
    try {
      const res = await fetch('ajax/subscribe_newsletter.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: emailInput.value })
      });
      const data = await res.json();
      showToast(data.message, data.status === 'success' ? 'success' : 'error');
      if (data.status === 'success') emailInput.value = '';
    } catch {
      showToast('Đã có lỗi. Vui lòng thử lại.', 'error');
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = 'Đăng Ký';
    }
  });
}
handleNewsletterSignup();

gsap.to('#hero-sub', { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out', delay: 2.0 });

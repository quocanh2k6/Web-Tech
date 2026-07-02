<?php
require_once 'db_connect.php';
require_once 'includes/header.php';
?>

    <!-- Support Hero -->
    <section class="relative pt-40 pb-20 px-8 w-full flex flex-col items-center justify-center bg-brand-surface text-center border-b border-brand-border">
        <div class="w-16 h-16 bg-brand-gold text-black rounded-full flex items-center justify-center mb-6 shadow-md text-2xl">
            <i class="fas fa-headset"></i>
        </div>
        <h1 class="font-display text-5xl md:text-6xl font-black mb-6 text-brand-white">Hỗ Trợ TechNova</h1>
        <p class="font-body text-lg text-brand-sub max-w-xl mx-auto leading-relaxed">
            Bạn cần tư vấn sản phẩm, hỗ trợ kỹ thuật hay thông tin đơn hàng? Đội ngũ TechNova luôn sẵn sàng hỗ trợ.
        </p>
    </section>

    <!-- Support Content Tabs -->
    <section class="py-16 px-6 md:px-12 w-full bg-brand-bg min-h-[60vh]">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-12">
            
            <!-- Sidebar Navigation -->
            <div class="w-full md:w-1/4">
                <div class="bg-brand-surface p-6 rounded-xl border border-brand-border sticky top-32 shadow-sm">
                    <ul class="space-y-3 font-body font-medium">
                        <li>
                            <button class="support-tab-btn w-full text-left px-5 py-4 rounded-lg text-sm transition-colors bg-brand-gold text-black shadow-[0_0_15px_rgba(201,168,76,0.2)] font-bold tracking-wide" data-target="tab-contact">
                                <i class="fas fa-address-book mr-3"></i> Thông Tin Liên Hệ
                            </button>
                        </li>
                        <li>
                            <button class="support-tab-btn w-full text-left px-5 py-4 rounded-lg text-sm transition-colors text-brand-sub hover:bg-[#1a1a1a] hover:text-brand-white" data-target="tab-form">
                                <i class="fas fa-envelope mr-3"></i> Gửi Tin Nhắn
                            </button>
                        </li>
                        <li>
                            <button class="support-tab-btn w-full text-left px-5 py-4 rounded-lg text-sm transition-colors text-brand-sub hover:bg-[#1a1a1a] hover:text-brand-white" data-target="tab-returns" id="warranty">
                                <i class="fas fa-undo mr-3"></i> Bảo Hành & Đổi Trả
                            </button>
                        </li>
                        <li>
                            <button class="support-tab-btn w-full text-left px-5 py-4 rounded-lg text-sm transition-colors text-brand-sub hover:bg-[#1a1a1a] hover:text-brand-white" data-target="tab-faq" id="faq">
                                <i class="fas fa-question-circle mr-3"></i> Câu Hỏi Thường Gặp
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full md:w-3/4">
                <!-- Tab: Contact Info -->
                <div id="tab-contact" class="support-tab-content block animate-[fadeIn_0.3s_ease-out]">
                    <h2 class="font-display text-4xl font-black mb-6 text-brand-white">Kết Nối Với Chúng Tôi</h2>
                    <p class="text-brand-sub font-body mb-10 leading-relaxed text-lg">
                        Đội ngũ hỗ trợ khách hàng hoạt động từ Thứ Hai đến Thứ Bảy (9:00 AM - 6:00 PM). Hãy liên hệ qua các kênh chính thức dưới đây.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="bg-brand-card p-8 rounded-xl border border-brand-border hover:border-brand-gold/50 transition-colors">
                            <div class="w-12 h-12 bg-brand-surface rounded-full flex items-center justify-center border border-brand-border text-brand-gold mb-6 text-xl">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <h3 class="font-bold text-xl mb-2 text-brand-white">Hotline Hỗ Trợ</h3>
                            <p class="text-brand-sub font-body font-medium">1900-xxxx</p>
                        </div>
                        <div class="bg-brand-card p-8 rounded-xl border border-brand-border hover:border-brand-gold/50 transition-colors">
                            <div class="w-12 h-12 bg-brand-surface rounded-full flex items-center justify-center border border-brand-border text-brand-gold mb-6 text-xl">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                            <h3 class="font-bold text-xl mb-2 text-brand-white">Email Hỗ Trợ</h3>
                            <p class="text-brand-sub font-body font-medium">support@technova.vn</p>
                            <p class="text-brand-sub font-body font-medium mt-4">123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh</p>
                        </div>
                    </div>
                </div>

                <!-- Tab: Contact Form -->
                <div id="tab-form" class="support-tab-content hidden animate-[fadeIn_0.3s_ease-out]">
                    <h2 class="font-display text-4xl font-black mb-6 text-brand-white">Gửi Tin Nhắn</h2>
                    <p class="text-brand-sub font-body mb-8 leading-relaxed text-lg">
                        Bạn có câu hỏi về cấu hình, thông số hay tình trạng đơn hàng? Hãy gửi tin nhắn, chúng tôi sẽ phản hồi trong vòng 24 giờ.
                    </p>
                    
                    <div id="form-message" class="hidden p-4 rounded-md mb-6 text-sm flex items-center gap-3 font-medium"></div>

                    <form id="contactForm" class="bg-brand-card p-8 rounded-xl border border-brand-border shadow-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold mb-2 text-brand-sub uppercase tracking-wide">Họ và Tên *</label>
                                <input type="text" name="name" id="c_name" required class="form-input">
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2 text-brand-sub uppercase tracking-wide">Địa chỉ Email *</label>
                                <input type="email" name="email" id="c_email" required class="form-input">
                            </div>
                        </div>
                        <div class="mb-8">
                            <label class="block text-sm font-bold mb-2 text-brand-sub uppercase tracking-wide">Nội dung *</label>
                            <textarea name="message" id="c_message" rows="6" required class="form-input"></textarea>
                        </div>
                        <button type="submit" id="btn-submit" class="btn-gold rounded-md inline-flex min-w-[200px]">
                            <span>Gửi Tin Nhắn</span>
                        </button>
                    </form>
                </div>

                <!-- Tab: Returns & Warranty -->
                <div id="tab-returns" class="support-tab-content hidden animate-[fadeIn_0.3s_ease-out]">
                    <h2 class="font-display text-4xl font-black mb-8 text-brand-white">Bảo Hành & Đổi Trả</h2>
                    <div class="prose prose-lg max-w-none text-brand-sub font-body leading-relaxed space-y-6">
                        <p class="text-xl font-medium text-brand-white">Chúng tôi cam kết chất lượng sản phẩm. Nếu bạn gặp vấn đề, chính sách bảo hành và đổi trả của chúng tôi vô cùng đơn giản.</p>
                        
                        <div class="bg-brand-surface p-6 rounded-lg border border-brand-border my-8">
                            <h4 class="text-brand-white font-bold text-xl mb-3 flex items-center gap-2"><i class="fas fa-shield-alt text-brand-gold"></i> Cam Kết Của TechNova</h4>
                            <p class="text-sm">Chúng tôi đảm bảo chất lượng mọi sản phẩm bán ra. Nếu thiết bị gặp lỗi từ nhà sản xuất, bạn có thể mang đến cửa hàng TechNova để được bảo hành, đổi trả hoặc hoàn tiền.</p>
                        </div>

                        <h4 class="text-brand-white font-bold text-2xl mt-8 mb-4 border-b border-brand-border pb-2">1. Chính Sách Đổi Trả</h4>
                        <ul class="list-none space-y-4">
                            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-gold mt-1"></i> <span>Chấp nhận đổi trả trong vòng <strong>30 ngày</strong> kể từ ngày mua.</span></li>
                            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-gold mt-1"></i> <span>Sản phẩm phải còn nguyên vẹn, đầy đủ phụ kiện và hộp (nếu có).</span></li>
                            <li class="flex items-start gap-3"><i class="fas fa-check text-brand-gold mt-1"></i> <span>Sản phẩm bị lỗi do người dùng (rơi vỡ, vào nước) sẽ không được bảo hành miễn phí.</span></li>
                        </ul>

                        <h4 class="text-brand-white font-bold text-2xl mt-8 mb-4 border-b border-brand-border pb-2">2. Quy Trình Đổi Trả</h4>
                        <p>Vui lòng mang sản phẩm kèm hóa đơn mua hàng đến trực tiếp cửa hàng (123 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh) hoặc liên hệ qua form để được hướng dẫn gửi ship.</p>
                        
                        <h4 class="text-brand-white font-bold text-2xl mt-8 mb-4 border-b border-brand-border pb-2">3. Hoàn Tiền</h4>
                        <p>Sau khi nhận và kiểm tra thiết bị, tiền sẽ được hoàn về tài khoản gốc của bạn trong 5-7 ngày làm việc.</p>
                    </div>
                </div>

                <!-- Tab: FAQ -->
                <div id="tab-faq" class="support-tab-content hidden animate-[fadeIn_0.3s_ease-out]">
                    <h2 class="font-display text-4xl font-black mb-8 text-brand-white">Câu Hỏi Thường Gặp</h2>
                    <div class="space-y-6">
                        <div class="bg-brand-card border border-brand-border rounded-xl p-6 shadow-sm hover:border-brand-gold/30 transition-colors">
                            <h4 class="font-display text-xl font-bold mb-3 text-brand-white flex items-center gap-3"><i class="fas fa-box text-brand-gold"></i> Làm sao để theo dõi đơn hàng?</h4>
                            <p class="text-brand-sub font-body leading-relaxed">Khi đơn hàng được giao cho đơn vị vận chuyển, bạn sẽ nhận được email có mã vận đơn. Bạn cũng có thể xem tại Lịch Sử Đơn Hàng.</p>
                        </div>
                        <div class="bg-brand-card border border-brand-border rounded-xl p-6 shadow-sm hover:border-brand-gold/30 transition-colors">
                            <h4 class="font-display text-xl font-bold mb-3 text-brand-white flex items-center gap-3"><i class="fas fa-globe text-brand-gold"></i> Cửa hàng có giao hàng toàn quốc không?</h4>
                            <p class="text-brand-sub font-body leading-relaxed">Có! Chúng tôi hợp tác với các đơn vị vận chuyển uy tín để giao hàng nhanh chóng trên toàn quốc từ 2-5 ngày.</p>
                        </div>
                        <div class="bg-brand-card border border-brand-border rounded-xl p-6 shadow-sm hover:border-brand-gold/30 transition-colors">
                            <h4 class="font-display text-xl font-bold mb-3 text-brand-white flex items-center gap-3"><i class="fas fa-recycle text-brand-gold"></i> Chính sách thu cũ đổi mới là gì?</h4>
                            <p class="text-brand-sub font-body leading-relaxed">Nếu bạn có thiết bị điện tử cũ muốn nâng cấp, hãy mang đến cửa hàng. Chúng tôi sẽ định giá và hỗ trợ bạn đổi sang sản phẩm mới với giá cực kỳ ưu đãi.</p>
                        </div>
                        <div class="bg-brand-card border border-brand-border rounded-xl p-6 shadow-sm hover:border-brand-gold/30 transition-colors">
                            <h4 class="font-display text-xl font-bold mb-3 text-brand-white flex items-center gap-3"><i class="fas fa-water text-brand-gold"></i> Làm sao để bảo quản thiết bị điện tử tốt nhất?</h4>
                            <p class="text-brand-sub font-body leading-relaxed">Tránh để thiết bị ở nơi có nhiệt độ quá cao hoặc quá ẩm. Sử dụng ốp lưng, kính cường lực và thường xuyên vệ sinh bằng khăn mềm, khô.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching logic
    const tabBtns = document.querySelectorAll('.support-tab-btn');
    const tabContents = document.querySelectorAll('.support-tab-content');

    // Handle hash in URL for direct linking
    const currentHash = window.location.hash;
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Reset buttons
            tabBtns.forEach(b => {
                b.classList.remove('bg-brand-gold', 'text-black', 'shadow-[0_0_15px_rgba(201,168,76,0.2)]');
                b.classList.add('text-brand-sub', 'hover:bg-[#1a1a1a]', 'hover:text-brand-white');
            });
            // Activate clicked button
            this.classList.remove('text-brand-sub', 'hover:bg-[#1a1a1a]', 'hover:text-brand-white');
            this.classList.add('bg-brand-gold', 'text-black', 'shadow-[0_0_15px_rgba(201,168,76,0.2)]');

            // Hide all tabs
            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('block');
            });
            // Show target tab
            const targetId = this.getAttribute('data-target');
            const targetTab = document.getElementById(targetId);
            if(targetTab) {
                targetTab.classList.remove('hidden');
                targetTab.classList.add('block');
            }
        });
    });

    if(currentHash) {
        const hashBtn = document.querySelector(`.support-tab-btn[id="${currentHash.substring(1)}"]`);
        if(hashBtn) {
            hashBtn.click();
        }
    }

    // Handle AJAX Contact Form
    const contactForm = document.getElementById('contactForm');
    const msgBox = document.getElementById('form-message');
    const btnSubmit = document.getElementById('btn-submit');
    const btnText = btnSubmit.querySelector('span');

    if(contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // UI Loading
            btnSubmit.disabled = true;
            btnText.textContent = 'Đang gửi...';
            msgBox.classList.add('hidden');
            msgBox.className = 'p-4 rounded-md mb-6 text-sm flex items-center gap-3 font-medium hidden'; // Reset classes

            const data = {
                name: document.getElementById('c_name').value,
                email: document.getElementById('c_email').value,
                message: document.getElementById('c_message').value
            };

            fetch('ajax/process_contact.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                msgBox.classList.remove('hidden');
                if (result.status === 'success') {
                    msgBox.classList.add('bg-green-900/30', 'text-green-400', 'border', 'border-green-800');
                    msgBox.innerHTML = '<i class="fas fa-check-circle"></i> Tin nhắn đã được gửi thành công! Chúng tôi sẽ phản hồi sớm nhất.';
                    contactForm.reset();
                } else {
                    msgBox.classList.add('bg-red-900/30', 'text-red-400', 'border', 'border-red-800');
                    msgBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + result.message;
                }
            })
            .catch(error => {
                msgBox.classList.remove('hidden');
                msgBox.classList.add('bg-red-900/30', 'text-red-400', 'border', 'border-red-800');
                msgBox.innerHTML = '<i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra. Vui lòng thử lại.';
            })
            .finally(() => {
                btnSubmit.disabled = false;
                btnText.textContent = 'Gửi Tin Nhắn';
            });
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>

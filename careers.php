<?php
require_once 'db_connect.php';
require_once 'includes/helpers.php';
require_once 'includes/header.php';
?>

<div class="max-w-7xl mx-auto py-24 px-6 min-h-[70vh]">
    <div class="text-center mb-16">
        <p class="text-brand-gold uppercase tracking-[0.2em] font-bold text-sm mb-3">Tuyển Dụng</p>
        <h1 class="font-display text-5xl md:text-6xl font-black text-brand-white">Cơ Hội Nghề Nghiệp Tại TechNova</h1>
        <div class="w-16 h-1 bg-brand-gold mx-auto mt-8"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-24">
        <div>
            <h2 class="font-display text-3xl font-bold mb-6 text-brand-white">Đồng hành cùng tương lai công nghệ.</h2>
            <p class="font-body text-brand-sub leading-relaxed mb-6 text-lg">
                Chúng tôi luôn tìm kiếm những Kỹ thuật viên sửa chữa, Chuyên viên tư vấn sản phẩm công nghệ, Nhân viên kho hàng và Lập trình viên website đầy nhiệt huyết để cùng xây dựng một TechNova Store vững mạnh.
            </p>
            <p class="font-body text-brand-sub leading-relaxed text-lg">
                Chúng tôi cung cấp môi trường làm việc chuyên nghiệp, mức lương cạnh tranh và cơ hội tiếp cận sớm nhất với những thiết bị điện tử tối tân.
            </p>
        </div>
        <div class="bg-brand-surface rounded-2xl overflow-hidden h-[400px] border border-brand-border">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Team working" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-700 opacity-80 hover:opacity-100">
        </div>
    </div>

    <div class="bg-brand-card border border-brand-border rounded-2xl shadow-sm p-12 text-center">
        <i class="fas fa-rocket text-5xl text-brand-gold mb-6"></i>
        <h3 class="font-display text-2xl font-bold mb-4 text-brand-white">Hiện chưa có vị trí trống</h3>
        <p class="text-brand-sub font-body max-w-lg mx-auto mb-8">
            Đội ngũ của chúng tôi hiện đã đủ nhân sự. Tuy nhiên, nếu bạn đam mê công nghệ, hãy để lại email để chúng tôi liên hệ khi có cơ hội mới.
        </p>
        <form class="max-w-md mx-auto flex flex-col sm:flex-row gap-3">
            <input type="email" placeholder="Địa chỉ email của bạn" class="flex-1 form-input">
            <button type="button" class="btn-gold rounded-md flex-shrink-0">Đăng Ký Nhận Tin</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<?php
require_once 'config/db_connect.php';
require_once 'includes/helpers.php';
require_once 'includes/header.php';
?>

<div class="w-full">
    <!-- Hero Image Area -->
    <div class="relative w-full h-[60vh] md:h-[70vh] bg-brand-gold overflow-hidden">
        <img src="https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=2000&q=80" alt="Tech Future" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-60">
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center p-6 text-white z-10">
            <h1 class="font-display text-5xl md:text-7xl font-black mb-4 uppercase tracking-tighter shadow-sm">Công Nghệ Định Hình Tương Lai</h1>
            <div class="w-24 h-2 bg-brand-white mb-6"></div>
            <p class="font-body text-xl md:text-2xl max-w-2xl font-light tracking-wide">Mang đến những sản phẩm công nghệ đột phá, kết nối mọi người với thế giới số.</p>
        </div>
    </div>

    <!-- The TechNova Story -->
    <div class="max-w-4xl mx-auto py-24 px-6 text-center">
        <p class="text-brand-gold uppercase tracking-[0.25em] text-sm font-bold mb-4">Câu Chuyện Của Chúng Tôi</p>
        <h2 class="font-display text-4xl md:text-5xl font-black mb-10 text-brand-white">Hành Trình Của TechNova Store</h2>
        <div class="prose prose-lg mx-auto text-brand-sub font-body leading-relaxed text-left md:text-center space-y-6">
            <p>
                Thành lập vào năm 2020, xuất phát từ niềm đam mê công nghệ mãnh liệt, TechNova Store ra đời với khát vọng trở thành cầu nối giữa người dùng Việt Nam và những tiến bộ công nghệ mới nhất trên thế giới.
            </p>
            <p>
                Chúng tôi hiểu rằng, trong kỷ nguyên số hóa, một thiết bị điện tử không chỉ đơn thuần là công cụ, mà là người bạn đồng hành đắc lực trong cả công việc lẫn cuộc sống. Sứ mệnh của chúng tôi là mang những sản phẩm công nghệ chính hãng, chất lượng cao đến tay người tiêu dùng Việt Nam với mức giá hợp lý nhất, kèm theo dịch vụ hậu mãi vượt trội.
            </p>
            <p>
                Hơn cả một cửa hàng bán lẻ, TechNova Store định hướng xây dựng một cộng đồng những người yêu công nghệ, nơi chia sẻ kiến thức, kinh nghiệm và cùng nhau trải nghiệm những giá trị đích thực mà thế giới số mang lại.
            </p>
        </div>
    </div>

    <!-- Core Values -->
    <div class="bg-brand-surface py-24 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="font-display text-4xl font-black text-brand-white">Giá Trị Cốt Lõi</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <!-- Value 1 -->
                <div class="bg-brand-card p-10 rounded-2xl shadow-sm border border-brand-border text-center group hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-20 h-20 mx-auto bg-brand-gold/10 rounded-full flex items-center justify-center text-brand-gold text-3xl mb-6 group-hover:bg-brand-gold group-hover:text-black transition-colors border border-brand-gold/20">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="font-display text-2xl font-bold mb-4 text-brand-white">Chính Hãng 100%</h3>
                    <p class="text-brand-sub font-body">Chúng tôi cam kết cung cấp các sản phẩm có nguồn gốc xuất xứ rõ ràng, đầy đủ giấy tờ, đảm bảo chất lượng nguyên bản từ nhà sản xuất.</p>
                </div>
                <!-- Value 2 -->
                <div class="bg-brand-card p-10 rounded-2xl shadow-sm border border-brand-border text-center group hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-20 h-20 mx-auto bg-brand-gold/10 rounded-full flex items-center justify-center text-brand-gold text-3xl mb-6 group-hover:bg-brand-gold group-hover:text-black transition-colors border border-brand-gold/20">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="font-display text-2xl font-bold mb-4 text-brand-white">Bảo Hành Uy Tín</h3>
                    <p class="text-brand-sub font-body">Chính sách hậu mãi chuyên nghiệp, minh bạch. Đội ngũ kỹ thuật viên giàu kinh nghiệm luôn sẵn sàng giải quyết mọi vấn đề kỹ thuật nhanh chóng.</p>
                </div>
                <!-- Value 3 -->
                <div class="bg-brand-card p-10 rounded-2xl shadow-sm border border-brand-border text-center group hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-20 h-20 mx-auto bg-brand-gold/10 rounded-full flex items-center justify-center text-brand-gold text-3xl mb-6 group-hover:bg-brand-gold group-hover:text-black transition-colors border border-brand-gold/20">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="font-display text-2xl font-bold mb-4 text-brand-white">Tư Vấn Tận Tâm</h3>
                    <p class="text-brand-sub font-body">Đội ngũ chuyên viên am hiểu công nghệ sẽ lắng nghe nhu cầu và đưa ra những giải pháp tối ưu nhất, phù hợp với ngân sách của bạn.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="py-32 px-6 bg-[#0a0a0a] text-white text-center border-t border-brand-border">
        <h2 class="font-display text-4xl md:text-6xl font-black mb-8 text-brand-white">Sẵn sàng trải nghiệm công nghệ đỉnh cao?</h2>
        <a href="shop.php" class="btn-gold inline-flex rounded-md">
            Mua Sắm Ngay
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

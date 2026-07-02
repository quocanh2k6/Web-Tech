<?php
require_once 'db_connect.php';
require_once 'includes/helpers.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: shop.php");
    exit();
}

$product_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = :id");
$stmt->execute(['id' => $product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: shop.php");
    exit();
}

$category_id = isset($product['category_id']) ? (int)$product['category_id'] : 0;
$category_name = isset($product['category_name']) && $product['category_name'] !== ''
    ? $product['category_name']
    : 'Category';
$description = isset($product['description']) && trim((string)$product['description']) !== ''
    ? $product['description']
    : 'No description available for this item.';

require_once 'includes/header.php';
?>

<div class="max-w-7xl mx-auto py-12 px-6 min-h-[80vh] mt-4">
    <!-- Breadcrumb -->
    <div class="mb-8 font-body text-sm font-medium tracking-wide uppercase">
        <a href="index.php" class="text-brand-sub hover:text-brand-gold transition-colors">Trang Chủ</a>
        <span class="text-brand-border mx-2">/</span>
        <a href="shop.php" class="text-brand-sub hover:text-brand-gold transition-colors">Thiết Bị</a>
        <span class="text-brand-border mx-2">/</span>
        <a href="<?= $category_id > 0 ? 'shop.php?category=' . $category_id : 'shop.php' ?>" class="text-brand-sub hover:text-brand-gold transition-colors">
            <?= htmlspecialchars($category_name) ?>
        </a>
        <span class="text-brand-border mx-2">/</span>
        <span class="text-brand-white"><?= htmlspecialchars($product['name']) ?></span>
    </div>

    <div class="flex flex-col lg:flex-row gap-12 lg:gap-16">
        <!-- Product Image Gallery -->
        <div class="w-full lg:w-3/5">
            <div class="w-full bg-brand-card rounded-2xl relative h-[50vh] md:h-[700px] overflow-hidden group shadow-sm border border-brand-border">
                <img src="<?= htmlspecialchars(asset_image_url($product['image_url'])) ?>" alt="<?= htmlspecialchars($product['name']) ?>" 
                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';"
                     class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 cursor-zoom-in">
                <div class="absolute top-4 left-4 bg-brand-gold text-[#000] text-xs font-bold uppercase tracking-widest px-3 py-1.5 rounded-md shadow-sm">
                    Chính Hãng
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="w-full lg:w-2/5 flex flex-col justify-start pt-4">
            <h1 class="font-display text-4xl md:text-5xl font-black mb-4 text-brand-white leading-tight"><?= htmlspecialchars($product['name']) ?></h1>
            <p class="font-display text-3xl font-bold text-brand-gold mb-8"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</p>
            
            <div class="mb-10 prose prose-invert max-w-none">
                <p class="text-brand-sub font-body leading-relaxed text-lg">
                    <?= nl2br(htmlspecialchars($description)) ?>
                </p>
            </div>

            <!-- Technical Specs (TechNova) -->
            <div class="mb-10 grid grid-cols-2 gap-4 border-y border-brand-border py-6">
                <div>
                    <p class="text-xs text-brand-sub font-bold uppercase tracking-widest mb-1">Tình trạng</p>
                    <p class="font-medium text-brand-white">Mới 100%</p>
                </div>
                <div>
                    <p class="text-xs text-brand-sub font-bold uppercase tracking-widest mb-1">Bảo hành</p>
                    <p class="font-medium text-brand-white">12 Tháng</p>
                </div>
                <div>
                    <p class="text-xs text-brand-sub font-bold uppercase tracking-widest mb-1">Giao hàng</p>
                    <p class="font-medium text-brand-white">Toàn quốc</p>
                </div>
                <div>
                    <p class="text-xs text-brand-sub font-bold uppercase tracking-widest mb-1">Cam kết</p>
                    <p class="font-medium text-brand-white">1 đổi 1 trong 30 ngày</p>
                </div>
            </div>

            <!-- Quantity & Add to Cart -->
            <div class="flex flex-col sm:flex-row items-center gap-4 mt-auto">
                <div class="flex items-center border border-brand-border rounded-md bg-brand-surface h-12">
                    <button type="button" onclick="updateQty(-1)" class="w-12 h-full flex items-center justify-center text-brand-sub hover:text-brand-gold transition-colors focus:outline-none">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <input type="number" id="qty-input" value="1" min="1" class="w-16 h-full text-center border-x border-brand-border focus:outline-none bg-transparent text-brand-white appearance-none font-bold">
                    <button type="button" onclick="updateQty(1)" class="w-12 h-full flex items-center justify-center text-brand-sub hover:text-brand-gold transition-colors focus:outline-none">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
                
                <button onclick="addToCartDetail(<?= $product['id'] ?>)" class="btn-gold w-full sm:w-auto flex-grow justify-center h-12 rounded-md">
                    <span>Thêm Vào Giỏ</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </div>

            <!-- Shipping Promises -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 bg-brand-card p-6 rounded-xl border border-brand-border">
                <div class="flex items-center gap-3 text-sm text-brand-white font-medium">
                    <div class="w-8 h-8 rounded-full bg-brand-surface flex items-center justify-center border border-brand-border text-brand-gold">
                        <i class="fas fa-box"></i>
                    </div>
                    <span>Miễn phí giao hàng</span>
                </div>
                <div class="flex items-center gap-3 text-sm text-brand-white font-medium">
                    <div class="w-8 h-8 rounded-full bg-brand-surface flex items-center justify-center border border-brand-border text-brand-gold">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <span>Bảo hành tận nhà</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateQty(change) {
    const input = document.getElementById('qty-input');
    let currentVal = parseInt(input.value) || 1;
    let newVal = currentVal + change;
    if (newVal < 1) newVal = 1;
    input.value = newVal;
}

function addToCartDetail(productId) {
    const qty = parseInt(document.getElementById('qty-input').value) || 1;
    addToCart(productId, qty);
}
</script>

<?php require_once 'includes/footer.php'; ?>

<?php
require_once 'db_connect.php';
require_once 'includes/helpers.php';

// Fetch Categories
$stmt = $conn->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();

// Get current category from URL
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Fetch Products
if ($category_id) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :cat_id ORDER BY created_at DESC");
    $stmt->execute(['cat_id' => $category_id]);
} else {
    $stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
}
$products = $stmt->fetchAll();

$page_title = "Tất Cả Sản Phẩm";
if ($category_id) {
    foreach($categories as $cat) {
        if ($cat['id'] == $category_id) {
            $page_title = $cat['name'];
            break;
        }
    }
}

require_once 'includes/header.php';
?>

<div class="max-w-7xl mx-auto py-12 px-6 min-h-[80vh]">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-brand-border pb-6 mt-8">
        <div>
            <h1 class="font-display text-4xl md:text-5xl font-black text-brand-white"><?= htmlspecialchars($page_title) ?></h1>
            <p class="text-brand-sub mt-2 font-body">Đỉnh cao công nghệ, nâng tầm trải nghiệm của bạn.</p>
        </div>
        
        <div class="flex space-x-4 mt-6 md:mt-0 overflow-x-auto whitespace-nowrap pb-2 w-full md:w-auto font-body text-sm font-semibold tracking-wider uppercase">
            <a href="shop.php" class="px-4 py-2 rounded-full transition-all <?= !$category_id ? 'bg-brand-gold text-black shadow-[0_0_15px_rgba(201,168,76,0.3)]' : 'bg-brand-surface text-brand-sub hover:text-brand-white border border-brand-border' ?>">Tất Cả</a>
            <?php foreach($categories as $cat): ?>
                <a href="shop.php?category=<?= $cat['id'] ?>" class="px-4 py-2 rounded-full transition-all <?= $category_id == $cat['id'] ? 'bg-brand-gold text-black shadow-[0_0_15px_rgba(201,168,76,0.3)]' : 'bg-brand-surface text-brand-sub hover:text-brand-white border border-brand-border' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if(count($products) > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
            <?php foreach($products as $product): ?>
                <div class="product-card">
                    <div class="product-img-wrap">
                        <a href="product_detail.php?id=<?= $product['id'] ?>">
                            <img src="<?= htmlspecialchars(asset_image_url($product['image_url'])) ?>" alt="<?= htmlspecialchars($product['name']) ?>" 
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';">
                        </a>
                        <div class="product-quick-add" onclick="addToCart(<?= $product['id'] ?>)">THÊM VÀO GIỎ</div>
                    </div>
                    
                    <div class="p-5">
                        <a href="product_detail.php?id=<?= $product['id'] ?>" class="block">
                            <h3 class="product-name mb-2 truncate"><?= htmlspecialchars($product['name']) ?></h3>
                            <div class="product-price"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-32 bg-brand-surface rounded-2xl border border-brand-border">
            <i class="fas fa-box-open text-4xl text-brand-sub mb-4"></i>
            <p class="text-brand-sub font-body text-lg">Không tìm thấy sản phẩm nào trong danh mục này.</p>
            <a href="shop.php" class="btn-gold mt-6 rounded-md">Xem Tất Cả Sản Phẩm</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>

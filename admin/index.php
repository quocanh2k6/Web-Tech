<?php
require_once 'includes/header.php';

// --- Stats Queries ---
$totalRevenue = $conn->query("SELECT COALESCE(SUM(total_amount), 0) FROM orders WHERE status != 'Cancelled'")->fetchColumn();
$totalOrders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalCustomers = $conn->query("SELECT COUNT(*) FROM users WHERE role_id = 3")->fetchColumn();
$totalProducts = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
$pendingOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetchColumn();

// --- Revenue last 7 days ---
$revenueStmt = $conn->query("
    SELECT DATE(created_at) as date, COALESCE(SUM(total_amount), 0) as revenue
    FROM orders WHERE status != 'Cancelled' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(created_at) ORDER BY date ASC
");
$revenueData = $revenueStmt->fetchAll();
$revLabels = []; $revValues = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $revLabels[] = date('d/m', strtotime($d));
    $found = false;
    foreach ($revenueData as $row) {
        if ($row['date'] === $d) { $revValues[] = (float)$row['revenue']; $found = true; break; }
    }
    if (!$found) $revValues[] = 0;
}

// --- Top products chart ---
$chartStmt = $conn->prepare("
    SELECT p.name, SUM(oi.quantity) as total_sold
    FROM order_items oi JOIN products p ON oi.product_id = p.id
    GROUP BY p.id ORDER BY total_sold DESC LIMIT 8
");
$chartStmt->execute();
$chartData = $chartStmt->fetchAll();
$labels = []; $data = [];
foreach ($chartData as $row) {
    $labels[] = mb_strimwidth($row['name'], 0, 20, '...');
    $data[] = (int)$row['total_sold'];
}

// --- Recent orders ---
$recentOrders = $conn->query("
    SELECT o.id, o.total_amount, o.status, o.payment_method, o.created_at, u.full_name
    FROM orders o LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.created_at DESC LIMIT 6
")->fetchAll();

// --- Contacts ---
$contacts = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5")->fetchAll();

// --- Newsletter ---
$newsletters = $conn->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Helper
function formatVND($amount) {
    return number_format($amount, 0, ',', '.') . 'đ';
}
function statusBadge($status) {
    $map = [
        'Pending'    => 'badge-warning',
        'Processing' => 'badge-info',
        'Completed'  => 'badge-success',
        'Cancelled'  => 'badge-danger',
        'Shipped'    => 'badge-info',
    ];
    $cls = $map[$status] ?? 'badge-gray';
    return '<span class="badge ' . $cls . '">' . htmlspecialchars($status) . '</span>';
}
?>

<!-- Page Header -->
<div class="mb-8 animate-fade-in">
    <h1 class="text-2xl font-bold text-zinc-800 leading-snug">Tổng Quan</h1>
    <p class="text-zinc-400 text-sm mt-1 leading-relaxed">Xin chào, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>. Đây là báo cáo hoạt động hôm nay.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    
    <!-- Revenue -->
    <div class="admin-card p-5 animate-fade-in animate-delay-1">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider leading-relaxed">Doanh Thu</p>
                <p class="text-2xl font-bold text-zinc-800 mt-2"><?= formatVND($totalRevenue) ?></p>
            </div>
            <div class="stat-icon w-11 h-11 rounded-xl bg-brand-gold/10 flex items-center justify-center">
                <i class="fas fa-dollar-sign text-brand-gold text-lg"></i>
            </div>
        </div>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="text-[11px] font-semibold text-emerald-500"><i class="fas fa-arrow-up text-[9px]"></i> 12.5%</span>
            <span class="text-[11px] text-zinc-400">so với tuần trước</span>
        </div>
    </div>

    <!-- Orders -->
    <div class="admin-card p-5 animate-fade-in animate-delay-2">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider leading-relaxed">Đơn Hàng</p>
                <p class="text-2xl font-bold text-zinc-800 mt-2"><?= number_format($totalOrders) ?></p>
            </div>
            <div class="stat-icon w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="fas fa-shopping-bag text-amber-500 text-lg"></i>
            </div>
        </div>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="badge badge-warning text-[10px]"><?= $pendingOrders ?> chờ xử lý</span>
        </div>
    </div>

    <!-- Customers -->
    <div class="admin-card p-5 animate-fade-in animate-delay-3">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider leading-relaxed">Khách Hàng</p>
                <p class="text-2xl font-bold text-zinc-800 mt-2"><?= number_format($totalCustomers) ?></p>
            </div>
            <div class="stat-icon w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fas fa-users text-emerald-500 text-lg"></i>
            </div>
        </div>
        <div class="flex items-center gap-1.5 mt-3">
            <span class="text-[11px] font-semibold text-emerald-500"><i class="fas fa-arrow-up text-[9px]"></i> 8.2%</span>
            <span class="text-[11px] text-zinc-400">tháng này</span>
        </div>
    </div>

    <!-- Products -->
    <div class="admin-card p-5 animate-fade-in animate-delay-4">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs font-medium text-zinc-400 uppercase tracking-wider leading-relaxed">Sản Phẩm</p>
                <p class="text-2xl font-bold text-zinc-800 mt-2"><?= number_format($totalProducts) ?></p>
            </div>
            <div class="stat-icon w-11 h-11 rounded-xl bg-zinc-100 flex items-center justify-center">
                <i class="fas fa-box text-zinc-500 text-lg"></i>
            </div>
        </div>
        <div class="flex items-center gap-1.5 mt-3">
            <a href="products.php" class="text-[11px] font-medium text-brand-gold hover:text-brand-gold-light transition-colors">Quản lý <i class="fas fa-arrow-right text-[9px] ml-0.5"></i></a>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-8">
    
    <!-- Revenue Line Chart (2/3) -->
    <div class="admin-card p-6 xl:col-span-2 animate-fade-in">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-base font-semibold text-zinc-700 leading-snug">Doanh Thu 7 Ngày Gần Nhất</h2>
                <p class="text-xs text-zinc-400 mt-0.5 leading-relaxed">Biểu đồ theo ngày</p>
            </div>
            <span class="badge badge-info"><i class="fas fa-chart-line text-[10px] mr-1"></i> Tổng quan</span>
        </div>
        <div class="chart-container h-[280px]">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Products Bar Chart (1/3) -->
    <div class="admin-card p-6 animate-fade-in">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-base font-semibold text-zinc-700 leading-snug">Bán Chạy Nhất</h2>
                <p class="text-xs text-zinc-400 mt-0.5 leading-relaxed">Top sản phẩm</p>
            </div>
            <span class="badge badge-gray"><i class="fas fa-trophy text-[10px] mr-1"></i> Top 8</span>
        </div>
        <div class="chart-container h-[280px]">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="admin-card mb-8 animate-fade-in">
    <div class="flex items-center justify-between p-5 pb-0">
        <div>
            <h2 class="text-base font-semibold text-zinc-700 leading-snug">Đơn Hàng Gần Đây</h2>
            <p class="text-xs text-zinc-400 mt-0.5 leading-relaxed">6 đơn hàng mới nhất</p>
        </div>
        <a href="orders.php" class="btn-admin btn-ghost text-xs">Xem tất cả <i class="fas fa-arrow-right text-[10px]"></i></a>
    </div>
    <div class="overflow-x-auto p-5">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Khách Hàng</th>
                    <th>Thanh Toán</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Ngày</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr><td colspan="7" class="text-center text-zinc-400 py-8">Chưa có đơn hàng nào.</td></tr>
                <?php else: ?>
                    <?php foreach($recentOrders as $order): ?>
                        <tr>
                            <td class="font-semibold text-zinc-700">#<?= $order['id'] ?></td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-zinc-100 flex items-center justify-center text-[10px] font-bold text-zinc-500">
                                        <?= mb_substr($order['full_name'] ?? 'K', 0, 1) ?>
                                    </div>
                                    <?= htmlspecialchars($order['full_name'] ?? 'Khách vãng lai') ?>
                                </div>
                            </td>
                            <td class="text-zinc-500"><?= htmlspecialchars($order['payment_method']) ?></td>
                            <td class="font-semibold text-zinc-700"><?= formatVND($order['total_amount']) ?></td>
                            <td><?= statusBadge($order['status']) ?></td>
                            <td class="text-zinc-400 text-xs"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td><a href="order_detail.php?id=<?= $order['id'] ?>" class="text-brand-gold hover:text-brand-gold-light text-xs font-medium transition-colors">Chi tiết</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Contacts & Newsletter -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    
    <!-- Contacts -->
    <div class="admin-card">
        <div class="flex items-center justify-between p-5 pb-0">
            <div>
                <h2 class="text-base font-semibold text-zinc-700 leading-snug">Tin Nhắn Mới</h2>
                <p class="text-xs text-zinc-400 mt-0.5 leading-relaxed">Liên hệ từ khách hàng</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-brand-gold/10 flex items-center justify-center">
                <i class="fas fa-envelope text-brand-gold text-sm"></i>
            </div>
        </div>
        <div class="overflow-x-auto p-5">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Ngày</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($contacts)): ?>
                        <tr><td colspan="4" class="text-center text-zinc-400 py-6">Chưa có tin nhắn.</td></tr>
                    <?php else: ?>
                        <?php foreach($contacts as $contact): ?>
                            <tr>
                                <td class="font-medium text-zinc-700"><?= htmlspecialchars($contact['name']) ?></td>
                                <td class="text-zinc-500"><?= htmlspecialchars($contact['email']) ?></td>
                                <td class="text-zinc-400 text-xs"><?= date('d/m/Y', strtotime($contact['created_at'])) ?></td>
                                <td>
                                    <button type="button" class="view-contact-btn text-brand-gold hover:text-brand-gold-light text-xs font-medium transition-colors" data-contact="<?= htmlspecialchars(json_encode($contact)) ?>">Xem</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Newsletter -->
    <div class="admin-card">
        <div class="flex items-center justify-between p-5 pb-0">
            <div>
                <h2 class="text-base font-semibold text-zinc-700 leading-snug">Đăng Ký Bản Tin</h2>
                <p class="text-xs text-zinc-400 mt-0.5 leading-relaxed">Subscriber mới nhất</p>
            </div>
            <a href="newsletter.php" class="btn-admin btn-ghost text-xs">Xem tất cả <i class="fas fa-arrow-right text-[10px]"></i></a>
        </div>
        <div class="overflow-x-auto p-5">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Ngày Đăng Ký</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($newsletters)): ?>
                        <tr><td colspan="2" class="text-center text-zinc-400 py-6">Chưa có lượt đăng ký.</td></tr>
                    <?php else: ?>
                        <?php foreach($newsletters as $news): ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-emerald-50 flex items-center justify-center">
                                            <i class="fas fa-at text-emerald-400 text-[10px]"></i>
                                        </div>
                                        <span class="text-zinc-600"><?= htmlspecialchars($news['email']) ?></span>
                                    </div>
                                </td>
                                <td class="text-zinc-400 text-xs"><?= date('d/m/Y H:i', strtotime($news['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Contact Modal -->
<div id="contact-modal" class="fixed inset-0 bg-zinc-900/50 backdrop-blur-sm z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-7 max-w-lg w-full m-4 transform scale-95 transition-transform duration-300" id="contact-modal-content">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-zinc-800 leading-snug">Chi Tiết Tin Nhắn</h3>
            <button id="close-modal-btn" class="w-8 h-8 rounded-lg bg-zinc-50 hover:bg-zinc-100 flex items-center justify-center text-zinc-400 hover:text-zinc-600 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-[10px] font-semibold text-zinc-400 uppercase tracking-wider mb-1">Người gửi</label>
                <div id="modal-name" class="text-sm text-zinc-800 font-medium leading-relaxed"></div>
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-zinc-400 uppercase tracking-wider mb-1">Email</label>
                <div id="modal-email" class="text-sm text-zinc-600 leading-relaxed"></div>
            </div>
            <div>
                <label class="block text-[10px] font-semibold text-zinc-400 uppercase tracking-wider mb-1">Ngày gửi</label>
                <div id="modal-date" class="text-sm text-zinc-600 leading-relaxed"></div>
            </div>
            <div class="pt-3 border-t border-zinc-100">
                <label class="block text-[10px] font-semibold text-zinc-400 uppercase tracking-wider mb-2">Nội dung</label>
                <div id="modal-message" class="text-sm text-zinc-700 whitespace-pre-wrap bg-zinc-50 p-4 rounded-xl border border-zinc-100 leading-relaxed"></div>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button id="close-modal-bottom-btn" class="btn-admin btn-ghost">Đóng</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // === REVENUE LINE CHART (Gold branded) ===
    const revCtx = document.getElementById('revenueChart').getContext('2d');
    const gradient = revCtx.createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(201, 168, 76, 0.15)');
    gradient.addColorStop(1, 'rgba(201, 168, 76, 0.0)');

    new Chart(revCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($revLabels) ?>,
            datasets: [{
                label: 'Doanh thu',
                data: <?= json_encode($revValues) ?>,
                borderColor: '#C9A84C',
                backgroundColor: gradient,
                borderWidth: 2.5,
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#C9A84C',
                pointHoverBorderColor: '#ffffff',
                pointHoverBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            scales: {
                x: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { font: { size: 11, family: 'Be Vietnam Pro, Inter' }, color: '#a1a1aa' }
                },
                y: {
                    grid: { color: '#f4f4f5', drawBorder: false },
                    border: { display: false },
                    ticks: {
                        font: { size: 11, family: 'Be Vietnam Pro, Inter' }, color: '#a1a1aa',
                        callback: v => (v >= 1e6 ? (v/1e6).toFixed(1) + 'M' : v >= 1e3 ? (v/1e3).toFixed(0) + 'K' : v)
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#18181b',
                    titleFont: { family: 'Be Vietnam Pro, Inter', size: 12 },
                    bodyFont: { family: 'Be Vietnam Pro, Inter', size: 11 },
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: false,
                    callbacks: {
                        label: ctx => new Intl.NumberFormat('vi-VN').format(ctx.raw) + 'đ'
                    }
                }
            }
        }
    });

    // === TOP PRODUCTS BAR CHART (Warm gold gradient) ===
    const barCtx = document.getElementById('salesChart').getContext('2d');
    const barGradient = barCtx.createLinearGradient(0, 0, 400, 0);
    barGradient.addColorStop(0, '#C9A84C');
    barGradient.addColorStop(1, '#E8C97E');

    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Đã bán',
                data: <?= json_encode($data) ?>,
                backgroundColor: barGradient,
                borderRadius: 6,
                borderSkipped: false,
                barThickness: 18,
                maxBarThickness: 24,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: { color: '#f4f4f5', drawBorder: false },
                    border: { display: false },
                    ticks: { font: { size: 11, family: 'Be Vietnam Pro, Inter' }, color: '#a1a1aa', precision: 0 }
                },
                y: {
                    grid: { display: false },
                    border: { display: false },
                    ticks: { font: { size: 11, family: 'Be Vietnam Pro, Inter' }, color: '#71717a' }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#18181b',
                    titleFont: { family: 'Be Vietnam Pro, Inter', size: 12 },
                    bodyFont: { family: 'Be Vietnam Pro, Inter', size: 11 },
                    padding: 10,
                    cornerRadius: 10,
                    displayColors: false,
                }
            }
        }
    });

    // === CONTACT MODAL ===
    const modal = document.getElementById('contact-modal');
    const modalContent = document.getElementById('contact-modal-content');
    const closeBtns = [document.getElementById('close-modal-btn'), document.getElementById('close-modal-bottom-btn')];
    
    document.querySelectorAll('.view-contact-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = JSON.parse(this.getAttribute('data-contact'));
            document.getElementById('modal-name').textContent = data.name;
            document.getElementById('modal-email').textContent = data.email;
            document.getElementById('modal-message').textContent = data.message;
            document.getElementById('modal-date').textContent = new Date(data.created_at).toLocaleString('vi-VN');
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalContent.classList.remove('scale-95');
        });
    });

    function closeModal() {
        modal.classList.add('opacity-0', 'pointer-events-none');
        modalContent.classList.add('scale-95');
    }
    closeBtns.forEach(btn => btn.addEventListener('click', closeModal));
    modal.addEventListener('click', e => { if(e.target === modal) closeModal(); });
});
</script>

<?php require_once 'includes/footer.php'; ?>

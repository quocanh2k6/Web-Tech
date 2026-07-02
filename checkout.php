<?php
require_once 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header("Location: auth.php");
    exit();
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if(empty($cart_items)){
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT address FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user_data = $stmt->fetch();

if (empty($user_data['address'])) {
    $_SESSION['profile_error'] = 'Vui lòng cập nhật địa chỉ giao hàng trước khi thanh toán.';
    header("Location: profile.php");
    exit();
}

$total_amount = 0;
foreach($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

require_once 'includes/header.php';
?>

<div class="max-w-3xl mx-auto py-12 px-6 min-h-[70vh] mt-8">
    <h1 class="font-display text-4xl font-black mb-10 border-b border-brand-border pb-4 text-center text-brand-white">Thanh Toán An Toàn</h1>
    
    <!-- Payment Method Container -->
    <div id="payment-method-container" class="bg-brand-card shadow-lg p-8 rounded-xl border border-brand-border">
        <h2 class="font-display text-2xl mb-6 font-bold text-brand-white">Chọn Phương Thức Thanh Toán</h2>
        
        <form id="payment-form">
            <div class="space-y-4 mb-8">
                <label class="flex items-center p-5 border border-brand-border rounded-lg cursor-pointer hover:border-brand-gold transition-colors bg-brand-surface">
                    <input type="radio" name="payment_method" value="Apple Pay" class="w-5 h-5 text-brand-gold accent-brand-gold" checked>
                    <span class="ml-4 font-bold font-body text-brand-white flex-grow">Apple Pay</span>
                    <i class="fab fa-apple text-brand-white text-2xl"></i>
                </label>
                
                <label class="flex items-center p-5 border border-brand-border rounded-lg cursor-pointer hover:border-brand-gold transition-colors bg-brand-surface">
                    <input type="radio" name="payment_method" value="Credit Card" class="w-5 h-5 text-brand-gold accent-brand-gold">
                    <span class="ml-4 font-bold font-body text-brand-white flex-grow">Thẻ Tín Dụng</span>
                    <i class="fas fa-credit-card text-brand-sub text-2xl"></i>
                </label>
                
                <label class="flex items-center p-5 border border-brand-border rounded-lg cursor-pointer hover:border-brand-gold transition-colors bg-brand-surface">
                    <input type="radio" name="payment_method" value="PayPal" class="w-5 h-5 text-brand-gold accent-brand-gold">
                    <span class="ml-4 font-bold font-body text-brand-white flex-grow">PayPal</span>
                    <i class="fab fa-paypal text-[#00457C] text-2xl" style="color: #0079C1;"></i>
                </label>
            </div>
            
            <div class="flex justify-between items-center border-t border-brand-border pt-6 mb-6">
                <span class="text-brand-sub font-body font-medium">Tổng Đơn Hàng:</span>
                <span class="font-display font-black text-2xl text-brand-gold"><?= number_format($total_amount, 0, ',', '.') ?> VNĐ</span>
            </div>

            <button type="button" id="btn-confirm-method" class="btn-gold w-full justify-center rounded-md">
                Xác Nhận Phương Thức Thanh Toán
            </button>
        </form>
    </div>

    <!-- QR / Processing Container (Hidden by default) -->
    <div id="qr-container" class="hidden bg-brand-card shadow-xl p-10 rounded-xl border border-brand-border text-center transform scale-95 opacity-0 transition-all duration-500">
        <h2 class="font-display text-2xl font-bold mb-2 text-brand-white">Đang Xử Lý Thanh Toán</h2>
        <p class="text-brand-sub mb-8 font-body text-sm">Vui lòng hoàn tất giao dịch trong ứng dụng thanh toán của bạn.</p>
        
        <div class="bg-brand-surface p-8 inline-block rounded-xl mb-8 border border-brand-border shadow-inner">
            <i class="fas fa-mobile-alt text-6xl text-brand-sub mb-4"></i>
            <p class="mt-2 font-black text-2xl text-brand-gold"><?= number_format($total_amount, 0, ',', '.') ?> VNĐ</p>
        </div>
        <div id="payment-action-area">
            <button type="button" id="btn-confirm-payment" class="btn-gold w-full justify-center rounded-md shadow-md gap-3">
                <i class="fas fa-check-circle"></i> Tôi đã hoàn tất thanh toán
            </button>

            <!-- Spinner -->
            <div id="payment-spinner" class="hidden w-full py-4 flex flex-col items-center justify-center">
                <div class="w-12 h-12 mx-auto rounded-full border-4 border-brand-border border-t-brand-gold animate-spin"></div>
                <p class="text-brand-sub text-sm mt-4 font-body font-medium">Đang xác thực giao dịch an toàn...</p>
            </div>
        </div>
        <button type="button" id="btn-cancel-payment" class="w-full bg-transparent text-brand-sub py-4 font-bold uppercase tracking-widest text-xs hover:text-brand-white mt-2 transition-colors focus:outline-none">
            Hủy & Quay Lại
        </button>
    </div>

    <!-- Success Container (Hidden by default) -->
    <div id="success-container" class="hidden text-center py-20 transform scale-95 opacity-0 transition-all duration-700 bg-brand-card rounded-xl shadow-lg border border-brand-border">
        <!-- SVG Checkmark -->
        <div class="success-animation mb-8">
            <svg class="checkmark w-24 h-24 mx-auto block stroke-brand-gold stroke-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle stroke-brand-gold fill-none" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check fill-none stroke-brand-gold" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>
        
        <h1 class="font-display text-4xl md:text-5xl font-black mb-4 text-brand-white">Thanh Toán Thành Công</h1>
        <p class="text-brand-sub text-lg mb-10 font-body max-w-md mx-auto">Đơn hàng thiết bị của bạn đang được chuẩn bị. Chúng tôi sẽ gửi email xác nhận cho bạn sớm nhất.</p>
        
        <a href="shop.php" class="btn-gold inline-flex rounded-md shadow-md hover:shadow-lg">
            Tiếp Tục Mua Sắm
        </a>
    </div>

</div>

<style>
.checkmark__circle {
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    stroke-width: 2;
    stroke-miterlimit: 10;
    stroke: var(--gold);
    fill: none;
    animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
}

.checkmark {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #fff;
    stroke-miterlimit: 10;
    margin: 10% auto;
    box-shadow: inset 0px 0px 0px var(--gold);
    animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
}

.checkmark__check {
    transform-origin: 50% 50%;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
}

@keyframes stroke {
    100% { stroke-dashoffset: 0; }
}
@keyframes scale {
    0%, 100% { transform: none; }
    50% { transform: scale3d(1.1, 1.1, 1); }
}
@keyframes fill {
    100% { box-shadow: inset 0px 0px 0px 40px rgba(201, 168, 76, 0.1); }
}
</style>

<script>
    let selectedMethod = '';

    document.getElementById('btn-confirm-method').addEventListener('click', function() {
        const methods = document.getElementsByName('payment_method');
        for (let i = 0; i < methods.length; i++) {
            if (methods[i].checked) {
                selectedMethod = methods[i].value;
                break;
            }
        }

        const methodContainer = document.getElementById('payment-method-container');
        const qrContainer = document.getElementById('qr-container');

        // Hide method
        methodContainer.classList.add('hidden');
        
        // Show QR
        qrContainer.classList.remove('hidden');
        setTimeout(() => {
            qrContainer.classList.remove('scale-95', 'opacity-0');
            qrContainer.classList.add('scale-100', 'opacity-100');
        }, 50);
    });

    document.getElementById('btn-cancel-payment').addEventListener('click', function() {
        const methodContainer = document.getElementById('payment-method-container');
        const qrContainer = document.getElementById('qr-container');

        qrContainer.classList.remove('scale-100', 'opacity-100');
        qrContainer.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            qrContainer.classList.add('hidden');
            methodContainer.classList.remove('hidden');
        }, 500);
    });

    document.getElementById('btn-confirm-payment').addEventListener('click', function() {
        const btn = this;
        const spinner = document.getElementById('payment-spinner');
        const qrContainer = document.getElementById('qr-container');
        const actionArea = document.getElementById('payment-action-area');
        
        btn.classList.add('hidden');
        spinner.classList.remove('hidden');
        btn.disabled = true;
        actionArea.classList.add('pointer-events-none');

        setTimeout(() => {
            fetch('ajax/process_checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    payment_method: selectedMethod
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const successContainer = document.getElementById('success-container');
                    const headerCartBadge = document.getElementById('cart-badge');

                    if (headerCartBadge) {
                        headerCartBadge.classList.add('hidden');
                        headerCartBadge.textContent = '0';
                    }

                    spinner.classList.add('hidden');

                    qrContainer.classList.remove('scale-100', 'opacity-100');
                    qrContainer.classList.add('scale-95', 'opacity-0');
                    
                    setTimeout(() => {
                        qrContainer.classList.add('hidden');
                        document.querySelector('h1').classList.add('hidden');
                        successContainer.classList.remove('hidden');
                        setTimeout(() => {
                            successContainer.classList.remove('scale-95', 'opacity-0');
                            successContainer.classList.add('scale-100', 'opacity-100');
                        }, 50);
                    }, 500);
                } else {
                    spinner.classList.add('hidden');
                    btn.classList.remove('hidden');
                    btn.disabled = false;
                    actionArea.classList.remove('pointer-events-none');
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                spinner.classList.add('hidden');
                btn.classList.remove('hidden');
                btn.disabled = false;
                actionArea.classList.remove('pointer-events-none');
                alert('Đã có lỗi kết nối mạng.');
            });
        }, 2000);
    });
</script>

<?php require_once 'includes/footer.php'; ?>

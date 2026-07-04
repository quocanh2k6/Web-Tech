<?php
session_start();
require_once 'config/db_connect.php';

$error = '';
$success = '';
$action_type = 'login';

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'register') {
        $action_type = 'register';
        $full_name = trim($_POST['full_name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']) ?: null;
        $password = $_POST['password'];

        if (empty($full_name) || empty($phone) || empty($password)) {
            $error = 'Vui lòng điền đầy đủ các trường bắt buộc.';
        } elseif (strlen($password) < 6) {
            $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
        } else {
            // Check if phone or email exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE phone = :phone OR (email = :email AND email IS NOT NULL)");
            $stmt->execute(['phone' => $phone, 'email' => $email]);
            if ($stmt->rowCount() > 0) {
                $error = 'Số điện thoại hoặc Email đã được đăng ký.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (full_name, phone, email, password) VALUES (:full_name, :phone, :email, :password)");
                try {
                    $stmt->execute([
                        'full_name' => $full_name,
                        'phone' => $phone,
                        'email' => $email,
                        'password' => $hashed_password
                    ]);
                    $success = 'Tạo tài khoản thành công! Bạn có thể đăng nhập ngay.';
                    $action_type = 'login';
                } catch(PDOException $e) {
                    $error = 'Đã có lỗi xảy ra: ' . $e->getMessage();
                }
            }
        }
    } elseif ($action == 'login') {
        $login_id = trim($_POST['login_id']); 
        $password = $_POST['password'];

        if (empty($login_id) || empty($password)) {
            $error = 'Vui lòng nhập thông tin đăng nhập.';
        } else {
            $stmt = $conn->prepare("SELECT id, full_name, password, role_id, avatar_url FROM users WHERE phone = :login_id OR email = :login_id");
            $stmt->execute(['login_id' => $login_id]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['user_avatar'] = $user['avatar_url'] ?? null;
                
                if (in_array($user['role_id'], [1, 2])) {
                    require_once 'includes/helpers.php';
                    log_admin_action($conn, $user['id'], "Login", "Admin login successful.");
                    header("Location: admin/index.php");
                    exit();
                }

                if (isset($_SESSION['redirect_after_login'])) {
                    $redirect = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header("Location: $redirect");
                } else {
                    header("Location: index.php");
                }
                exit();

            } else {
                $error = 'Thông tin đăng nhập không chính xác.';
            }
        }
    }
}

require_once 'includes/header.php';
?>

<div class="max-w-7xl mx-auto py-20 px-6 flex justify-center items-center min-h-[85vh]">
    <div class="w-full max-w-md bg-brand-card p-10 rounded-2xl shadow-xl border border-brand-border relative overflow-hidden">
        
        <?php if ($error): ?>
            <div class="bg-red-900/30 text-red-400 border border-red-800 p-4 rounded-md mb-6 text-sm font-medium flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="bg-green-900/30 text-green-400 border border-green-800 p-4 rounded-md mb-6 text-sm font-medium flex items-center gap-3">
                <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <div id="login-form" class="transition-opacity duration-300 <?= $action_type == 'register' ? 'hidden opacity-0' : 'opacity-100' ?>">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-brand-gold/10 text-brand-gold rounded-full flex items-center justify-center mx-auto mb-4 text-2xl border border-brand-border">
                    <i class="fas fa-lock"></i>
                </div>
                <h2 class="font-display text-3xl font-black text-brand-white">Chào Mừng Trở Lại</h2>
                <p class="text-brand-sub text-sm mt-2 font-body">Đăng nhập để xem thiết bị và đơn hàng của bạn.</p>
            </div>
            
            <form method="POST" action="auth.php">
                <input type="hidden" name="action" value="login">
                <div class="mb-5">
                    <label class="block text-xs font-bold mb-2 text-brand-sub uppercase tracking-widest">Email hoặc Số điện thoại *</label>
                    <input type="text" name="login_id" required class="form-input">
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-bold mb-2 text-brand-sub uppercase tracking-widest">Mật khẩu *</label>
                    <input type="password" name="password" required class="form-input">
                </div>
                <div class="flex justify-end mb-8">
                    <a href="forgot_password.php" class="text-sm font-bold text-brand-gold hover:text-brand-gold-light transition-colors">Quên mật khẩu?</a>
                </div>
                <button type="submit" class="btn-gold w-full justify-center rounded-md">
                    Đăng Nhập
                </button>
            </form>
            <div class="mt-8 text-center text-sm font-body text-brand-sub">
                Chưa có tài khoản TechNova? <a href="#" id="show-register" class="font-bold text-brand-gold hover:text-brand-gold-light transition-colors">Đăng ký ngay</a>
            </div>
        </div>

        <!-- Register Form -->
        <div id="register-form" class="transition-opacity duration-300 <?= $action_type == 'register' ? 'opacity-100' : 'hidden opacity-0' ?>">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-brand-gold/10 text-brand-gold rounded-full flex items-center justify-center mx-auto mb-4 text-2xl border border-brand-border">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2 class="font-display text-3xl font-black text-brand-white">Tham Gia TechNova</h2>
                <p class="text-brand-sub text-sm mt-2 font-body">Tạo tài khoản để theo dõi đơn hàng công nghệ của bạn.</p>
            </div>
            
            <form method="POST" action="auth.php">
                <input type="hidden" name="action" value="register">
                <div class="mb-5">
                    <label class="block text-xs font-bold mb-2 text-brand-sub uppercase tracking-widest">Họ và Tên *</label>
                    <input type="text" name="full_name" required class="form-input">
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-bold mb-2 text-brand-sub uppercase tracking-widest">Số điện thoại *</label>
                    <input type="text" name="phone" required class="form-input">
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-bold mb-2 text-brand-sub uppercase tracking-widest">Email (Không bắt buộc)</label>
                    <input type="email" name="email" class="form-input">
                </div>
                <div class="mb-8">
                    <label class="block text-xs font-bold mb-2 text-brand-sub uppercase tracking-widest">Mật khẩu *</label>
                    <input type="password" name="password" required minlength="6" class="form-input">
                </div>
                <button type="submit" class="btn-gold w-full justify-center rounded-md">
                    Tạo Tài Khoản
                </button>
            </form>
            <div class="mt-8 text-center text-sm font-body text-brand-sub">
                Đã có tài khoản? <a href="#" id="show-login" class="font-bold text-brand-gold hover:text-brand-gold-light transition-colors">Đăng nhập</a>
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('show-register').addEventListener('click', function(e) {
        e.preventDefault();
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        
        loginForm.classList.add('hidden', 'opacity-0');
        loginForm.classList.remove('opacity-100');
        
        registerForm.classList.remove('hidden', 'opacity-0');
        registerForm.classList.add('opacity-100');
    });

    document.getElementById('show-login').addEventListener('click', function(e) {
        e.preventDefault();
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        
        registerForm.classList.add('hidden', 'opacity-0');
        registerForm.classList.remove('opacity-100');
        
        loginForm.classList.remove('hidden', 'opacity-0');
        loginForm.classList.add('opacity-100');
    });
</script>

<?php require_once 'includes/footer.php'; ?>

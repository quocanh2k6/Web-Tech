<?php
session_start();
require_once 'config/db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_id = trim($_POST['login_id'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($login_id) || empty($new_password) || empty($confirm_password)) {
        $error = 'Vui lòng điền đầy đủ các trường.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Mật khẩu xác nhận không khớp.';
    } elseif (strlen($new_password) < 6) {
        $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
    } else {
        $stmt = $conn->prepare("SELECT id, role_id FROM users WHERE phone = :login_id OR email = :login_id");
        $stmt->execute(['login_id' => $login_id]);
        $user = $stmt->fetch();

        if ($user) {
            if (in_array($user['role_id'], [1, 2])) {
                $error = 'Quản trị viên không thể đặt lại mật khẩu tại đây.';
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE users SET password = :pw WHERE id = :id");
                $update->execute(['pw' => $hashed_password, 'id' => $user['id']]);
                $success = 'Đặt lại mật khẩu thành công. Đang chuyển hướng đến đăng nhập...';
                echo "<script>setTimeout(function() { window.location.href = 'auth.php'; }, 2000);</script>";
            }
        } else {
            $error = 'Không tìm thấy tài khoản với email hoặc số điện thoại này.';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="max-w-7xl mx-auto py-20 px-6 flex justify-center items-center min-h-[70vh]">
    <div class="w-full max-w-md bg-brand-card p-10 rounded-2xl shadow-xl border border-brand-border relative overflow-hidden">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-brand-gold/10 text-brand-gold rounded-full flex items-center justify-center mx-auto mb-4 text-2xl border border-brand-border">
                <i class="fas fa-key"></i>
            </div>
            <h2 class="font-display text-3xl font-black text-brand-white">Đặt Lại Mật Khẩu</h2>
            <p class="text-brand-sub text-sm mt-2 font-body">Nhập mật khẩu mới của bạn bên dưới.</p>
        </div>
        
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

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-xs font-bold mb-2 text-brand-sub uppercase tracking-widest">SĐT / Email *</label>
                <input type="text" name="login_id" required class="form-input">
            </div>
            <div>
                <label class="block text-xs font-bold mb-2 text-brand-sub uppercase tracking-widest">Mật khẩu mới *</label>
                <input type="password" name="new_password" required minlength="6" class="form-input">
            </div>
            <div>
                <label class="block text-xs font-bold mb-2 text-brand-sub uppercase tracking-widest">Xác nhận mật khẩu *</label>
                <input type="password" name="confirm_password" required minlength="6" class="form-input">
            </div>
            <button type="submit" class="btn-gold w-full justify-center rounded-md mt-4">
                Đặt Lại Mật Khẩu
            </button>
        </form>

        <div class="mt-8 text-center text-sm font-body">
            <a href="auth.php" class="font-bold text-brand-gold hover:text-brand-gold-light transition-colors"><i class="fas fa-arrow-left mr-2"></i>Quay lại đăng nhập</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

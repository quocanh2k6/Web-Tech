<?php
require_once 'config/db_connect.php';
require_once 'includes/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'profile.php';
    header('Location: auth.php');
    exit();
}

$userId = (int) $_SESSION['user_id'];
$error = '';
if (isset($_SESSION['profile_error'])) {
    $error = $_SESSION['profile_error'];
    unset($_SESSION['profile_error']);
}
$success = '';

function profile_local_avatar_path(?string $avatarUrl): ?string
{
    $avatarUrl = trim((string) $avatarUrl);
    if ($avatarUrl === '') {
        return null;
    }

    if (preg_match('#^https?://#i', $avatarUrl)) {
        return null;
    }

    $relative = ltrim($avatarUrl, '/\\');
    if (str_starts_with($relative, 'assets/avatars/')) {
        return __DIR__ . '/' . $relative;
    }

    return null;
}

$stmt = $conn->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: auth.php');
    exit();
}

$formValues = [
    'full_name' => $user['full_name'] ?? '',
    'phone' => $user['phone'] ?? '',
    'email' => $user['email'] ?? '',
    'address' => $user['address'] ?? '',
    'birth_date' => $user['birth_date'] ?? '',
    'gender' => $user['gender'] ?? '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'save_profile';

    if ($action === 'remove_avatar') {
        $localAvatarPath = profile_local_avatar_path($user['avatar_url'] ?? null);
        if ($localAvatarPath && is_file($localAvatarPath)) {
            @unlink($localAvatarPath);
        }

        $stmt = $conn->prepare('UPDATE users SET avatar_url = NULL WHERE id = :id');
        $stmt->execute(['id' => $userId]);

        $_SESSION['user_avatar'] = null;
        $success = 'Đã xóa ảnh đại diện.';
    } else {
        $formValues['full_name'] = trim((string) ($_POST['full_name'] ?? ''));
        $formValues['phone'] = trim((string) ($_POST['phone'] ?? ''));
        $formValues['email'] = trim((string) ($_POST['email'] ?? ''));
        $formValues['address'] = trim((string) ($_POST['address'] ?? ''));
        $formValues['birth_date'] = trim((string) ($_POST['birth_date'] ?? ''));
        $formValues['gender'] = trim((string) ($_POST['gender'] ?? ''));
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

        if ($formValues['full_name'] === '' || $formValues['phone'] === '' || $formValues['address'] === '') {
            $error = 'Vui lòng nhập Họ Tên, SĐT và Địa chỉ giao hàng.';
        } elseif ($formValues['email'] !== '' && !filter_var($formValues['email'], FILTER_VALIDATE_EMAIL)) {
            $error = 'Địa chỉ email không hợp lệ.';
        } elseif ($formValues['birth_date'] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $formValues['birth_date'])) {
            $error = 'Ngày sinh không hợp lệ.';
        } elseif ($formValues['gender'] !== '' && !in_array($formValues['gender'], ['Male', 'Female', 'Other', 'Nam', 'Nữ', 'Khác'], true)) {
            $error = 'Giới tính không hợp lệ.';
        } elseif (($currentPassword !== '' || $newPassword !== '' || $confirmPassword !== '') && (!$currentPassword || !$newPassword || !$confirmPassword)) {
            $error = 'Để đổi mật khẩu, vui lòng nhập mật khẩu hiện tại, mật khẩu mới và xác nhận mật khẩu mới.';
        } elseif ($newPassword !== '' && strlen($newPassword) < 6) {
            $error = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
        } elseif ($newPassword !== '' && $newPassword !== $confirmPassword) {
            $error = 'Mật khẩu xác nhận không khớp.';
        } elseif (($currentPassword !== '' || $newPassword !== '' || $confirmPassword !== '') && !password_verify($currentPassword, $user['password'])) {
            $error = 'Mật khẩu hiện tại không chính xác.';
        } else {
            $duplicateQuery = '
                SELECT id
                FROM users
                WHERE id <> :id
                  AND (phone = :phone';
            $duplicateParams = [
                'id' => $userId,
                'phone' => $formValues['phone'],
            ];

            if ($formValues['email'] !== '') {
                $duplicateQuery .= ' OR email = :email';
                $duplicateParams['email'] = $formValues['email'];
            }

            $duplicateQuery .= ')
                LIMIT 1';

            $stmt = $conn->prepare($duplicateQuery);
            $stmt->execute($duplicateParams);

            if ($stmt->fetch()) {
                $error = 'Số điện thoại hoặc email này đã được sử dụng.';
            } else {
                $avatarUrl = $user['avatar_url'] ?? null;

                if (!empty($_FILES['avatar']['name'])) {
                    $avatar = $_FILES['avatar'];

                    if (!isset($avatar['error']) || $avatar['error'] !== UPLOAD_ERR_OK) {
                        $error = 'Không thể tải ảnh lên.';
                    } elseif (($avatar['size'] ?? 0) > 2 * 1024 * 1024) {
                        $error = 'Kích thước ảnh không được vượt quá 2MB.';
                    } else {
                        $imageInfo = @getimagesize($avatar['tmp_name']);
                        $mimeType = $imageInfo['mime'] ?? '';
                        $allowedMimeTypes = [
                            'image/jpeg' => 'jpg',
                            'image/png' => 'png',
                            'image/gif' => 'gif',
                            'image/webp' => 'webp',
                        ];

                        if (!isset($allowedMimeTypes[$mimeType])) {
                            $error = 'Ảnh phải có định dạng JPG, PNG, GIF, hoặc WEBP.';
                        } else {
                            $uploadDir = __DIR__ . '/assets/avatars';
                            if (!is_dir($uploadDir)) {
                                mkdir($uploadDir, 0775, true);
                            }

                            $newFileName = 'user_' . $userId . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $allowedMimeTypes[$mimeType];
                            $targetPath = $uploadDir . '/' . $newFileName;

                            if (!move_uploaded_file($avatar['tmp_name'], $targetPath)) {
                                $error = 'Không thể lưu ảnh.';
                            } else {
                                $oldAvatarPath = profile_local_avatar_path($user['avatar_url'] ?? null);
                                if ($oldAvatarPath && is_file($oldAvatarPath)) {
                                    @unlink($oldAvatarPath);
                                }

                                $avatarUrl = 'assets/avatars/' . $newFileName;
                            }
                        }
                    }
                }

                if ($error === '') {
                    $updateParams = [
                        'id' => $userId,
                        'full_name' => $formValues['full_name'],
                        'phone' => $formValues['phone'],
                        'email' => $formValues['email'] !== '' ? $formValues['email'] : null,
                        'address' => $formValues['address'] !== '' ? $formValues['address'] : null,
                        'birth_date' => $formValues['birth_date'] !== '' ? $formValues['birth_date'] : null,
                        'gender' => $formValues['gender'] !== '' ? $formValues['gender'] : null,
                        'avatar_url' => $avatarUrl !== '' ? $avatarUrl : null,
                    ];

                    $sql = '
                        UPDATE users
                        SET full_name = :full_name,
                            phone = :phone,
                            email = :email,
                            address = :address,
                            birth_date = :birth_date,
                            gender = :gender,
                            avatar_url = :avatar_url';

                    if ($newPassword !== '') {
                        $sql .= ', password = :password';
                        $updateParams['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                    }

                    $sql .= ' WHERE id = :id';

                    $stmt = $conn->prepare($sql);
                    $stmt->execute($updateParams);

                    $_SESSION['user_name'] = $formValues['full_name'];
                    $_SESSION['user_avatar'] = $avatarUrl;
                    $success = 'Cập nhật hồ sơ thành công.';

                    $stmt = $conn->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
                    $stmt->execute(['id' => $userId]);
                    $user = $stmt->fetch();
                }
            }
        }
    }
}

$avatarDisplayUrl = user_avatar_url($user['avatar_url'] ?? '', $user['full_name'] ?? 'User');
$hasLocalAvatar = trim((string) ($user['avatar_url'] ?? '')) !== '';
require_once 'includes/header.php';
?>

<div class="max-w-7xl mx-auto py-12 px-6 min-h-[70vh] mt-8">
    <div class="mb-10 border-b border-brand-border pb-6">
        <p class="section-eyebrow mb-2">Bảng Điều Khiển</p>
        <h1 class="font-display text-4xl md:text-5xl font-black text-brand-white">Hồ Sơ Của Tôi</h1>
    </div>

    <div class="flex gap-4 mb-6">
        <a href="profile.php" class="btn-gold rounded-md">Hồ Sơ</a>
        <a href="orders.php" class="btn-outline rounded-md">Đơn Hàng</a>
    </div>

    <?php if ($error): ?>
        <div class="mb-8 rounded-md border border-red-800 bg-red-900/30 px-4 py-4 text-sm font-medium text-red-400 flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-xl"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="mb-8 rounded-md border border-green-800 bg-green-900/30 px-4 py-4 text-sm font-medium text-green-400 flex items-center gap-3">
            <i class="fas fa-check-circle text-xl"></i> <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <div class="grid gap-8 lg:grid-cols-[360px_minmax(0,1fr)]">
        <div class="bg-brand-card border border-brand-border shadow-sm rounded-xl p-8 lg:sticky lg:top-28 h-fit">
            <div class="flex flex-col items-center text-center">
                <div class="relative group">
                    <img
                        id="avatar-preview"
                        src="<?= htmlspecialchars($avatarDisplayUrl) ?>"
                        alt="<?= htmlspecialchars($user['full_name']) ?>"
                        class="w-48 h-48 rounded-full object-cover border-4 border-brand-border shadow-sm bg-brand-surface transition-transform group-hover:scale-105 duration-300"
                    >
                    <button
                        type="button"
                        id="avatar-pick-btn"
                        class="absolute bottom-2 right-2 w-12 h-12 rounded-full bg-brand-gold text-black shadow-[0_0_15px_rgba(201,168,76,0.3)] hover:bg-brand-gold-light transition-colors flex items-center justify-center text-lg"
                        title="Đổi Ảnh"
                    >
                        <i class="fas fa-camera"></i>
                    </button>
                </div>

                <div class="mt-6">
                    <h2 class="font-display text-2xl font-black text-brand-white"><?= htmlspecialchars($user['full_name']) ?></h2>
                    <p class="text-sm font-bold text-brand-gold mt-2 tracking-wide"><?= htmlspecialchars($user['phone']) ?></p>
                    <?php if (!empty($user['email'])): ?>
                        <p class="text-sm text-brand-sub font-body mt-1"><?= htmlspecialchars($user['email']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-8 space-y-4 pt-6 border-t border-brand-border">
                <input type="file" id="avatar-input" name="avatar" accept="image/*" form="profile-save-form" class="hidden">
                <label for="avatar-input" class="block w-full text-center cursor-pointer rounded-md border-2 border-dashed border-brand-border px-4 py-4 text-sm font-bold text-brand-sub hover:border-brand-gold hover:text-brand-gold transition-colors bg-brand-surface">
                    <i class="fas fa-upload mr-2"></i> Tải Ảnh Mới Lên
                </label>
                <?php if ($hasLocalAvatar): ?>
                    <form method="POST" action="profile.php">
                        <input type="hidden" name="action" value="remove_avatar">
                        <button
                            type="submit"
                            class="w-full rounded-md border border-red-800 px-4 py-3 text-sm font-bold text-red-500 hover:bg-red-900/30 transition-colors"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa ảnh đại diện?');"
                        >
                            <i class="fas fa-trash-alt mr-2"></i> Xóa Ảnh
                        </button>
                    </form>
                <?php endif; ?>
                <p class="text-xs text-brand-sub font-body leading-relaxed text-center mt-2">
                    Hỗ trợ: JPG, PNG, GIF, WEBP. Tối đa: 2MB.
                </p>
            </div>
        </div>

        <div class="bg-brand-card border border-brand-border shadow-sm rounded-xl p-8 md:p-10">
            <form id="profile-save-form" method="POST" action="profile.php" enctype="multipart/form-data" class="space-y-8">
                <input type="hidden" name="action" value="save_profile">
                
                <h3 class="font-display text-xl font-bold text-brand-white mb-6">Thông Tin Cá Nhân</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <label class="block">
                        <span class="mb-2 block text-xs font-bold uppercase tracking-widest text-brand-sub">Họ và Tên *</span>
                        <input
                            type="text"
                            name="full_name"
                            value="<?= htmlspecialchars($formValues['full_name']) ?>"
                            class="form-input"
                            required
                        >
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-xs font-bold uppercase tracking-widest text-brand-sub">Số điện thoại *</span>
                        <input
                            type="text"
                            name="phone"
                            value="<?= htmlspecialchars($formValues['phone']) ?>"
                            class="form-input"
                            required
                        >
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-xs font-bold uppercase tracking-widest text-brand-sub">Email</span>
                        <input
                            type="email"
                            name="email"
                            value="<?= htmlspecialchars($formValues['email']) ?>"
                            class="form-input"
                            placeholder="Tùy chọn"
                        >
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-xs font-bold uppercase tracking-widest text-brand-sub">Ngày sinh</span>
                        <input
                            type="date"
                            name="birth_date"
                            value="<?= htmlspecialchars($formValues['birth_date']) ?>"
                            class="form-input"
                            style="color-scheme: dark;"
                        >
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-xs font-bold uppercase tracking-widest text-brand-sub">Giới tính</span>
                        <select
                            name="gender"
                            class="form-input"
                        >
                            <option value="" <?= $formValues['gender'] === '' ? 'selected' : '' ?>>Chưa xác định</option>
                            <option value="Nam" <?= ($formValues['gender'] === 'Male' || $formValues['gender'] === 'Nam') ? 'selected' : '' ?>>Nam</option>
                            <option value="Nữ" <?= ($formValues['gender'] === 'Female' || $formValues['gender'] === 'Nữ') ? 'selected' : '' ?>>Nữ</option>
                            <option value="Khác" <?= ($formValues['gender'] === 'Other' || $formValues['gender'] === 'Khác') ? 'selected' : '' ?>>Khác</option>
                        </select>
                    </label>
                    <label class="block md:col-span-2">
                        <span class="mb-2 block text-xs font-bold uppercase tracking-widest text-brand-sub">Địa chỉ giao hàng *</span>
                        <input
                            type="text"
                            name="address"
                            value="<?= htmlspecialchars($formValues['address']) ?>"
                            class="form-input"
                            placeholder="Vui lòng nhập địa chỉ nhận hàng đầy đủ"
                            required
                        >
                    </label>
                </div>

                <div class="border-t border-brand-border pt-8 mt-4">
                    <h3 class="font-display text-xl font-bold text-brand-white mb-6">Bảo Mật</h3>
                    <div class="grid gap-6 md:grid-cols-3">
                        <label class="block">
                            <span class="mb-2 block text-xs font-bold uppercase tracking-widest text-brand-sub">Mật khẩu hiện tại</span>
                            <input
                                type="password"
                                name="current_password"
                                class="form-input"
                                placeholder="Bỏ trống nếu không đổi"
                            >
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-xs font-bold uppercase tracking-widest text-brand-sub">Mật khẩu mới</span>
                            <input
                            type="password"
                            name="new_password"
                            minlength="6"
                            class="form-input"
                            placeholder="Tối thiểu 6 ký tự"
                        >
                        </label>
                        <label class="block">
                            <span class="mb-2 block text-xs font-bold uppercase tracking-widest text-brand-sub">Xác nhận MK mới</span>
                            <input
                                type="password"
                                name="confirm_password"
                                class="form-input"
                                placeholder="Nhập lại mật khẩu mới"
                            >
                        </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center gap-6 pt-6 mt-4">
                    <button type="submit" class="btn-gold rounded-md shadow-md gap-3">
                        <i class="fas fa-save"></i>
                        Lưu Thay Đổi
                    </button>
                    <p class="text-sm font-body font-medium text-brand-sub">
                        Để trống mật khẩu nếu bạn không muốn thay đổi.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarPickBtn = document.getElementById('avatar-pick-btn');

    if (avatarInput && avatarPreview) {
        const openPicker = () => avatarInput.click();

        avatarPickBtn?.addEventListener('click', openPicker);

        avatarInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) {
                return;
            }

            const url = URL.createObjectURL(file);
            avatarPreview.src = url;
            avatarPreview.onload = () => URL.revokeObjectURL(url);
        });
    }
</script>

<?php require_once 'includes/footer.php'; ?>

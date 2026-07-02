<?php
require_once __DIR__ . '/includes/header.php';

$message = '';
$error = '';

// Check if settings table exists and fetch
try {
    $stmt = $conn->query("SELECT * FROM settings WHERE id = 1");
    $settings = $stmt->fetch();
    
    if (!$settings) {
        // Fallback defaults if no record exists
        $settings = [
            'site_name' => 'TechNova Store',
            'logo_url' => '',
            'phone' => '',
            'email' => '',
            'address' => ''
        ];
    }
} catch (PDOException $e) {
    $error = "Vui lòng chạy lệnh SQL tạo bảng settings trước. Lỗi: " . $e->getMessage();
    $settings = [
        'site_name' => '', 'logo_url' => '', 'phone' => '', 'email' => '', 'address' => ''
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $site_name = trim($_POST['site_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $logo_url = $settings['logo_url']; // keep old logo by default
    
    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif'];
        if (in_array($_FILES['logo']['type'], $allowedTypes)) {
            $uploadDir = __DIR__ . '/../assets/images/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $fileName = 'logo_' . time() . '_' . basename($_FILES['logo']['name']);
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetPath)) {
                $logo_url = 'assets/images/' . $fileName;
            } else {
                $error = "Không thể tải lên file logo.";
            }
        } else {
            $error = "Định dạng ảnh không hợp lệ (Chỉ hỗ trợ JPG, PNG, WEBP, SVG, GIF).";
        }
    }
    
    if (empty($site_name)) {
        $error = "Tên website không được để trống.";
    } elseif (empty($error)) {
        try {
            // Check if record exists
            $check = $conn->query("SELECT id FROM settings WHERE id = 1")->fetch();
            if ($check) {
                $stmtUpdate = $conn->prepare("UPDATE settings SET site_name = :site_name, logo_url = :logo, phone = :phone, email = :email, address = :address WHERE id = 1");
                $stmtUpdate->execute([
                    ':site_name' => $site_name,
                    ':logo' => $logo_url,
                    ':phone' => $phone,
                    ':email' => $email,
                    ':address' => $address
                ]);
            } else {
                $stmtInsert = $conn->prepare("INSERT INTO settings (id, site_name, logo_url, phone, email, address) VALUES (1, :site_name, :logo, :phone, :email, :address)");
                $stmtInsert->execute([
                    ':site_name' => $site_name,
                    ':logo' => $logo_url,
                    ':phone' => $phone,
                    ':email' => $email,
                    ':address' => $address
                ]);
            }
            $message = "Cập nhật cài đặt thành công!";
            // Update local variable
            $settings['site_name'] = $site_name;
            $settings['logo_url'] = $logo_url;
            $settings['phone'] = $phone;
            $settings['email'] = $email;
            $settings['address'] = $address;
        } catch (PDOException $e) {
            $error = "Lỗi khi lưu cài đặt: " . $e->getMessage();
        }
    }
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Cài đặt Website</h1>
</div>

<?php if ($message): ?>
    <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-100 flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="space-y-6">
            
            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Thông tin chung</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tên Website <span class="text-red-500">*</span></label>
                        <input type="text" name="site_name" value="<?= htmlspecialchars($settings['site_name']) ?>" required
                               class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo Website</label>
                        <?php if (!empty($settings['logo_url'])): ?>
                            <div class="mb-3">
                                <img src="../<?= htmlspecialchars($settings['logo_url']) ?>" alt="Logo" class="h-16 object-contain bg-gray-50 p-2 rounded border border-gray-200">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/svg+xml,image/gif"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-primary/10 file:text-brand-primary hover:file:bg-brand-primary/20">
                        <p class="text-xs text-gray-500 mt-1">Định dạng hỗ trợ: JPG, PNG, WEBP, SVG. (Để trống nếu không muốn thay đổi)</p>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-100 pb-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Thông tin liên hệ</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại hỗ trợ</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($settings['phone']) ?>" placeholder="VD: 0901234567"
                               class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email liên hệ</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($settings['email']) ?>" placeholder="VD: support@technova.vn"
                               class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ cửa hàng</label>
                        <textarea name="address" rows="3" placeholder="Nhập địa chỉ..."
                                  class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary"><?= htmlspecialchars($settings['address']) ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-brand-primary hover:bg-brand-secondary text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Lưu cài đặt
                </button>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

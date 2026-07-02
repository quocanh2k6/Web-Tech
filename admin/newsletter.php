<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/includes/header.php';
// Include Composer's autoloader for PHPMailer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

$message = '';
$error = '';
$sentCount = 0;

// Fetch subscribers
$subscribers = [];
try {
    $stmt = $conn->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
    $subscribers = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Chưa có bảng newsletter_subscribers. Vui lòng chạy database.sql";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $subject = trim($_POST['subject'] ?? '');
    $body = trim($_POST['body'] ?? '');
    
    if (empty($subject) || empty($body)) {
        $error = "Tiêu đề và Nội dung không được để trống.";
    } elseif (count($subscribers) === 0) {
        $error = "Chưa có ai đăng ký nhận tin.";
    } else {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $error = "PHPMailer chưa được cài đặt. Vui lòng chạy lệnh 'composer install' ở thư mục gốc để tải thư viện.";
        } else {
            // Init PHPMailer
            $mail = new PHPMailer(true);
            try {
                // SMTP Configuration
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth   = true;
                $mail->Username   = 'your-email@gmail.com'; // SMTP username (REPLACE THIS)
                $mail->Password   = 'your-app-password';    // SMTP App password (REPLACE THIS)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption (587)
                $mail->Port       = 587; // TCP port to connect to

                $mail->setFrom('your-email@gmail.com', 'TechNova Store');
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = nl2br(htmlspecialchars($body)); // Convert newlines to <br> for HTML email
                
                foreach ($subscribers as $sub) {
                    try {
                        $mail->clearAddresses();
                        $mail->addAddress($sub['email']);
                        $mail->send();
                        $sentCount++;
                    } catch (Exception $e) {
                        // Log error for this specific email if needed
                        continue;
                    }
                }
                $message = "Đã gửi thư thành công cho $sentCount người đăng ký!";
            } catch (Exception $e) {
                $error = "Lỗi khi cấu hình Gửi Mail. Message: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Quản lý Newsletter</h1>
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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form gửi email -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Soạn Email gửi Hàng loạt</h2>
        <form method="POST" action="">
            <div class="space-y-4">
                <div class="bg-blue-50 text-blue-800 text-sm p-3 rounded-lg flex gap-2">
                    <i class="fas fa-info-circle mt-0.5"></i>
                    <div>Vui lòng điền thông tin SMTP thật của bạn vào file <code>admin/newsletter.php</code> (Dòng 39-40) để sử dụng được chức năng này, và đảm bảo đã chạy <code>composer install</code>.</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề (Subject) <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" required placeholder="VD: Khuyến mãi tưng bừng mùa hè..."
                           class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung (Message) <span class="text-red-500">*</span></label>
                    <textarea name="body" required rows="6" placeholder="Nhập nội dung email tại đây..."
                              class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary"></textarea>
                </div>
                
                <button type="submit" class="w-full px-6 py-2.5 bg-brand-primary hover:bg-brand-secondary text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2" <?= empty($subscribers) ? 'disabled' : '' ?>>
                    <i class="fas fa-paper-plane"></i> Gửi Mail Hàng Loạt (<?= count($subscribers) ?> người)
                </button>
            </div>
        </form>
    </div>

    <!-- Danh sách người đăng ký -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Danh sách Đăng ký (<?= count($subscribers) ?>)</h2>
        <div class="overflow-x-auto max-h-96">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-sm sticky top-0">
                        <th class="py-3 px-4 font-semibold w-16">ID</th>
                        <th class="py-3 px-4 font-semibold">Email</th>
                        <th class="py-3 px-4 font-semibold text-right">Ngày ĐK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (count($subscribers) > 0): ?>
                        <?php foreach ($subscribers as $sub): ?>
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-3 px-4 text-sm text-gray-500"><?= $sub['id'] ?></td>
                                <td class="py-3 px-4 font-medium text-gray-900"><?= htmlspecialchars($sub['email']) ?></td>
                                <td class="py-3 px-4 text-sm text-gray-500 text-right"><?= date('d/m/Y', strtotime($sub['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-500">Chưa có ai đăng ký nhận tin.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/includes/header.php';
// Include Composer's autoloader for PHPMailer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

require_once __DIR__ . '/../includes/newsletter_mailer.php';

$alert_message = '';
$alert_type = ''; // 'success' or 'error'
$sentCount = 0;

// Fetch subscribers
$subscribers = [];
try {
    $stmt = $conn->query("SELECT * FROM newsletter_subscribers ORDER BY created_at DESC");
    $subscribers = $stmt->fetchAll();
} catch (PDOException $e) {
    $alert_message = "Chưa có bảng newsletter_subscribers. Vui lòng chạy database.sql";
    $alert_type = 'error';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_newsletter'])) {
    $subject = trim($_POST['subject'] ?? '');
    $messageBody = trim($_POST['message'] ?? '');
    
    if (empty($subject) || empty($messageBody)) {
        $alert_message = "Tiêu đề và Nội dung không được để trống.";
        $alert_type = 'error';
    } elseif (count($subscribers) === 0) {
        $alert_message = "Chưa có ai đăng ký nhận tin.";
        $alert_type = 'error';
    } else {
        if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
            $alert_message = "PHPMailer chưa được cài đặt. Vui lòng chạy lệnh 'composer install' ở thư mục gốc để tải thư viện.";
            $alert_type = 'error';
        } else {
            // Get config from includes/newsletter_mailer.php
            $config = newsletter_mail_config();
            $mail = new PHPMailer(true);
            try {
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                $mail->Host       = $config['smtp_host'];
                $mail->SMTPAuth   = $config['smtp_auth'];
                $mail->Username   = $config['smtp_username'];
                $mail->Password   = $config['smtp_password'];
                if (!empty($config['smtp_secure'])) {
                    $mail->SMTPSecure = $config['smtp_secure'];
                }
                $mail->Port       = $config['smtp_port'];

                $mail->setFrom($config['from_email'], $config['from_name']);
                if ($config['reply_to_email'] !== '') {
                    $mail->addReplyTo($config['reply_to_email'], $config['reply_to_name']);
                }
                
                $mail->isHTML(true);
                $mail->Subject = $subject;
                
                foreach ($subscribers as $sub) {
                    try {
                        $mail->clearAddresses();
                        $mail->addAddress($sub['email']);
                        
                        $unsubUrl = newsletter_unsubscribe_url($sub['email']);
                        $mail->Body = nl2br(htmlspecialchars($messageBody)) . '<br><br><hr><p style="font-size:12px;color:#888;">Bạn nhận được email này vì đã đăng ký nhận tin. <a href="'.$unsubUrl.'">Hủy đăng ký</a></p>';
                        $mail->AltBody = $messageBody . "\n\nHủy đăng ký: " . $unsubUrl;
                        
                        $mail->send();
                        $sentCount++;
                    } catch (Exception $e) {
                        continue;
                    }
                }
                $alert_message = "Đã gửi thư thành công cho $sentCount người đăng ký!";
                $alert_type = 'success';
            } catch (Exception $e) {
                $alert_message = "Lỗi khi cấu hình Gửi Mail. Message: {$mail->ErrorInfo}";
                $alert_type = 'error';
            }
        }
    }
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Quản lý Newsletter</h1>
</div>

<?php if ($alert_message): ?>
    <?php if ($alert_type === 'success'): ?>
        <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 border border-green-100 flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($alert_message) ?>
        </div>
    <?php else: ?>
        <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 border border-red-100 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($alert_message) ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form gửi email -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-3">Soạn Email gửi Hàng loạt</h2>
        <form method="POST" action="">
            <div class="space-y-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề (Subject) <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" required placeholder="VD: Khuyến mãi tưng bừng mùa hè..."
                           class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/20 focus:border-[#C9A84C]">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung (Message) <span class="text-red-500">*</span></label>
                    <textarea name="message" required rows="6" placeholder="Nhập nội dung email tại đây..."
                              class="w-full p-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#C9A84C]/20 focus:border-[#C9A84C]"></textarea>
                </div>
                
                <button type="submit" name="send_newsletter" class="w-full px-5 py-2.5 bg-[#C9A84C] hover:bg-[#b5953e] text-white font-semibold rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2" <?= empty($subscribers) ? 'disabled' : '' ?>>
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

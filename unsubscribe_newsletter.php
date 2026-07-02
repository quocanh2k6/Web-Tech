<?php
require_once 'includes/newsletter_mailer.php';
require_once 'db_connect.php';

$email = isset($_GET['email']) ? trim((string) $_GET['email']) : '';
$token = isset($_GET['token']) ? trim((string) $_GET['token']) : '';
$expectedToken = newsletter_unsubscribe_token($email);
$validRequest = $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) && hash_equals($expectedToken, $token);

$title = 'Hủy đăng ký bản tin';
$message = 'Yêu cầu không hợp lệ.';
$success = false;

if ($validRequest) {
    try {
        $stmt = $conn->prepare('DELETE FROM newsletter_subscribers WHERE email = :email');
        $stmt->execute(['email' => strtolower($email)]);

        $success = true;
        $message = 'Bạn đã hủy đăng ký bản tin thành công.';
    } catch (PDOException $e) {
        $message = 'Không thể hủy đăng ký lúc này. Vui lòng thử lại sau.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body { margin: 0; font-family: 'Inter', Arial, Helvetica, sans-serif; background: #050505; color: #f5f5f7; }
        .wrap { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card { max-width: 560px; width: 100%; background: #0f0f0f; border: 1px solid #222222; border-radius: 24px; padding: 40px 32px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,.5); }
        .eyebrow { text-transform: uppercase; letter-spacing: .2em; font-size: 12px; color: #a1a1aa; margin-bottom: 14px; font-weight: 700; }
        h1 { font-family: 'Syne', Georgia, serif; font-size: 32px; line-height: 1.2; margin: 0 0 16px; font-weight: 900; }
        p { line-height: 1.8; color: #a1a1aa; }
        .btn { display: inline-block; margin-top: 16px; padding: 16px 32px; border-radius: 8px; background: #C9A84C; color: #000; text-decoration: none; font-weight: 700; text-transform: uppercase; font-size: 14px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(201, 168, 76, 0.3); }
        .btn:hover { background: #E2C063; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(201, 168, 76, 0.5); }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="card">
            <div class="eyebrow" style="<?= $success ? 'color: #10b981;' : 'color: #ef4444;' ?>"><?= $success ? 'Thành công' : 'Lưu ý' ?></div>
            <h1><?= htmlspecialchars($title) ?></h1>
            <p><?= htmlspecialchars($message) ?></p>
            <a class="btn" href="<?= htmlspecialchars(newsletter_mail_config()['base_url']) ?>">Quay lại trang chủ</a>
        </div>
    </div>
</body>
</html>

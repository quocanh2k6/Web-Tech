<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Only POST request supported']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$user_message = $input['message'] ?? '';

if (empty($user_message)) {
    echo json_encode(['error' => 'Message cannot be empty']);
    exit;
}

$api_key = "YOUR_GEMINI_API_KEY_HERE";
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent";

// System instruction setup
$system_instruction = "Bạn là chuyên gia tư vấn công nghệ và nhân viên hỗ trợ khách hàng của 'TechNova Store', một cửa hàng bán lẻ thiết bị điện tử cao cấp. Tên của bạn là Nova. Hãy tỏ ra hữu ích, ngắn gọn, chuyên nghiệp và có kiến thức sâu rộng về smartphone, laptop, âm thanh và phụ kiện. Nếu được hỏi về các đơn hàng cụ thể, hãy khuyên họ kiểm tra Lịch Sử Đơn Hàng hoặc liên hệ support@technova.vn.";

$data = [
    "systemInstruction" => [
        "parts" => [
            ["text" => $system_instruction]
        ]
    ],
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => $user_message]
            ]
        ]
    ],
    "generationConfig" => [
        "temperature" => 0.7,
        "maxOutputTokens" => 800
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix SSL for local XAMPP
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-goog-api-key: ' . $api_key
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpcode == 200) {
    $result = json_decode($response, true);
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $bot_text = $result['candidates'][0]['content']['parts'][0]['text'];
        echo json_encode(['reply' => $bot_text]);
    } else {
        echo json_encode(['reply' => "Xin lỗi, tôi đang gặp sự cố kết nối với hệ thống."]);
    }
} else {
    echo json_encode(['reply' => "Xin lỗi, hệ thống liên lạc đang bị lỗi. Vui lòng thử lại sau.", "debug" => $response]);
}
?>

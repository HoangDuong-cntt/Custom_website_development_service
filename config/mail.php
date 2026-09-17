<?php
/*
 * File: config/mail.php
 * Mục đích: Cấu hình SMTP cho việc gửi email xác nhận tới khách hàng và quản trị viên.
 * Phần: System config / email service.
 */
// PHPMailer is already installed via Composer.
// For Gmail, use an App Password; never use the normal account password.
// You can either:
// 1) set environment variables before running PHP, or
// 2) edit the values below for a local/test environment.

// Hàm helper lấy giá trị cấu hình từ environment hoặc mặc định nếu chưa khai báo.
$env = static function (string $key, $default = null) {
    $value = getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return $value;
};

// Chỉ bật gửi email khi MAIL_ENABLED=true; tránh lỗi vận hành làm mất dữ liệu lead.
$enabled = filter_var($env('MAIL_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN);

return [
    'enabled' => $enabled,
    'host' => $env('MAIL_HOST', 'smtp.gmail.com'),
    'port' => (int)($env('MAIL_PORT', '587')),
    'username' => $env('MAIL_USERNAME', 'your-email@gmail.com'),
    'password' => $env('MAIL_PASSWORD', 'YOUR_GMAIL_APP_PASSWORD'),
    'encryption' => $env('MAIL_ENCRYPTION', 'tls'), // tls for 587, ssl for 465
    'from_email' => $env('MAIL_FROM_EMAIL', 'contact@hoangduongtech.com'),
    'from_name' => $env('MAIL_FROM_NAME', 'HoangDuongTech'),
    'profile_url' => $env('MAIL_PROFILE_URL', 'https://example.com/ho-so-nang-luc'),
    'pricing_url' => $env('MAIL_PRICING_URL', 'https://example.com/bang-gia'),
];

<?php
// PHPMailer is already installed via Composer.
// For Gmail, use an App Password; never use the normal account password.
// You can either:
// 1) set environment variables before running PHP, or
// 2) edit the values below for a local/test environment.

$env = static function (string $key, $default = null) {
    $value = getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return $value;
};

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

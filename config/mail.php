<?php
// Enable after installing PHPMailer: composer require phpmailer/phpmailer
// For Gmail, use a Google App Password; never use your account password here.
return [
    'enabled' => false,
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => 'your-email@gmail.com',
    'password' => 'YOUR_GMAIL_APP_PASSWORD',
    'encryption' => 'tls', // tls for 587, ssl for 465
    'from_email' => 'contact@hoangduongtech.com',
    'from_name' => 'HoangDuongTech',
    'profile_url' => 'https://example.com/ho-so-nang-luc',
    'pricing_url' => 'https://example.com/bang-gia',
];

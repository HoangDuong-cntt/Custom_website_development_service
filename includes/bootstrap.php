<?php

declare(strict_types=1);
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')]);
session_start();
require_once __DIR__ . '/../config/database.php';
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function settings(): array
{
    static $data;
    if ($data === null) $data = db()->query('SELECT key_name, value FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR);
    return $data;
}
function is_admin(): bool
{
    return ($_SESSION['admin_logged_in'] ?? false) === true && !empty($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
}
function require_admin(): void
{
    if (!is_admin()) {
        header('Location: ../index.php');
        exit;
    }
}
function csrf(): string
{
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function verify_csrf(): bool
{
    return hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '');
}
function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf()) . '">';
}
function client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

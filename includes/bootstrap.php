<?php
/*
 * File: includes/bootstrap.php
 * Mục đích: Khởi tạo session, include database, định nghĩa helper chung và kiểm tra quyền admin.
 * Phần: Core bootstrap / shared utilities.
 */

declare(strict_types=1);
// Bật session an toàn với cookie HttpOnly và SameSite để bảo vệ phiên truy cập.
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')]);
session_start();
require_once __DIR__ . '/../config/database.php';
const SITE_LOGO_FILE = 'assets/images/logo-hdtech.svg';
const SITE_LOGO_PATH = __DIR__ . '/../assets/images/logo-hdtech.svg';
/**
 * Escapes dữ liệu hiển thị để tránh XSS khi render ra HTML.
 * Tham số: $value - chuỗi cần escape hoặc null.
 * Giá trị trả về: Chuỗi đã được mã hóa HTML an toàn.
 */
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
/**
 * Đọc cấu hình hệ thống từ bảng settings trong database.
 * Tham số: Không có tham số đầu vào.
 * Giá trị trả về: Mảng key => value của các setting công khai.
 */
function settings(): array
{
    static $data;
    if ($data === null) $data = db()->query('SELECT key_name, value FROM settings')->fetchAll(PDO::FETCH_KEY_PAIR);
    return $data;
}
/**
 * Kiểm tra người dùng hiện tại có quyền quản trị hay không.
 * Tham số: Không có tham số đầu vào.
 * Giá trị trả về: true nếu đã đăng nhập với role admin, ngược lại false.
 */
function is_admin(): bool
{
    return ($_SESSION['admin_logged_in'] ?? false) === true && !empty($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
}
/**
 * Chặn truy cập nếu user chưa đủ quyền admin.
 * Tham số: Không có tham số đầu vào.
 * Giá trị trả về: Không trả về giá trị; chuyển hướng về trang chủ khi không đủ quyền.
 */
function require_admin(): void
{
    if (!is_admin()) {
        header('Location: ../index.php');
        exit;
    }
}
/**
 * Tạo hoặc lấy CSRF token để bảo vệ form khỏi request giả mạo.
 * Tham số: Không có tham số đầu vào.
 * Giá trị trả về: Chuỗi token duy nhất được lưu trong session.
 */
function csrf(): string
{
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
/**
 * Xác thực token gửi từ form để đảm bảo request hợp lệ.
 * Tham số: Không có tham số đầu vào; đọc $_POST['csrf'].
 * Giá trị trả về: true nếu token khớp, false nếu không hợp lệ.
 */
function verify_csrf(): bool
{
    return hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '');
}
/**
 * Tạo input ẩn chứa CSRF token để nhúng vào form HTML.
 * Tham số: Không có tham số đầu vào.
 * Giá trị trả về: HTML hidden input với token đã escape.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf()) . '">';
}
/**
 * Lấy địa chỉ IP của client hiện tại.
 * Tham số: Không có tham số đầu vào.
 * Giá trị trả về: Chuỗi IP người dùng hoặc 0.0.0.0 nếu không có dữ liệu.
 */
function client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

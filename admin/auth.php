<?php
/*
 * File: admin/auth.php
 * Mục đích: Kiểm tra trạng thái đăng nhập admin trước khi truy cập các trang quản trị.
 * Phần: Authentication / permission check.
 */
require_once __DIR__ . '/../includes/bootstrap.php';
// Bảo vệ các trang trong admin khỏi truy cập không được phép.
if (!is_admin()) {
    header('Location: login.php');
    exit;
}

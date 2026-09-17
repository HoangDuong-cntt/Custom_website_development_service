<?php
/*
 * File: admin/logout.php
 * Mục đích: Đăng xuất admin và xóa phiên làm việc hiện tại.
 * Phần: Authentication / session management.
 */
require_once __DIR__ . '/auth.php';
// Xóa toàn bộ session để kết thúc quyền quản trị ngay lập tức.
$_SESSION = [];
session_destroy();
header('Location: ../index.php');

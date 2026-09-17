<?php
/*
 * File: admin/contract_print.php
 * Mục đích: Chuyển hướng tệp cũ sang trang in hợp đồng mới để giữ tương thích khi admin truy cập link cũ.
 * Phần: Admin / backward compatibility.
 */
require_once __DIR__ . '/auth.php';
header('Location: print_contract.php?id=' . (int)($_GET['id'] ?? 0));
exit;

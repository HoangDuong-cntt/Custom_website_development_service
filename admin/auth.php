<?php
require_once __DIR__.'/../includes/bootstrap.php';
if (($_SESSION['admin_logged_in'] ?? false) !== true || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

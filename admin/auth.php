<?php
require_once __DIR__ . '/../includes/bootstrap.php';
if (!is_admin()) {
    header('Location: login.php');
    exit;
}

<?php
// XAMPP defaults. Change only these constants for another environment.
const DB_HOST = '127.0.0.1'; const DB_PORT = '3306'; const DB_NAME = 'hoangduongtech_db';
const DB_USER = 'root'; const DB_PASS = '';
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    try {
        $pdo = new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) { http_response_code(500); exit('Không thể kết nối cơ sở dữ liệu. Hãy import database.sql và kiểm tra XAMPP.'); }
}

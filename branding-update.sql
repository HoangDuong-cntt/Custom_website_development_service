-- Run this once in phpMyAdmin if hoangduongtech_db was imported before the rebrand.
USE hoangduongtech_db;
UPDATE settings SET value = 'HoangDuongTech' WHERE key_name = 'site_name';
UPDATE settings SET value = 'hello@hoangduongtech.vn' WHERE key_name = 'notification_email';

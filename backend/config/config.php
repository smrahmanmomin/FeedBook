<?php
// ============================================
// FeedBook Configuration
// ============================================
define('DB_HOST', 'localhost');
define('DB_USER', getenv('FEEDBOOK_DB_USER') ?: '[REDACTED]');
define('DB_PASS', getenv('FEEDBOOK_DB_PASS') ?: '[REDACTED]');
define('DB_NAME', getenv('FEEDBOOK_DB_NAME') ?: 'kawaaiin_feedbook');
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
$basePath = ($scriptDir === '/' || $scriptDir === '.') ? '' : rtrim($scriptDir, '/');
define('SITE_URL', $scheme . '://' . $host . $basePath);
define('UPLOAD_DIR', __DIR__ . '/../../uploads/');
define('MAX_IMAGE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Dhaka');
?>

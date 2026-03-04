<?php
// ============================================
// FeedBook Configuration
// ============================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'feedbook');
define('SITE_URL', 'http://localhost:8080/feedbook');
define('UPLOAD_DIR', __DIR__ . '/../../uploads/');
define('MAX_IMAGE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Dhaka');
?>

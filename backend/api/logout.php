<?php
require_once __DIR__ . '/../controllers/AuthController.php';
AuthController::logout();
$basePath = defined('APP_BASE_PATH') ? APP_BASE_PATH : '';
header('Location: ' . $basePath . '/login');
exit;
?>

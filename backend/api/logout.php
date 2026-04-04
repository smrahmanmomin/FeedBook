<?php
require_once __DIR__ . '/../controllers/AuthController.php';
AuthController::logout();
header('Location: /feedbook/login');
exit;
?>

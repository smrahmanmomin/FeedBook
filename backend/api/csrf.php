<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../middleware/CsrfMiddleware.php';
echo json_encode(['token' => CsrfMiddleware::generate_token()]);
?>

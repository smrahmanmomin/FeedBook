<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/PostController.php';
$q = trim($_GET['q'] ?? '');
if (empty($q)) { echo json_encode(['posts'=>[]]); exit; }
echo json_encode(['posts' => PostController::search($q)]);
?>

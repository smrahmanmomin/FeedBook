<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/PostController.php';
$cat = $_GET['category_id'] ?? null;
echo json_encode(['posts' => PostController::get_all($cat)]);
?>

<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/PostController.php';
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { echo json_encode(['error'=>'Invalid ID']); exit; }
$post = PostController::get_by_id($id);
if (!$post) { http_response_code(404); echo json_encode(['error'=>'Post not found']); exit; }
echo json_encode($post);
?>

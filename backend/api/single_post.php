<?php
ob_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../controllers/CommentController.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { ob_clean(); echo json_encode(['error'=>'Invalid ID']); exit; }

$post = PostController::get_by_id($id);
if (!$post) { 
    http_response_code(404); 
    ob_clean(); 
    echo json_encode(['error'=>'Post not found']); 
    exit; 
}

try {
    $post['comments'] = CommentController::get_by_post($id) ?? [];
    $post['comment_count'] = CommentController::get_count_by_post($id) ?? 0;
} catch (Exception $e) {
    $post['comments'] = [];
    $post['comment_count'] = 0;
}

ob_clean();
echo json_encode($post);
?>

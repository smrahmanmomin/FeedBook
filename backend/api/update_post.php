<?php
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['success'=>false,'message'=>'Login required']); exit; }
require_once __DIR__ . '/../controllers/PostController.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false,'message'=>'POST only']); exit; }

$id = intval($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$category_id = $_POST['category_id'] ?? null;
if (empty($category_id)) $category_id = null;

if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid post ID']); exit; }
$post = PostController::get_by_id($id);
if (!$post) { echo json_encode(['success'=>false,'message'=>'Post not found']); exit; }
if ($post['user_id'] != $_SESSION['user_id'] && ($_SESSION['role']??'') !== 'admin') {
    echo json_encode(['success'=>false,'message'=>'Not authorized']); exit;
}

$image = PostController::handleImageUpload($_FILES['image'] ?? null);
echo json_encode(PostController::update($id, $title, $content, $category_id, $image));
?>

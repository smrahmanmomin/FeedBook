<?php
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['success'=>false,'message'=>'Login required']); exit; }
require_once __DIR__ . '/../controllers/PostController.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false,'message'=>'POST only']); exit; }

// Handle FormData (multipart) for image upload
$title = trim($_POST['title'] ?? '');
$content = trim($_POST['content'] ?? '');
$category_id = $_POST['category_id'] ?? null;
if (empty($category_id)) $category_id = null;

$image = PostController::handleImageUpload($_FILES['image'] ?? null);
echo json_encode(PostController::create($title, $content, $_SESSION['user_id'], $category_id, $image));
?>

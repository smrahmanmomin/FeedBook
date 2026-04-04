<?php
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['success'=>false,'message'=>'Login required']); exit; }
require_once __DIR__ . '/../controllers/PostController.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false,'message'=>'POST only']); exit; }
$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
if ($id <= 0) { echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }
echo json_encode(PostController::delete($id, $_SESSION['user_id']));
?>

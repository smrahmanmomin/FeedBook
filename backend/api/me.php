<?php
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'Login required']); exit; }
require_once __DIR__ . '/../controllers/UserController.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode(UserController::update_profile($_SESSION['user_id'], trim($data['name']??''), trim($data['email']??'')));
} else {
    $user = UserController::get_user($_SESSION['user_id']);
    echo json_encode($user ?: ['error'=>'Not found']);
}
?>

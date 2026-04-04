<?php
header('Content-Type: application/json');
if (!isset($_SESSION['user_id']) || ($_SESSION['role']??'') !== 'admin') {
    http_response_code(403); echo json_encode(['error'=>'Admin only']); exit;
}
require_once __DIR__ . '/../controllers/UserController.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode(UserController::delete_user(intval($data['id']??0)));
} else {
    echo json_encode(['users' => UserController::get_all()]);
}
?>

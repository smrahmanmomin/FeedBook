<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/CategoryController.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role']??'') !== 'admin') {
        http_response_code(403);
        echo json_encode(['success'=>false,'message'=>'Admin only']);
        exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    if (!is_array($data)) {
        $data = [];
    }
    $action = $data['action'] ?? 'create';

    if ($action === 'delete') {
        echo json_encode(CategoryController::delete((int)($data['id'] ?? 0)));
        exit;
    }

    echo json_encode(CategoryController::create(trim($data['name'] ?? '')));
} else {
    echo json_encode(['categories' => CategoryController::get_all()]);
}
?>

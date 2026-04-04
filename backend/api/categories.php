<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/CategoryController.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role']??'') !== 'admin') {
        echo json_encode(['success'=>false,'message'=>'Admin only']); exit;
    }
    $data = json_decode(file_get_contents('php://input'), true);
    echo json_encode(CategoryController::create(trim($data['name']??'')));
} else {
    echo json_encode(['categories' => CategoryController::get_all()]);
}
?>

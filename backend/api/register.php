<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/AuthController.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false,'message'=>'POST only']); exit; }
$data = json_decode(file_get_contents('php://input'), true);
echo json_encode(AuthController::register(trim($data['name']??''), trim($data['email']??''), $data['password']??'', $data['confirm_password']??null));
?>

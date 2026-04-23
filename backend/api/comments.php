<?php
ob_start();
header('Content-Type: application/json');

// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', '0');

try {
    $action = $_GET['action'] ?? $_POST['action'] ?? null;

    // GET: Fetch comments for a post
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {
        require_once __DIR__ . '/../controllers/CommentController.php';
        
        $post_id = intval($_GET['post_id'] ?? 0);
        if ($post_id <= 0) {
            http_response_code(400);
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
            exit;
        }
        
        try {
            $comments = CommentController::get_by_post($post_id);
            if ($comments === false) $comments = [];
            ob_clean();
            echo json_encode(['success' => true, 'comments' => $comments ?? []]);
        } catch (Exception $e) {
            http_response_code(500);
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // POST: Create a comment
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'create') {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Login required']);
            exit;
        }
        
        require_once __DIR__ . '/../controllers/CommentController.php';
        
        $data = json_decode(file_get_contents('php://input'), true);
        $post_id = intval($data['post_id'] ?? 0);
        $content = $data['content'] ?? '';
        
        if ($post_id <= 0) {
            http_response_code(400);
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Invalid post ID']);
            exit;
        }
        
        try {
            $result = CommentController::create($post_id, $_SESSION['user_id'], $content);
            http_response_code($result['success'] ? 201 : 400);
            ob_clean();
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    // POST: Delete a comment
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'delete') {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Login required']);
            exit;
        }
        
        require_once __DIR__ . '/../controllers/CommentController.php';
        
        $data = json_decode(file_get_contents('php://input'), true);
        $comment_id = intval($data['id'] ?? 0);
        
        if ($comment_id <= 0) {
            http_response_code(400);
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Invalid comment ID']);
            exit;
        }
        
        try {
            $result = CommentController::delete($comment_id, $_SESSION['user_id']);
            http_response_code($result['success'] ? 200 : 403);
            ob_clean();
            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    http_response_code(400);
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
} catch (Exception $e) {
    http_response_code(500);
    ob_clean();
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
?>

<?php
class AuthMiddleware {
    public static function require_login() {
        if (!isset($_SESSION['user_id'])) {
            if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Login required']);
                exit;
            }
            header('Location: /feedbook/login');
            exit;
        }
    }

    public static function require_admin() {
        self::require_login();
        if (($_SESSION['role'] ?? '') !== 'admin') {
            if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Admin access required']);
                exit;
            }
            header('Location: /feedbook/');
            exit;
        }
    }

    public static function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}
?>

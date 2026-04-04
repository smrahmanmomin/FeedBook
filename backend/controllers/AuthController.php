<?php
require_once __DIR__ . '/../models/UserModel.php';

class AuthController {
    public static function login($email, $password) {
        try {
            $m = new UserModel();
            $user = $m->get_by_email($email);
            if (!$user || !password_verify($password, $user['password'])) {
                return ['success' => false, 'message' => 'Invalid email or password'];
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name']    = $user['name'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];
            return ['success' => true, 'message' => 'Login successful'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function register($name, $email, $password, $confirm_password = null) {
        try {
            if (empty($name) || empty($email) || empty($password)) {
                return ['success' => false, 'message' => 'All fields are required'];
            }
            if ($confirm_password !== null && $password !== $confirm_password) {
                return ['success' => false, 'message' => 'Passwords do not match'];
            }
            if (strlen($password) < 6) {
                return ['success' => false, 'message' => 'Password must be at least 6 characters'];
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Invalid email address'];
            }
            $m = new UserModel();
            if ($m->get_by_email($email)) {
                return ['success' => false, 'message' => 'Email already registered'];
            }
            $m->create($name, $email, password_hash($password, PASSWORD_BCRYPT));
            return ['success' => true, 'message' => 'Registration successful! Please login.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function logout() {
        $_SESSION = [];
        session_destroy();
        return ['success' => true];
    }
}
?>

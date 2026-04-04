<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserController {
    public static function get_user($id) {
        try { return (new UserModel())->get_by_id($id); } catch (Exception $e) { return null; }
    }

    public static function update_profile($id, $name, $email) {
        try {
            if (empty($name) || empty($email)) return ['success' => false, 'message' => 'Name and email required'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['success' => false, 'message' => 'Invalid email'];
            $m = new UserModel();
            $ex = $m->get_by_email($email);
            if ($ex && $ex['id'] != $id) return ['success' => false, 'message' => 'Email already taken'];
            $m->update($id, $name, $email);
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            return ['success' => true, 'message' => 'Profile updated!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function get_all() {
        try { return (new UserModel())->get_all(); } catch (Exception $e) { return []; }
    }

    public static function delete_user($id) {
        try { (new UserModel())->delete($id); return ['success' => true]; } catch (Exception $e) { return ['success' => false]; }
    }
}
?>

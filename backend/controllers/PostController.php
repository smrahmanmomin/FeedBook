<?php
require_once __DIR__ . '/../models/PostModel.php';

class PostController {
    public static function create($title, $content, $user_id, $category_id = null, $image = null) {
        try {
            if (empty($title) || empty($content)) {
                return ['success' => false, 'message' => 'Title and content are required'];
            }
            $id = (new PostModel())->create($title, $content, $user_id, $category_id, $image);
            return ['success' => true, 'message' => 'Post created!', 'id' => $id];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function get_all($category_id = null) {
        try { return (new PostModel())->get_all($category_id); } catch (Exception $e) { return []; }
    }

    public static function get_by_id($id) {
        try { return (new PostModel())->get_by_id($id); } catch (Exception $e) { return null; }
    }

    public static function get_by_user($user_id) {
        try { return (new PostModel())->get_by_user($user_id); } catch (Exception $e) { return []; }
    }

    public static function search($query) {
        try { return (new PostModel())->search($query); } catch (Exception $e) { return []; }
    }

    public static function update($id, $title, $content, $category_id = null, $image = null) {
        try {
            if (empty($title) || empty($content)) {
                return ['success' => false, 'message' => 'Title and content are required'];
            }
            (new PostModel())->update($id, $title, $content, $category_id, $image);
            return ['success' => true, 'message' => 'Post updated!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function delete($id, $user_id) {
        try {
            $post = (new PostModel())->get_by_id($id);
            if (!$post) return ['success' => false, 'message' => 'Post not found'];
            if ($post['user_id'] != $user_id && ($_SESSION['role'] ?? '') !== 'admin') {
                return ['success' => false, 'message' => 'Not authorized'];
            }
            // Delete image file if exists
            if ($post['image'] && file_exists(__DIR__ . '/../../uploads/' . $post['image'])) {
                unlink(__DIR__ . '/../../uploads/' . $post['image']);
            }
            (new PostModel())->delete($id);
            return ['success' => true, 'message' => 'Post deleted!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function handleImageUpload($file) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) return null;
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed)) return null;
        if ($file['size'] > 5 * 1024 * 1024) return null;
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('post_', true) . '.' . $ext;
        $dest = __DIR__ . '/../../uploads/' . $filename;
        if (!is_dir(dirname($dest))) mkdir(dirname($dest), 0777, true);
        if (move_uploaded_file($file['tmp_name'], $dest)) return $filename;
        return null;
    }
}
?>

<?php
require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../models/PostModel.php';

class CommentController {
    public static function create($post_id, $user_id, $content) {
        try {
            if (empty($content) || strlen($content) < 1) {
                return ['success' => false, 'message' => 'Comment cannot be empty'];
            }
            if (strlen($content) > 5000) {
                return ['success' => false, 'message' => 'Comment is too long'];
            }
            // Verify post exists
            $post = (new PostModel())->get_by_id($post_id);
            if (!$post) {
                return ['success' => false, 'message' => 'Post not found'];
            }
            $id = (new CommentModel())->create($post_id, $user_id, $content);
            return ['success' => true, 'message' => 'Comment posted!', 'id' => $id];
        } catch (Exception $e) {
            error_log('CommentController::create error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function get_by_post($post_id) {
        try {
            $model = new CommentModel();
            $result = $model->get_by_post($post_id);
            return is_array($result) ? $result : [];
        } catch (Exception $e) {
            error_log('CommentController::get_by_post error: ' . $e->getMessage());
            return [];
        }
    }

    public static function get_by_id($id) {
        try {
            return (new CommentModel())->get_by_id($id);
        } catch (Exception $e) {
            error_log('CommentController::get_by_id error: ' . $e->getMessage());
            return null;
        }
    }

    public static function delete($id, $user_id) {
        try {
            $comment = (new CommentModel())->get_by_id($id);
            if (!$comment) {
                return ['success' => false, 'message' => 'Comment not found'];
            }
            if ($comment['user_id'] != $user_id && ($_SESSION['role'] ?? '') !== 'admin') {
                return ['success' => false, 'message' => 'Not authorized'];
            }
            (new CommentModel())->delete($id);
            return ['success' => true, 'message' => 'Comment deleted!'];
        } catch (Exception $e) {
            error_log('CommentController::delete error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function get_count_by_post($post_id) {
        try {
            return (new CommentModel())->get_count_by_post($post_id);
        } catch (Exception $e) {
            error_log('CommentController::get_count_by_post error: ' . $e->getMessage());
            return 0;
        }
    }
}
?>

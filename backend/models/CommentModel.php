<?php
require_once __DIR__ . '/../config/database.php';

class CommentModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function create($post_id, $user_id, $content) {
        $stmt = $this->db->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $user_id, $content]);
        return $this->db->lastInsertId();
    }

    public function get_by_post($post_id) {
        $stmt = $this->db->prepare(
            "SELECT comments.*, users.name AS author
             FROM comments
             LEFT JOIN users ON comments.user_id = users.id
             WHERE comments.post_id = ?
             ORDER BY comments.created_at DESC"
        );
        $stmt->execute([$post_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return is_array($result) ? $result : [];
    }

    public function get_by_id($id) {
        $stmt = $this->db->prepare(
            "SELECT comments.*, users.name AS author
             FROM comments
             LEFT JOIN users ON comments.user_id = users.id
             WHERE comments.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function get_count_by_post($post_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM comments WHERE post_id = ?");
        $stmt->execute([$post_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
?>

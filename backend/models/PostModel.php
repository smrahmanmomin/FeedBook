<?php
require_once __DIR__ . '/../config/database.php';

class PostModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function create($title, $content, $user_id, $category_id = null, $image = null) {
        $stmt = $this->db->prepare("INSERT INTO posts (title, content, user_id, category_id, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $content, $user_id, $category_id, $image]);
        return $this->db->lastInsertId();
    }

    public function get_all($category_id = null) {
        $sql = "SELECT posts.*, users.name AS author, categories.name AS category_name
                FROM posts
                LEFT JOIN users ON posts.user_id = users.id
                LEFT JOIN categories ON posts.category_id = categories.id";
        $params = [];
        if ($category_id) {
            $sql .= " WHERE posts.category_id = ?";
            $params[] = $category_id;
        }
        $sql .= " ORDER BY posts.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function get_by_id($id) {
        $stmt = $this->db->prepare(
            "SELECT posts.*, users.name AS author, categories.name AS category_name
             FROM posts
             LEFT JOIN users ON posts.user_id = users.id
             LEFT JOIN categories ON posts.category_id = categories.id
             WHERE posts.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function get_by_user($user_id) {
        $stmt = $this->db->prepare(
            "SELECT posts.*, categories.name AS category_name
             FROM posts LEFT JOIN categories ON posts.category_id = categories.id
             WHERE posts.user_id = ? ORDER BY posts.created_at DESC"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function search($query) {
        $q = "%$query%";
        $stmt = $this->db->prepare(
            "SELECT posts.*, users.name AS author, categories.name AS category_name
             FROM posts
             LEFT JOIN users ON posts.user_id = users.id
             LEFT JOIN categories ON posts.category_id = categories.id
             WHERE posts.title LIKE ? OR posts.content LIKE ?
             ORDER BY posts.created_at DESC"
        );
        $stmt->execute([$q, $q]);
        return $stmt->fetchAll();
    }

    public function update($id, $title, $content, $category_id = null, $image = null) {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE posts SET title=?, content=?, category_id=?, image=? WHERE id=?");
            return $stmt->execute([$title, $content, $category_id, $image, $id]);
        }
        $stmt = $this->db->prepare("UPDATE posts SET title=?, content=?, category_id=? WHERE id=?");
        return $stmt->execute([$title, $content, $category_id, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>

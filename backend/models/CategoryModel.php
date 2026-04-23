<?php
require_once __DIR__ . '/../config/database.php';

class CategoryModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function get_all() {
        $stmt = $this->db->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function get_by_id($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function get_by_name($name) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE LOWER(name) = LOWER(?) LIMIT 1");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    public function create($name) {
        $stmt = $this->db->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$name]);
        return $this->db->lastInsertId();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>

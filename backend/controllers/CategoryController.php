<?php
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController {
    public static function get_all() {
        try { return (new CategoryModel())->get_all(); } catch (Exception $e) { return []; }
    }

    public static function create($name) {
        try {
            if (empty($name)) return ['success' => false, 'message' => 'Name is required'];
            $id = (new CategoryModel())->create($name);
            return ['success' => true, 'id' => $id, 'message' => 'Category created!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function delete($id) {
        try { (new CategoryModel())->delete($id); return ['success' => true]; } catch (Exception $e) { return ['success' => false]; }
    }
}
?>

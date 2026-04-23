<?php
require_once __DIR__ . '/../models/CategoryModel.php';

class CategoryController {
    public static function get_all() {
        try { return (new CategoryModel())->get_all(); } catch (Exception $e) { return []; }
    }

    public static function create($name) {
        try {
            $name = trim($name);
            if ($name === '') return ['success' => false, 'message' => 'Name is required'];

            $model = new CategoryModel();
            if ($model->get_by_name($name)) {
                return ['success' => false, 'message' => 'Category already exists'];
            }

            $id = $model->create($name);
            return ['success' => true, 'id' => $id, 'message' => 'Category created!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    public static function delete($id) {
        try {
            if ($id <= 0) return ['success' => false, 'message' => 'Invalid category'];
            (new CategoryModel())->delete($id);
            return ['success' => true, 'message' => 'Category deleted'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Could not delete category'];
        }
    }
}
?>

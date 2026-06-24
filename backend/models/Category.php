<?php
/**
 * Category Model
 * Handle category database operations
 */

class Category {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all categories
     */
    public function getAllCategories() {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT * FROM categories WHERE is_active = TRUE ORDER BY name ASC'
            );
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get category by ID
     */
    public function getCategoryById($category_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT * FROM categories WHERE id = ? AND is_active = TRUE'
            );
            $stmt->execute([$category_id]);
            
            return $stmt->fetch() ?: null;
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Add category (admin only)
     */
    public function addCategory($data) {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO categories (name, description, image_url) VALUES (?, ?, ?)'
            );
            
            $stmt->execute([
                $data['name'],
                $data['description'] ?? '',
                $data['image_url'] ?? ''
            ]);
            
            return [
                'success' => true,
                'category_id' => $this->pdo->lastInsertId(),
                'message' => 'Category added successfully'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update category (admin only)
     */
    public function updateCategory($category_id, $data) {
        try {
            $allowed_fields = ['name', 'description', 'image_url', 'is_active'];
            $update_fields = [];
            $values = [];
            
            foreach ($allowed_fields as $field) {
                if (isset($data[$field])) {
                    $update_fields[] = "$field = ?";
                    $values[] = $data[$field];
                }
            }
            
            if (empty($update_fields)) {
                return ['success' => false, 'error' => 'No fields to update'];
            }
            
            $values[] = $category_id;
            $query = 'UPDATE categories SET ' . implode(', ', $update_fields) . ' WHERE id = ?';
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);
            
            return ['success' => true, 'message' => 'Category updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Delete category (admin only)
     */
    public function deleteCategory($category_id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM categories WHERE id = ?');
            $stmt->execute([$category_id]);
            
            return ['success' => true, 'message' => 'Category deleted successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

?>

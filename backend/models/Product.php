<?php
/**
 * Product Model
 * Handle product database operations
 */

class Product {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get all products
     */
    public function getAllProducts($limit = 20, $offset = 0, $category_id = null) {
        try {
            $query = 'SELECT * FROM products WHERE is_active = TRUE';
            $params = [];
            
            if ($category_id) {
                $query .= ' AND category_id = ?';
                $params[] = $category_id;
            }
            
            $query .= ' ORDER BY created_at DESC LIMIT ? OFFSET ?';
            $params[] = $limit;
            $params[] = $offset;
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get product by ID
     */
    public function getProductById($product_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT p.*, c.name as category_name FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ?'
            );
            $stmt->execute([$product_id]);
            
            return $stmt->fetch() ?: null;
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Search products
     */
    public function searchProducts($search_term) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT * FROM products 
                WHERE is_active = TRUE AND (name LIKE ? OR description LIKE ?)
                ORDER BY created_at DESC'
            );
            $search = '%' . $search_term . '%';
            $stmt->execute([$search, $search]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Add product (admin only)
     */
    public function addProduct($data) {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO products (category_id, name, description, price, quantity_in_stock, image_url, sku) 
                VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            
            $stmt->execute([
                $data['category_id'],
                $data['name'],
                $data['description'] ?? '',
                $data['price'],
                $data['quantity_in_stock'] ?? 0,
                $data['image_url'] ?? '',
                $data['sku'] ?? ''
            ]);
            
            return [
                'success' => true,
                'product_id' => $this->pdo->lastInsertId(),
                'message' => 'Product added successfully'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update product (admin only)
     */
    public function updateProduct($product_id, $data) {
        try {
            $allowed_fields = ['category_id', 'name', 'description', 'price', 'quantity_in_stock', 'image_url', 'sku', 'is_active'];
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
            
            $values[] = $product_id;
            $query = 'UPDATE products SET ' . implode(', ', $update_fields) . ' WHERE id = ?';
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);
            
            return ['success' => true, 'message' => 'Product updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Delete product (admin only)
     */
    public function deleteProduct($product_id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM products WHERE id = ?');
            $stmt->execute([$product_id]);
            
            return ['success' => true, 'message' => 'Product deleted successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

?>

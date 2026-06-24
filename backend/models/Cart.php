<?php
/**
 * Cart Model
 * Handle cart database operations
 */

class Cart {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Add product to cart
     */
    public function addToCart($user_id, $product_id, $quantity = 1) {
        try {
            // Check if product already in cart
            $stmt = $this->pdo->prepare(
                'SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?'
            );
            $stmt->execute([$user_id, $product_id]);
            
            if ($stmt->rowCount() > 0) {
                // Update quantity
                $item = $stmt->fetch();
                $new_quantity = $item['quantity'] + $quantity;
                
                $update_stmt = $this->pdo->prepare(
                    'UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?'
                );
                $update_stmt->execute([$new_quantity, $user_id, $product_id]);
            } else {
                // Insert new cart item
                $insert_stmt = $this->pdo->prepare(
                    'INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)'
                );
                $insert_stmt->execute([$user_id, $product_id, $quantity]);
            }
            
            return ['success' => true, 'message' => 'Item added to cart'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get user cart
     */
    public function getCart($user_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT c.*, p.name, p.price, p.image_url FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ? AND p.is_active = TRUE
                ORDER BY c.added_at DESC'
            );
            $stmt->execute([$user_id]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Update cart item quantity
     */
    public function updateQuantity($user_id, $product_id, $quantity) {
        try {
            if ($quantity <= 0) {
                return $this->removeFromCart($user_id, $product_id);
            }
            
            $stmt = $this->pdo->prepare(
                'UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?'
            );
            $stmt->execute([$quantity, $user_id, $product_id]);
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Remove item from cart
     */
    public function removeFromCart($user_id, $product_id) {
        try {
            $stmt = $this->pdo->prepare(
                'DELETE FROM cart WHERE user_id = ? AND product_id = ?'
            );
            $stmt->execute([$user_id, $product_id]);
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Clear user cart
     */
    public function clearCart($user_id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM cart WHERE user_id = ?');
            $stmt->execute([$user_id]);
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get cart total
     */
    public function getCartTotal($user_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT SUM(c.quantity * p.price) as total FROM cart c 
                JOIN products p ON c.product_id = p.id 
                WHERE c.user_id = ? AND p.is_active = TRUE'
            );
            $stmt->execute([$user_id]);
            
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}

?>

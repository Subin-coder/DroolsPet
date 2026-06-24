<?php
/**
 * Order Model
 * Handle order database operations
 */

class Order {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Create a new order
     */
    public function createOrder($user_id, $data) {
        try {
            // Generate unique order number
            $order_number = 'ORD-' . date('YmdHis') . '-' . substr(md5(uniqid()), 0, 6);
            
            $stmt = $this->pdo->prepare(
                'INSERT INTO orders (user_id, order_number, total_amount, payment_method, shipping_address) 
                VALUES (?, ?, ?, ?, ?)'
            );
            
            $stmt->execute([
                $user_id,
                $order_number,
                $data['total_amount'],
                $data['payment_method'],
                $data['shipping_address']
            ]);
            
            return [
                'success' => true,
                'order_id' => $this->pdo->lastInsertId(),
                'order_number' => $order_number
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Add order items
     */
    public function addOrderItems($order_id, $items) {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal) 
                VALUES (?, ?, ?, ?, ?)'
            );
            
            foreach ($items as $item) {
                $stmt->execute([
                    $order_id,
                    $item['product_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['subtotal']
                ]);
            }
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get order by ID
     */
    public function getOrderById($order_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT o.*, u.email, u.full_name, u.phone, u.address, u.city FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?'
            );
            $stmt->execute([$order_id]);
            
            $order = $stmt->fetch();
            
            if ($order) {
                // Get order items
                $items_stmt = $this->pdo->prepare(
                    'SELECT oi.*, p.name as product_name FROM order_items oi 
                    LEFT JOIN products p ON oi.product_id = p.id 
                    WHERE oi.order_id = ?'
                );
                $items_stmt->execute([$order_id]);
                $order['items'] = $items_stmt->fetchAll();
            }
            
            return $order ?: null;
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Get user orders
     */
    public function getUserOrders($user_id, $limit = 20, $offset = 0) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?'
            );
            $stmt->execute([$user_id, $limit, $offset]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get all orders (admin only)
     */
    public function getAllOrders($limit = 20, $offset = 0) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT o.*, u.email, u.username FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC LIMIT ? OFFSET ?'
            );
            $stmt->execute([$limit, $offset]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Update order status (admin only)
     */
    public function updateOrderStatus($order_id, $status) {
        try {
            $stmt = $this->pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
            $stmt->execute([$status, $order_id]);
            
            return ['success' => true, 'message' => 'Order status updated'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update payment status
     */
    public function updatePaymentStatus($order_id, $payment_status) {
        try {
            $stmt = $this->pdo->prepare('UPDATE orders SET payment_status = ? WHERE id = ?');
            $stmt->execute([$payment_status, $order_id]);
            
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get order count
     */
    public function getOrderCount() {
        try {
            $stmt = $this->pdo->prepare('SELECT COUNT(*) as count FROM orders');
            $stmt->execute();
            
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get total sales
     */
    public function getTotalSales() {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT SUM(total_amount) as total FROM orders WHERE payment_status = "completed"'
            );
            $stmt->execute();
            
            $result = $stmt->fetch();
            return $result['total'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
}

?>

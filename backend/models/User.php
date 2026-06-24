<?php
/**
 * User Model
 * Handle user database operations
 */

class User {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Register a new user
     */
    public function register($username, $email, $password, $full_name = '') {
        try {
            // Check if user already exists
            $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
            $stmt->execute([$email, $username]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'error' => 'User already exists'];
            }
            
            // Hash password
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            
            // Insert user
            $stmt = $this->pdo->prepare(
                'INSERT INTO users (username, email, password_hash, full_name, role) 
                VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$username, $email, $password_hash, $full_name, 'user']);
            
            $user_id = $this->pdo->lastInsertId();
            
            return [
                'success' => true,
                'user_id' => $user_id,
                'message' => 'User registered successfully'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Login user
     */
    public function login($email, $password) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, username, email, password_hash, full_name, role FROM users 
                WHERE email = ? AND is_active = TRUE'
            );
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() === 0) {
                return ['success' => false, 'error' => 'Invalid email or password'];
            }
            
            $user = $stmt->fetch();
            
            if (!password_verify($password, $user['password_hash'])) {
                return ['success' => false, 'error' => 'Invalid email or password'];
            }
            
            // Generate JWT token
            $token = JWTHandler::createToken([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'role' => $user['role']
            ]);
            
            return [
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'full_name' => $user['full_name'],
                    'role' => $user['role']
                ]
            ];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($user_id) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, username, email, full_name, phone, address, city, country, postal_code, role, created_at 
                FROM users WHERE id = ?'
            );
            $stmt->execute([$user_id]);
            
            return $stmt->fetch() ?: null;
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Update user profile
     */
    public function updateProfile($user_id, $data) {
        try {
            $allowed_fields = ['full_name', 'phone', 'address', 'city', 'country', 'postal_code'];
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
            
            $values[] = $user_id;
            $query = 'UPDATE users SET ' . implode(', ', $update_fields) . ' WHERE id = ?';
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);
            
            return ['success' => true, 'message' => 'Profile updated successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get all users (admin only)
     */
    public function getAllUsers($limit = 10, $offset = 0) {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, username, email, full_name, role, is_active, created_at 
                FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?'
            );
            $stmt->execute([$limit, $offset]);
            
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Delete user (admin only)
     */
    public function deleteUser($user_id) {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = ?');
            $stmt->execute([$user_id]);
            
            return ['success' => true, 'message' => 'User deleted successfully'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

?>

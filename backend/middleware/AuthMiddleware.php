<?php
/**
 * Authentication Middleware
 * Check and validate user authentication
 */

class AuthMiddleware {
    
    public static function authenticate() {
        $headers = getallheaders();
        $token = null;
        
        // Extract token from Authorization header
        if (isset($headers['Authorization'])) {
            preg_match('/Bearer\s+(.+)/', $headers['Authorization'], $matches);
            $token = $matches[1] ?? null;
        }
        
        if (!$token) {
            http_response_code(401);
            die(json_encode(['error' => 'Unauthorized - Missing token']));
        }
        
        $payload = JWTHandler::verifyToken($token);
        
        if (!$payload) {
            http_response_code(401);
            die(json_encode(['error' => 'Unauthorized - Invalid token']));
        }
        
        return $payload;
    }
    
    /**
     * Check if user is admin
     */
    public static function adminOnly() {
        $user = self::authenticate();
        
        if ($user['role'] !== 'admin') {
            http_response_code(403);
            die(json_encode(['error' => 'Forbidden - Admin access required']));
        }
        
        return $user;
    }
    
    /**
     * Get current user info without requiring authentication
     */
    public static function getCurrentUser() {
        $headers = getallheaders();
        $token = null;
        
        if (isset($headers['Authorization'])) {
            preg_match('/Bearer\s+(.+)/', $headers['Authorization'], $matches);
            $token = $matches[1] ?? null;
        }
        
        if (!$token) {
            return null;
        }
        
        return JWTHandler::verifyToken($token);
    }
}

?>

<?php
/**
 * JWT Token Handler
 * Handle JWT token creation and validation
 */

class JWTHandler {
    
    /**
     * Create a JWT token
     */
    public static function createToken($data) {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode(array_merge($data, ['exp' => time() + JWT_EXPIRATION]));
        
        $header = self::base64url_encode($header);
        $payload = self::base64url_encode($payload);
        
        $signature = hash_hmac(
            'sha256',
            "$header.$payload",
            JWT_SECRET,
            true
        );
        $signature = self::base64url_encode($signature);
        
        return "$header.$payload.$signature";
    }
    
    /**
     * Verify a JWT token
     */
    public static function verifyToken($token) {
        $parts = explode('.', $token);
        
        if (count($parts) !== 3) {
            return false;
        }
        
        list($header, $payload, $signature) = $parts;
        
        $expected_signature = hash_hmac(
            'sha256',
            "$header.$payload",
            JWT_SECRET,
            true
        );
        $expected_signature = self::base64url_encode($expected_signature);
        
        if (!hash_equals($signature, $expected_signature)) {
            return false;
        }
        
        $payload_decoded = json_decode(self::base64url_decode($payload), true);
        
        if ($payload_decoded['exp'] < time()) {
            return false;
        }
        
        return $payload_decoded;
    }
    
    /**
     * Decode JWT token without verification (for testing)
     */
    public static function decodeToken($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        
        $payload = $parts[1];
        return json_decode(self::base64url_decode($payload), true);
    }
    
    /**
     * Base64 URL safe encoding
     */
    private static function base64url_encode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
    
    /**
     * Base64 URL safe decoding
     */
    private static function base64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 4 - strlen($data) % 4));
    }
}

?>

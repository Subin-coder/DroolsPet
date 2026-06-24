<?php
/**
 * Input Validation Helper
 * Validate user input
 */

class Validator {
    
    private static $errors = [];
    
    /**
     * Get validation errors
     */
    public static function getErrors() {
        return self::$errors;
    }
    
    /**
     * Clear errors
     */
    public static function clearErrors() {
        self::$errors = [];
    }
    
    /**
     * Validate email
     */
    public static function validateEmail($email) {
        if (empty($email)) {
            self::$errors['email'] = 'Email is required';
            return false;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            self::$errors['email'] = 'Invalid email format';
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate username
     */
    public static function validateUsername($username) {
        if (empty($username)) {
            self::$errors['username'] = 'Username is required';
            return false;
        }
        
        if (strlen($username) < 3) {
            self::$errors['username'] = 'Username must be at least 3 characters';
            return false;
        }
        
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            self::$errors['username'] = 'Username can only contain letters, numbers, and underscores';
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate password
     */
    public static function validatePassword($password) {
        if (empty($password)) {
            self::$errors['password'] = 'Password is required';
            return false;
        }
        
        if (strlen($password) < 6) {
            self::$errors['password'] = 'Password must be at least 6 characters';
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate required field
     */
    public static function validateRequired($value, $fieldName) {
        if (empty($value)) {
            self::$errors[$fieldName] = ucfirst($fieldName) . ' is required';
            return false;
        }
        return true;
    }
    
    /**
     * Validate number
     */
    public static function validateNumber($value, $fieldName) {
        if (!is_numeric($value)) {
            self::$errors[$fieldName] = ucfirst($fieldName) . ' must be a number';
            return false;
        }
        return true;
    }
    
    /**
     * Validate positive number
     */
    public static function validatePositive($value, $fieldName) {
        if (!is_numeric($value) || $value <= 0) {
            self::$errors[$fieldName] = ucfirst($fieldName) . ' must be a positive number';
            return false;
        }
        return true;
    }
}

?>

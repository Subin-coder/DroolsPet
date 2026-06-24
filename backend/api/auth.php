<?php
/**
 * Authentication API
 * Handle user registration and login
 */

// Get request body
$input = json_decode(file_get_contents('php://input'), true);

$user_model = new User($pdo);

if ($action === 'register' && $request_method === 'POST') {
    // Validate input
    Validator::clearErrors();
    
    $is_valid = true;
    $is_valid &= Validator::validateUsername($input['username'] ?? '');
    $is_valid &= Validator::validateEmail($input['email'] ?? '');
    $is_valid &= Validator::validatePassword($input['password'] ?? '');
    
    if (!$is_valid) {
        http_response_code(400);
        die(json_encode(['error' => 'Validation failed', 'errors' => Validator::getErrors()]));
    }
    
    $result = $user_model->register(
        $input['username'],
        $input['email'],
        $input['password'],
        $input['full_name'] ?? ''
    );
    
    if ($result['success']) {
        // Log in user automatically
        $login_result = $user_model->login($input['email'], $input['password']);
        http_response_code(201);
        die(json_encode($login_result));
    } else {
        http_response_code(400);
        die(json_encode($result));
    }
}

else if ($action === 'login' && $request_method === 'POST') {
    // Validate input
    Validator::clearErrors();
    
    if (empty($input['email']) || empty($input['password'])) {
        http_response_code(400);
        die(json_encode(['error' => 'Email and password are required']));
    }
    
    $result = $user_model->login($input['email'], $input['password']);
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(401);
    }
    die(json_encode($result));
}

else if ($action === 'profile' && $request_method === 'GET') {
    // Get current user profile
    $user = AuthMiddleware::authenticate();
    
    $profile = $user_model->getUserById($user['user_id']);
    
    if ($profile) {
        http_response_code(200);
        die(json_encode(['success' => true, 'user' => $profile]));
    } else {
        http_response_code(404);
        die(json_encode(['error' => 'User not found']));
    }
}

else if ($action === 'profile' && $request_method === 'PUT') {
    // Update user profile
    $user = AuthMiddleware::authenticate();
    
    $result = $user_model->updateProfile($user['user_id'], $input);
    
    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action === 'verify-token' && $request_method === 'POST') {
    // Verify token
    $user = AuthMiddleware::authenticate();
    http_response_code(200);
    die(json_encode(['success' => true, 'user' => $user]));
}

else if ($action === 'forgot-password' && $request_method === 'POST') {
    // Request password reset
    if (empty($input['email'])) {
        http_response_code(400);
        die(json_encode(['error' => 'Email is required']));
    }

    $result = $user_model->createPasswordResetToken($input['email']);

    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action === 'reset-password' && $request_method === 'POST') {
    // Reset password with token
    if (empty($input['token']) || empty($input['password'])) {
        http_response_code(400);
        die(json_encode(['error' => 'Token and password are required']));
    }

    if (strlen($input['password']) < 6) {
        http_response_code(400);
        die(json_encode(['error' => 'Password must be at least 6 characters']));
    }

    $result = $user_model->resetPassword($input['token'], $input['password']);

    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else if ($action === 'change-password' && $request_method === 'POST') {
    // Change password for authenticated user
    $user = AuthMiddleware::authenticate();

    if (empty($input['current_password']) || empty($input['new_password'])) {
        http_response_code(400);
        die(json_encode(['error' => 'Current password and new password are required']));
    }

    if (strlen($input['new_password']) < 6) {
        http_response_code(400);
        die(json_encode(['error' => 'New password must be at least 6 characters']));
    }

    $result = $user_model->changePassword($user['user_id'], $input['current_password'], $input['new_password']);

    if ($result['success']) {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    die(json_encode($result));
}

else {
    http_response_code(404);
    die(json_encode(['error' => 'Auth endpoint not found']));
}

?>

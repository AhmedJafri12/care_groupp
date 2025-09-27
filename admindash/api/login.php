<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!verifyCSRFToken($_POST['csrf_token'] ?? null)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$identifier = trim($_POST['email'] ?? '');
$password   = (string)($_POST['password'] ?? '');
$remember   = !empty($_POST['remember_me']);

if ($identifier === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email/Username and password are required']);
    exit;
}

try {
    $pdo = db();

    // Detect available columns to support older schemas (plaintext 'password')
    $cols = $pdo->query('SHOW COLUMNS FROM users')->fetchAll(PDO::FETCH_COLUMN, 0);
    $hasEmail = in_array('email', $cols, true);
    $hasHash  = in_array('password_hash', $cols, true);
    $hasPlain = in_array('password', $cols, true);

    // Build field list dynamically based on available columns
    $fieldList = 'id, name, username, role';
    if ($hasHash)  { $fieldList .= ', password_hash'; }
    if ($hasPlain) { $fieldList .= ', password'; }

    // Try by username first
    $sqlUser = "SELECT $fieldList FROM users WHERE username = :id LIMIT 1";
    $stmt = $pdo->prepare($sqlUser);
    $stmt->execute([':id' => $identifier]);
    $user = $stmt->fetch();

    // If not found, and users.email exists, try by email
    if (!$user && $hasEmail) {
        $sqlEmail = "SELECT $fieldList FROM users WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sqlEmail);
        $stmt->execute([':email' => $identifier]);
        $user = $stmt->fetch();
    }

    if (!$user) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid credentials (user_not_found)']);
        exit;
    }
    // Prefer bcrypt hash when available
    if ($hasHash && !empty($user['password_hash'])) {
        if (!password_verify($password, $user['password_hash'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid credentials (password_mismatch)']);
            exit;
        }
    } elseif ($hasPlain && isset($user['password'])) {
        // TEMPORARY FALLBACK: support legacy plaintext password column if present
        if (!hash_equals((string)$user['password'], (string)$password)) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Invalid credentials (plaintext_mismatch)']);
            exit;
        }
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid credentials (no_password_column)']);
        exit;
    }

    // Prevent session fixation
    if (session_status() === PHP_SESSION_ACTIVE) { session_regenerate_id(true); }
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    if ($remember) {
        // Simple remember cookie (non-secure demo). For production, implement secure token logic.
        setcookie('remember_user', (string)$user['id'], time() + 60*60*24*30, '/');
    }

    echo json_encode(['success' => true, 'message' => 'Login successful']);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

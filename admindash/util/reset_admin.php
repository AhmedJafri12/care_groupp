<?php
// Temporary utility to reset a user's password using PHP's password_hash.
// Usage (browser):
//   http://localhost/eproject/util/reset_admin.php?secret=dev-allow-1&username=admin&password=admin123
// IMPORTANT: Delete this file after use.

require_once __DIR__ . '/../db.php';

$SECRET = 'dev-allow-1'; // change if needed
if (($_GET['secret'] ?? '') !== $SECRET) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

$username = trim($_GET['username'] ?? 'admin');
$password = (string)($_GET['password'] ?? 'admin123');

if ($username === '' || $password === '') {
    http_response_code(400);
    echo 'Missing username or password';
    exit;
}

try {
    $pdo = db();
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET password_hash = :h WHERE username = :u OR email = :u LIMIT 1');
    $stmt->execute([':h' => $hash, ':u' => $username]);
    if ($stmt->rowCount() > 0) {
        echo 'Password updated for ' . htmlspecialchars($username);
    } else {
        echo 'No matching user found for ' . htmlspecialchars($username);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error resetting password';
}

<?php
// Basic auth helpers
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function redirect(string $path): void {
    header('Location: ' . $path);
    exit;
}

function generateCSRFToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken(?string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}

function require_login(): void {
    // Bypass login if explicitly disabled in config
    if (defined('AUTH_DISABLED') && AUTH_DISABLED) {
        return;
    }
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

<?php
// Patient UI bootstrap: session + minimal auth helpers
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require shared DB connection (config/db.php)
require_once __DIR__ . '/../../config/db.php';

// --- DB-backed user operations (care_group.patients) ---
function pui_register_user(string $name, string $email, string $contact, string $password): array {
    $pdo = db();
    // Check duplicate email
    $stmt = $pdo->prepare('SELECT id FROM patients WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new RuntimeException('An account with this email already exists.');
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $pdo->prepare('INSERT INTO patients (name, email, contact_phone, password_hash) VALUES (?,?,?,?)');
    $ins->execute([$name, $email, $contact, $hash]);
    $id = (int)$pdo->lastInsertId();
    return [
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'contact' => $contact,
    ];
}

function pui_authenticate(string $email, string $password): ?array {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, name, email, contact_phone, password_hash FROM patients WHERE email = ?');
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if ($u && password_verify($password, $u['password_hash'])) {
        return [
            'id' => (int)$u['id'],
            'name' => $u['name'],
            'email' => $u['email'],
            'contact' => $u['contact_phone'] ?? ''
        ];
    }
    return null;
}

function pui_is_logged_in(): bool {
    return !empty($_SESSION['pui_user']);
}

function pui_require_login(): void {
    if (!pui_is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// Backward-compatible helper used by earlier pages
function pui_login(string $identifier): void {
    $_SESSION['pui_user'] = [
        'name' => $identifier,
        'email' => strpos($identifier, '@') !== false ? $identifier : ($identifier . '@example.com'),
    ];
}

function pui_logout(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function pui_user_name(): string { return $_SESSION['pui_user']['name'] ?? 'Patient'; }
function pui_user_email(): string { return $_SESSION['pui_user']['email'] ?? ''; }
function pui_user_contact(): string { return $_SESSION['pui_user']['contact'] ?? ''; }


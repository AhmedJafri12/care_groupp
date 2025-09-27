<?php
// Single, simple endpoint for AdminDash
// Usage: POST to api.php?action=<action>
// Returns JSON
header('Content-Type: application/json');
session_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
$pdo = db();

// Allow temporary bypass of auth/CSRF when AUTH_DISABLED is enabled
$authBypass = defined('AUTH_DISABLED') && AUTH_DISABLED;

// All actions here mutate state; enforce auth + POST + CSRF
if (!$authBypass && !isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
if (!$authBypass && !verifyCSRFToken($_POST['csrf_token'] ?? null)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
    exit;
}

$action = $_REQUEST['action'] ?? '';

function ok($msg = 'OK', $extra = []) { echo json_encode(['success' => true, 'message' => $msg] + $extra); exit; }
function err($msg = 'Error', $code = 400) { http_response_code($code); echo json_encode(['success' => false, 'message' => $msg]); exit; }

try {
    switch ($action) {
        // Cities
        case 'city.add': {
            $name = trim($_POST['name'] ?? '');
            if ($name === '') err('City name required');
            $stmt = $pdo->prepare('INSERT INTO cities(name) VALUES(?)');
            $stmt->execute([$name]);
            ok('City added');
        }
        case 'city.delete': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) err('City id required');
            $pdo->prepare('DELETE FROM cities WHERE id = ?')->execute([$id]);
            ok('City deleted');
        }

        // Doctors
        case 'doctor.add': {
            $name = trim($_POST['name'] ?? '');
            $spec = trim($_POST['specialization'] ?? '');
            if ($name === '' || $spec === '') err('Name and specialization required');
            $pdo->prepare('INSERT INTO doctors(name, specialization, email, phone, status) VALUES(?,?,?,?,"Active")')
                ->execute([$name, $spec, $_POST['email'] ?? null, $_POST['phone'] ?? null]);
            ok('Doctor added');
        }
        case 'doctor.update': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) err('Doctor id required');
            $name = trim($_POST['name'] ?? '');
            $spec = trim($_POST['specialization'] ?? '');
            $email = $_POST['email'] ?? null; $phone = $_POST['phone'] ?? null; $status = $_POST['status'] ?? 'Active';
            $pdo->prepare('UPDATE doctors SET name=?, specialization=?, email=?, phone=?, status=? WHERE id=?')
                ->execute([$name, $spec, $email, $phone, $status, $id]);
            ok('Doctor updated');
        }
        case 'doctor.delete': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) err('Doctor id required');
            $pdo->prepare('DELETE FROM doctors WHERE id = ?')->execute([$id]);
            ok('Doctor deleted');
        }

        // Patients
        case 'patient.update': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) err('Patient id required');
            $name = trim($_POST['name'] ?? '');
            $email = $_POST['email'] ?? null; $phone = $_POST['phone'] ?? null; $city_id = (int)($_POST['city_id'] ?? 0);
            $pdo->prepare('UPDATE patients SET name=?, email=?, phone=?, city_id=? WHERE id=?')
                ->execute([$name, $email, $phone, $city_id ?: null, $id]);
            ok('Patient updated');
        }
        case 'patient.delete': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) err('Patient id required');
            $pdo->prepare('DELETE FROM patients WHERE id = ?')->execute([$id]);
            ok('Patient deleted');
        }

        // Users (logins)
        case 'user.add': {
            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'Administrator';
            if ($name === '' || $username === '' || $password === '') err('All fields required');
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $pdo->prepare('INSERT INTO users(name, username, password_hash, role) VALUES(?,?,?,?)')
                ->execute([$name, $username, $hash, $role]);
            ok('User created');
        }
        case 'user.delete': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) err('User id required');
            $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
            ok('User deleted');
        }

        // Website info
        case 'content.add': {
            $type = $_POST['type'] ?? '';
            $title = trim($_POST['title'] ?? '');
            $summary = trim($_POST['summary'] ?? '');
            $details = trim($_POST['details'] ?? '');
            if ($type === '' || $title === '' || $summary === '' || $details === '') err('All fields required');
            $pdo->prepare('INSERT INTO website_content(type,title,summary,details) VALUES(?,?,?,?)')
                ->execute([$type, $title, $summary, $details]);
            ok('Content added');
        }
        case 'content.delete': {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) err('Content id required');
            $pdo->prepare('DELETE FROM website_content WHERE id = ?')->execute([$id]);
            ok('Content deleted');
        }

        default:
            err('Unknown or missing action', 404);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

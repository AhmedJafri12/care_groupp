<?php
require_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../db.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$spec = trim($_POST['specialization'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($name === '' || $spec === '') {
    header('Location: form.php?error=Please+fill+in+required+fields');
    exit;
}

try {
    $pdo = db();
    $stmt = $pdo->prepare('INSERT INTO doctors(name, specialization, email, phone, status) VALUES(?,?,?,?,"Active")');
    $stmt->execute([$name, $spec, $email ?: null, $phone ?: null]);
    header('Location: ../index.php?msg=Doctor+added');
    exit;
} catch (Throwable $e) {
    header('Location: form.php?error=Server+error');
    exit;
}

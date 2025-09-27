<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../auth.php';

// Stub implementation: always return success without revealing if email exists
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['success' => false, 'message' => 'Method not allowed']);
  exit;
}

// Accept 'email' and pretend to send a reset link
$email = trim($_POST['email'] ?? '');
if ($email === '') {
  http_response_code(400);
  echo json_encode(['success' => false, 'message' => 'Email is required']);
  exit;
}

// In a real implementation, generate a token, store it, and email a link.
echo json_encode(['success' => true, 'message' => 'If your email exists in our system, a reset link has been sent.']);

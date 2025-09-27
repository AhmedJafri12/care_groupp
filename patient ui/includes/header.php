<?php
// Patient UI shared header and tiny auth helpers (frontend-only)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Patient UI'; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <style>
    :root{--primary:#4361ee;--light:#f4f6fc;--dark:#2c3e50;--white:#fff;--muted:#6c757d;--radius:12px;--shadow:0 10px 25px rgba(0,0,0,.08)}
    body{font-family:'Segoe UI',Tahoma,Verdana,sans-serif;background:var(--light);color:#333;margin:0}
    .container{max-width:960px;margin:0 auto;padding:24px}
    .btn{display:inline-block;border:none;border-radius:8px;padding:10px 16px;background:var(--primary);color:#fff;cursor:pointer}
    .btn.secondary{background:#6c757d}
    .card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:20px}
    a{color:var(--primary);text-decoration:none}

    /* Dashboard layout */
    .dashboard{display:flex;min-height:100vh}
    .sidebar{background:var(--dark);color:#fff;width:250px;padding:24px 16px;flex-shrink:0}
    .sidebar h2{font-size:20px;margin:0 0 16px}
    .sidebar ul{list-style:none;padding:0;margin:0}
    .sidebar li{margin:12px 0}
    .sidebar a{color:#ecf0f1}
    .sidebar a:hover{color:#1abc9c}
    .main-content{flex:1;padding:24px}
    .header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;margin-bottom:20px}
    .cards{display:flex;flex-wrap:wrap;gap:16px}
    .cards .card{flex:1 1 240px}
    @media (max-width:768px){.dashboard{flex-direction:column}.sidebar{width:100%;text-align:center}}
  </style>
</head>
<body>

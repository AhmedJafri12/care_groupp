<?php
// Shared header for Doctor Dashboard
require_once __DIR__ . '/bootstrap.php';
$dd_title = isset($page_title) ? $page_title . ' | Doctor Dashboard' : 'Doctor Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo htmlspecialchars($dd_title); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
  <style>
    :root{--primary:#0d3b66;--light:#f4f6fc;--dark:#2c3e50;--radius:12px;--shadow:0 10px 25px rgba(0,0,0,.08)}
    body{font-family: Arial, sans-serif;background:var(--light);color:#333;margin:0}
    a{color:#0d3b66;text-decoration:none}
    .dd-container{display:flex;min-height:100vh}
    .dd-sidebar{background:var(--dark);color:#fff;width:250px;padding:24px 16px;flex-shrink:0}
    .dd-sidebar h2{font-size:20px;margin:0 0 16px}
    .dd-sidebar ul{list-style:none;padding:0;margin:0}
    .dd-sidebar li{margin:12px 0}
    .dd-sidebar a{color:#ecf0f1;display:block;padding:8px 10px;border-radius:8px}
    .dd-sidebar a.active,.dd-sidebar a:hover{background:#1f2d3a;color:#1abc9c}
    .dd-main{flex:1;padding:24px}
    .dd-card{background:#fff;border-radius:var(--radius);box-shadow:var(--shadow);padding:20px}
    .dd-cards{display:flex;flex-wrap:wrap;gap:16px}
    .dd-cards .dd-card{flex:1 1 240px}
    .form-control, .form-select{border-radius:8px}
    .btn-primary{background:#0d3b66;border-color:#0d3b66}
    .btn-primary:hover{background:#15599f;border-color:#15599f}
    @media (max-width: 768px){.dd-container{flex-direction:column}.dd-sidebar{width:100%}}
  </style>
</head>
<body>

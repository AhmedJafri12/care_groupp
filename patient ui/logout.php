<?php
require_once __DIR__ . '/includes/bootstrap.php';
pui_logout();
header('Location: login.php');
exit;
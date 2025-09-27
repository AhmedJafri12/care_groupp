<?php
require_once __DIR__ . '/includes/bootstrap.php';
dd_logout();
header('Location: login.php');
exit;

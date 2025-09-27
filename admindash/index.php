<?php
require_once __DIR__ . '/auth.php';
require_login();
// Serve the PHP-wrapped dashboard so session-driven UI (name/role/logout) is applied
require __DIR__ . '/admindash.php';

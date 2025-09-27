<?php
// Global DB connection helper for the entire site
// Update these credentials for your environment
const CARE_DB_DSN = 'mysql:host=127.0.0.1;dbname=care_group;charset=utf8mb4';
const CARE_DB_USER = 'root';
const CARE_DB_PASS = '';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO(CARE_DB_DSN, CARE_DB_USER, CARE_DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}

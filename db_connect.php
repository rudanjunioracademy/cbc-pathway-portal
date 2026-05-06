<?php
/**
 * Database Connection for Rudan CBC System
 * Database: rudan_cbc_system
 */

$host = 'localhost';
$db   = 'rudan_cbc_system';
$user = 'root';
$pass = 'root'; // Default WAMP password is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throws errors as exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetches data as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Uses real prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In a real environment, you'd log this and show a user-friendly message
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
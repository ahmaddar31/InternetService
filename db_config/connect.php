<?php
// Database connection settings
$host = 'localhost';  // Usually 'localhost' if running locally
$db = 'InternetService';  // Replace with your database name
$user = 'postgres';  // Replace with your database username
$pass = 'admin';  // Replace with your database password
$port = '5432';  // Default PostgreSQL port

// DSN (Data Source Name)
$dsn = "pgsql:host=$host;port=$port;dbname=$db;";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Enable exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Set fetch mode to associative array
        PDO::ATTR_EMULATE_PREPARES => false,  // Disable emulated prepared statements
    ]);
} catch (PDOException $e) {
    // If connection fails, display an error message
    echo "Error connecting to PostgreSQL database: " . $e->getMessage();
    exit();
}
?>

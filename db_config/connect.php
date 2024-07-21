<?php
// Database connection settings
$host = 'internetservice.c9wqqwm4ciqb.us-east-1.rds.amazonaws.com';  // Corrected hostname
$db = 'InternetService';  // Replace with your database name
$user = 'postgres';  // Replace with your database username
$pass = 'Vklr3VDe0ALZxyn0dGFf';  // Replace with your database password
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
    echo "Connected to the PostgreSQL database successfully!";
} catch (PDOException $e) {
    // If connection fails, display an error message
    echo "Error connecting to PostgreSQL database: " . $e->getMessage();
    exit();
}
?>

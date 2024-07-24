<?php
// Database connection settings
$host = 'c9mq4861d16jlm.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com';  // Corrected hostname
$db = 'd35md6qje9pcf2';  // Replace with your database name
$user = 'uc40obp5cdmp7r';  // Replace with your database username
$pass = 'p81fe3cd0ec3b7f0cd85824e36eb511cb21b347717f55694988f6ebb5ad3c2ec8';  // Replace with your database password
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

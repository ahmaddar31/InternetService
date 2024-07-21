<?php
// Get the Heroku database URL
$dbUrl = getenv('DATABASE_URL');

// Parse the URL to get the connection details
$db = parse_url($dbUrl);

$host = $db['host'];
$port = $db['port'];
$user = $db['user'];
$pass = $db['pass'];
$dbname = ltrim($db['path'], '/');

// DSN (Data Source Name)
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";

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

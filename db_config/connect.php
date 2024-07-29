<?php
// Path to the JSON file
$jsonFilePath = __DIR__ . '/db_credentials.json';


// Read and decode the JSON file
if (!file_exists($jsonFilePath)) {
    die("JSON file not found");
}

$jsonData = file_get_contents($jsonFilePath);
if ($jsonData === false) {
    die("Error reading JSON file");
}

$credentials = json_decode($jsonData, true);
if ($credentials === null) {
    die("Error decoding JSON file");
}

// Database connection settings
$host = $credentials['host'];
$db = $credentials['db'];
$user = $credentials['user'];
$pass = $credentials['pass'];
$port = $credentials['port'];

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


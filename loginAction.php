<?php
session_start();
include('./db_config/connect.php');

$user = $_POST['user'];
$pass = $_POST['Password'];

try {
    // Prepare the SQL query with correct column names and case sensitivity
    $query = 'SELECT * FROM public."admin" WHERE "username" = :user AND "Password" = :password';
    $stmt = $pdo->prepare($query);

    // Bind parameters
    $stmt->bindParam(':user', $user);
    $stmt->bindParam(':password', $pass);

    // Execute the query
    $stmt->execute();

    // Check if any rows were returned
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['userlog_info'] = $row;
        header('Location: index.php');
    } else {
        header('Location: login.php?flag=1');
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

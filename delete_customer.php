<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['userlog_info'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];

    try {
        // Delete the customer from the database
        $query = "DELETE FROM customer WHERE c_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
        $stmt->execute();

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: index.php');
}
?>

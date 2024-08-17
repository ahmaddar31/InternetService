<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['userlog_info'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $client_id = $_POST['client_id']; // The custom client ID entered by the user
    $c_name = $_POST['c_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $bundle = $_POST['bundle'];
    $bundle_price = $_POST['bundle_price'];

    try {
        // Update the customer record in the database
        $query = "UPDATE customer SET c_id = :c_id, c_name = :c_name, address = :address, phone = :phone, bundle = :bundle, bundle_price = :bundle_price WHERE c_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':c_id', $client_id); // Bind the custom client ID
        $stmt->bindParam(':c_name', $c_name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':bundle', $bundle);
        $stmt->bindParam(':bundle_price', $bundle_price);
        $stmt->bindParam(':id', $customer_id); // Bind the original customer ID to identify the record
        $stmt->execute();

        // Redirect back to the main page after successful update
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: index.php');
    exit();
}
?>

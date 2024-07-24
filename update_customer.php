<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['userlog_info'])) {
    header('Location: login.php');
    exit();
}

$customer_id = $_POST['customer_id'];
$c_name = $_POST['c_name'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$bundle = $_POST['bundle'];
$bundle_price = $_POST['bundle_price'];

try {
    $query = "UPDATE customer SET c_name = :c_name, address = :address, phone = :phone, bundle = :bundle, bundle_price = :bundle_price WHERE c_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':c_name', $c_name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':bundle', $bundle);
    $stmt->bindParam(':bundle_price', $bundle_price);
    $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: index.php');
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

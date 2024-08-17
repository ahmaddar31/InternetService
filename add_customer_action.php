<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['userlog_info'])) {
    header('Location: login.php');
    exit();
}

// Get the admin ID from the session
$admin_id = $_SESSION['userlog_info']['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_POST['client_id']; // Retrieve the client ID
    $c_name = $_POST['c_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $bundle = $_POST['bundle'];
    $bundle_price = $_POST['bundle_price'];

    $paid = 'N';
    $last_paid_date = date('Y-m-d H:i:s');

    $query = "INSERT INTO customer (c_id, a_id, c_name, address, phone, bundle, bundle_price, paid, last_paid_date) 
              VALUES (:c_id, :a_id, :c_name, :address, :phone, :bundle, :bundle_price, :paid, :last_paid_date)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':c_id', $client_id); // Bind the client ID
    $stmt->bindParam(':a_id', $admin_id);
    $stmt->bindParam(':c_name', $c_name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':bundle', $bundle);
    $stmt->bindParam(':bundle_price', $bundle_price);
    $stmt->bindParam(':paid', $paid);
    $stmt->bindParam(':last_paid_date', $last_paid_date);

    if ($stmt->execute()) {
        header('Location: index.php');
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
} else {
    header('Location: index.php');
}
?>

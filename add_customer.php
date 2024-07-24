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
    $c_name = $_POST['c_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $bundle = $_POST['bundle'];
    $bundle_price = 0;

    // Calculate bundle price based on selected bundle
    switch ($bundle) {
        case '8 MB':
            $bundle_price = 25;
            break;
        case '10 MB':
            $bundle_price = 30;
            break;
        case '12 MB':
            $bundle_price = 35;
            break;
        case '14 MB':
            $bundle_price = 40;
            break;
        case '16 MB':
            $bundle_price = 45;
            break;
        case '20 MB':
            $bundle_price = 55;
            break;
        case 'gaming1':
            $bundle_price = 35;
            break;
        case 'gaming2':
            $bundle_price = 40;
            break;
        case 'gaming3':
            $bundle_price = 50;
            break;
        default:
            // Handle invalid bundle option if necessary
            header('Location: index.php?flag=1');
            exit();
    }

    $paid = 'N';
    $last_paid_date = date('Y-m-d H:i:s');

    $query = "INSERT INTO customer (a_id, c_name, address, phone, bundle, bundle_price, paid, last_paid_date) 
              VALUES (:a_id, :c_name, :address, :phone, :bundle, :bundle_price, :paid, :last_paid_date)";
    $stmt = $pdo->prepare($query);
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

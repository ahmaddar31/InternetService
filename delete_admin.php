<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['manager_info'])) {
    header('Location: manager_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = $_POST['admin_id'];

    try {
        $query = "DELETE FROM admin WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $admin_id, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: manage_admins.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: manage_admins.php');
    exit();
}
?>

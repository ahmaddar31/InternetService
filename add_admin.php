<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['manager_info'])) {
    header('Location: manager_login.php');
    exit();
}

$manager_id = $_SESSION['manager_info']['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $bundle_names = $_POST['bundle_names']; // Get bundle names

    try {
        $query = "INSERT INTO admin (m_id, name, username, password, bundle_names) VALUES (:m_id, :name, :username, :password, :bundle_names)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':m_id', $manager_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':bundle_names', $bundle_names, PDO::PARAM_STR); // Bind bundle names
        $stmt->execute();
        header('Location: manage_admins.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Style1.css">
    <title>Add Admin - CoDelta Technologies</title>
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Roboto', sans-serif;
        }
        .navbar {
            background-color: #007bff;
            color: white;
        }
        .navbar-brand {
            font-weight: bold;
            color: white;
        }
        .container {
            margin-top: 50px;
            max-width: 600px;
        }
        .card {
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 5px;
        }
        .btn {
            border-radius: 5px;
            font-size: 16px;
            padding: 10px 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #bd2130;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">CoDelta Technologies</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h2 class="text-center">Add Admin</h2>
            <form action="add_admin.php" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="bundle_names">Bundle Names</label>
                    <input type="text" class="form-control" id="bundle_names" name="bundle_names" placeholder="Enter bundle names separated by commas" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Add Admin</button>
                    <a href="manage_admins.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="footer mt-4">
        <p>&copy; 2024 CoDelta Technologies. All rights reserved.</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


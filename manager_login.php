<?php
session_start();
include('./db_config/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $query = "SELECT * FROM manager WHERE username = :username AND password = :password";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $manager = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($manager) {
            $_SESSION['manager_info'] = $manager;
            header('Location: manage_admins.php');
            exit();
        } else {
            header('Location: manager_login.php?flag=1'); // Invalid credentials
            exit();
        }
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
    <link rel="stylesheet" href="./bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Style1.css">
    <title>Manager Login - CoDelta Technologies</title>
</head>
<body>
    <div class="container mt-5">
        <div class="card" style="width: 25rem; margin: 0 auto;">
            <div class="card-header text-center bg-dark text-white">Manager Login</div>
            <div class="card-body">
                <?php
                if (isset($_GET['flag']) && $_GET['flag'] == 1) {
                    echo "<div class='alert alert-danger text-center'>Invalid username or password!</div>";
                }
                ?>
                <form method="post" action="manager_login.php">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mt-4">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

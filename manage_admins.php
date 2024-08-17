<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['manager_info'])) {
    header('Location: manager_login.php');
    exit();
}

$manager_id = $_SESSION['manager_info']['id'];

// Fetch admins for this manager
$admins = [];
try {
    $query = "SELECT * FROM admin WHERE m_id = :manager_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':manager_id', $manager_id, PDO::PARAM_INT);
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Manage Admins - CoDelta Technologies</title>
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
            margin-top: 30px;
        }

        .table-responsive {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary, .btn-danger, .btn-warning {
            margin-right: 5px;
        }

        .header-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-info h2 {
            font-size: 24px;
            font-weight: bold;
        }

        .header-info p {
            font-size: 18px;
            color: #666;
        }

        .btn {
            font-size: 14px;
            padding: 10px 15px;
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
                    <a class="nav-link" href="logout.php" style="color:#f4f7f6">Logout <i class="fas fa-sign-out-alt"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="header-info">
            <h2>Manage Admins</h2>
            <p>Manage your admins efficiently and effectively</p>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="add_admin.php" class="btn btn-success"><i class="fas fa-plus"></i> Add Admin</a>
        </div>
        
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['name']); ?></td>
                            <td><?php echo htmlspecialchars($admin['username']); ?></td>
                            <td><?php echo htmlspecialchars($admin['password']); ?></td>
                            <td>
                                <a href="edit_admin.php?id=<?php echo $admin['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                <form action="delete_admin.php" method="post" style="display:inline;">
                                    <input type="hidden" name="admin_id" value="<?php echo $admin['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="footer mt-4">
        <p>&copy; 2024 CoDelta Technologies. All rights reserved.</p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

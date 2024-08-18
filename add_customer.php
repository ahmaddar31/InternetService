
<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['userlog_info'])) {
    header('Location: login.php');
    exit();
}

$admin_id = $_SESSION['userlog_info']['id'];

// Fetch the bundle names for the logged-in admin
$query = "SELECT bundle_names FROM admin WHERE id = :admin_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

$bundle_names = explode(',', $admin['bundle_names']); // Convert the comma-separated string to an array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Add New Customer</title>
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
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary, .btn-danger {
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

        .form-control {
            margin-bottom: 20px;
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
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout <i class="fas fa-sign-out-alt"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="header-info">
            <h2>Add New Customer</h2>
            <p>Fill in the details below to add a new customer</p>
        </div>

        <form action="./add_customer_action.php" method="post" onsubmit="return validatePhoneNumber();">
            <div class="form-group">
                <label for="client_id">Client ID</label>
                <input type="text" class="form-control" id="client_id" name="client_id" required>
            </div>
            <div class="form-group">
                <label for="c_name">Name</label>
                <input type="text" class="form-control" id="c_name" name="c_name" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="bundle">Bundle</label>
                <div>
                    <?php foreach ($bundle_names as $bundle): ?>
                        <input type="radio" id="bundle_<?php echo htmlspecialchars(trim($bundle)); ?>" name="bundle" value="<?php echo htmlspecialchars(trim($bundle)); ?>" required>
                        <label for="bundle_<?php echo htmlspecialchars(trim($bundle)); ?>"><?php echo htmlspecialchars(trim($bundle)); ?></label><br>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-group">
                <label for="bundle_price">Bundle Price</label>
                <input type="number" class="form-control" id="bundle_price" name="bundle_price" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add Customer</button>
            <a href="index.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function validatePhoneNumber() {
            const phoneInput = document.getElementById('phone').value;
            const phonePattern = /^[0-9]{8}$/;

            if (!phonePattern.test(phoneInput)) {
                alert('Phone number must be exactly 8 digits and contain only numbers.');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>

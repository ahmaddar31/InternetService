<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['userlog_info'])) {
    header('Location: login.php');
    exit();
}

$customer_id = $_GET['id'];

try {
    $query = "SELECT * FROM customer WHERE c_id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$customer) {
        header('Location: index.php?flag=1'); // Redirect if customer not found
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Edit Customer</title>
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
            <h2>Edit Customer</h2>
            <p>Modify the details of the customer</p>
        </div>

        <form action="update_customer.php" method="post" onsubmit="return validatePhoneNumber();">
            <input type="hidden" name="customer_id" value="<?php echo $customer['c_id']; ?>">
            <div class="form-group">
                <label for="client_id">Client ID</label>
                <input type="text" class="form-control" id="client_id" name="client_id" value="<?php echo htmlspecialchars($customer['c_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="c_name">Name</label>
                <input type="text" class="form-control" id="c_name" name="c_name" value="<?php echo htmlspecialchars($customer['c_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($customer['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="bundle">Bundle</label>
                <div>
                    <input type="radio" id="bundle_8mb" name="bundle" value="8 MB" <?php echo $customer['bundle'] == '8 MB' ? 'checked' : ''; ?> required>
                    <label for="bundle_8mb">8 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_10mb" name="bundle" value="10 MB" <?php echo $customer['bundle'] == '10 MB' ? 'checked' : ''; ?> required>
                    <label for="bundle_10mb">10 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_12mb" name="bundle" value="12 MB" <?php echo $customer['bundle'] == '12 MB' ? 'checked' : ''; ?> required>
                    <label for="bundle_12mb">12 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_14mb" name="bundle" value="14 MB" <?php echo $customer['bundle'] == '14 MB' ? 'checked' : ''; ?> required>
                    <label for="bundle_14mb">14 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_16mb" name="bundle" value="16 MB" <?php echo $customer['bundle'] == '16 MB' ? 'checked' : ''; ?> required>
                    <label for="bundle_16mb">16 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_20mb" name="bundle" value="20 MB" <?php echo $customer['bundle'] == '20 MB' ? 'checked' : ''; ?> required>
                    <label for="bundle_20mb">20 MB</label>
                </div>
            </div>
            <div class="form-group">
                <label for="bundle_price">Bundle Price</label>
                <input type="number" class="form-control" id="bundle_price" name="bundle_price" value="<?php echo htmlspecialchars($customer['bundle_price']); ?>" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
            <a href="index.php" class="btn btn-danger">Cancel</a>
        </form>
    </div>

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

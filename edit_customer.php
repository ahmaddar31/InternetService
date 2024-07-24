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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap-4.6.2-dist/css/bootstrap.min.css">
    <title>Edit Customer</title>
</head>
<body>
    <div class="container mt-5">
        <h3>Edit Customer</h3>
        <form action="update_customer.php" method="post">
            <input type="hidden" name="customer_id" value="<?php echo $customer['c_id']; ?>">
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
                <div>
                    <input type="radio" id="bundle_gaming1" name="bundle" value="gaming1" <?php echo $customer['bundle'] == 'gaming1' ? 'checked' : ''; ?> required>
                    <label for="bundle_gaming1">Gaming 1</label>
                </div>
                <div>
                    <input type="radio" id="bundle_gaming2" name="bundle" value="gaming2" <?php echo $customer['bundle'] == 'gaming2' ? 'checked' : ''; ?> required>
                    <label for="bundle_gaming2">Gaming 2</label>
                </div>
                <div>
                    <input type="radio" id="bundle_gaming3" name="bundle" value="gaming3" <?php echo $customer['bundle'] == 'gaming3' ? 'checked' : ''; ?> required>
                    <label for="bundle_gaming3">Gaming 3</label>
                </div>
            </div>
            <div class="form-group">
                <label for="bundle_price">Bundle Price</label>
                <input type="number" class="form-control" id="bundle_price" name="bundle_price" value="<?php echo htmlspecialchars($customer['bundle_price']); ?>" step="0.01" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="bundle"]');
            const bundlePriceInput = document.getElementById('bundle_price');

            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    let price = 0;

                    if (document.getElementById('bundle_8mb').checked) {
                        price = 25;
                    } else if (document.getElementById('bundle_10mb').checked) {
                        price = 30;
                    } else if (document.getElementById('bundle_12mb').checked) {
                        price = 35;
                    } else if (document.getElementById('bundle_14mb').checked) {
                        price = 40;
                    } else if (document.getElementById('bundle_16mb').checked) {
                        price = 45;
                    } else if (document.getElementById('bundle_20mb').checked) {
                        price = 55;
                    } else if (document.getElementById('bundle_gaming1').checked) {
                        price = 35;
                    } else if (document.getElementById('bundle_gaming2').checked) {
                        price = 40;
                    } else if (document.getElementById('bundle_gaming3').checked) {
                        price = 50;
                    }

                    bundlePriceInput.value = price;
                });
            });
        });
    </script>
</body>
</html>

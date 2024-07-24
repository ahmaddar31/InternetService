<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['userlog_info'])) {
    header('Location: login.php');
    exit();
}

// Initialize the $customers variable
$customers = [];
$admin_id = $_SESSION['userlog_info']['id'];
$search = '';

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Initialize the total amount for customers with paid status 'Y'
$total_amount_paid = 0;

try {
    // Fetch customer data for the logged-in admin
    $query = "SELECT * FROM customer WHERE a_id = :admin_id AND c_name ILIKE :search";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $search_term = '%' . $search . '%';
    $stmt->bindParam(':search', $search_term, PDO::PARAM_STR);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total amount for customers with paid status 'Y'
    foreach ($customers as $customer) {
        if ($customer['paid'] == 'Y') {
            $total_amount_paid += $customer['bundle_price'];
        }
    }

    // Count total number of customers
    $total_customers = count($customers);
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
    <title>Customer Table</title>
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .form-control, .btn {
            width: 100%;
        }
        @media (min-width: 768px) {
            .form-control, .btn {
                width: auto;
            }
        }
        .paid-yes {
            color: blue;
        }
        .paid-no {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Customer Table</h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        
        <form method="get" action="index.php" class="form-inline my-4">
            <input class="form-control mr-sm-2" type="search" placeholder="Search by name" aria-label="Search" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>name</th>
                        <th>address</th>
                        <th>phone</th>
                        <th>bundle</th>
                        <th>bundle price</th>
                        <th>paid</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td class="<?php echo $customer['paid'] == 'Y' ? 'paid-yes' : 'paid-no'; ?>">
                                <?php echo htmlspecialchars($customer['c_name']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($customer['address']); ?></td>
                            <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                            <td><?php echo htmlspecialchars($customer['bundle']); ?></td>
                            <td><?php echo htmlspecialchars($customer['bundle_price']); ?></td>
                            <td>
                                <form action="update_paid_status.php" method="post" style="display:inline;">
                                    <input type="hidden" name="customer_id" value="<?php echo $customer['c_id']; ?>">
                                    <select name="paid" class="form-control" onchange="this.form.submit()">
                                        <option value="N" <?php echo $customer['paid'] == 'N' ? 'selected' : ''; ?>>N</option>
                                        <option value="Y" <?php echo $customer['paid'] == 'Y' ? 'selected' : ''; ?>>Y</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form action="delete_customer.php" method="post" style="display:inline;">
                                    <input type="hidden" name="customer_id" value="<?php echo $customer['c_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                <a href="edit_customer.php?id=<?php echo $customer['c_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div>
            <p>Total Customers: <?php echo $total_customers; ?></p>
            <p>Total Amount Paid: $<?php echo $total_amount_paid; ?></p>
        </div>

        <?php
            if (isset($_GET['flag'])) {
                if ($_GET['flag'] == 1) {
                    echo "<b>Enter correct data</b>";
                }
            }
        ?>

        <h3 class="mt-5">Add New Customer</h3>
        <form action="./add_customer.php" method="post">
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
                    <input type="radio" id="bundle_8mb" name="bundle" value="8 MB" required>
                    <label for="bundle_8mb">8 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_10mb" name="bundle" value="10 MB" required>
                    <label for="bundle_10mb">10 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_12mb" name="bundle" value="12 MB" required>
                    <label for="bundle_12mb">12 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_14mb" name="bundle" value="14 MB" required>
                    <label for="bundle_14mb">14 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_16mb" name="bundle" value="16 MB" required>
                    <label for="bundle_16mb">16 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_20mb" name="bundle" value="20 MB" required>
                    <label for="bundle_20mb">20 MB</label>
                </div>
                <div>
                    <input type="radio" id="bundle_gaming1" name="bundle" value="gaming1" required>
                    <label for="bundle_gaming1">Gaming 1</label>
                </div>
                <div>
                    <input type="radio" id="bundle_gaming2" name="bundle" value="gaming2" required>
                    <label for="bundle_gaming2">Gaming 2</label>
                </div>
                <div>
                    <input type="radio" id="bundle_gaming3" name="bundle" value="gaming3" required>
                    <label for="bundle_gaming3">Gaming 3</label>
                </div>
            </div>
            <div class="form-group">
                <label for="bundle_price">Bundle Price</label>
                <input type="number" class="form-control" id="bundle_price" name="bundle_price" step="0.01" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Add Customer</button>
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

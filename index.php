<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['userlog_info'])) {
    header('Location: login.php');
    exit();
}

// Initialize variables
$customers = [];
$admin_id = $_SESSION['userlog_info']['id'];
$search = '';
$total_customers_paid = 0;
$total_customers_unpaid = 0;
$total_amount_paid = 0;

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

try {
    // On the 1st of each month, reset all paid customers to unpaid (N)
    $current_date = new DateTime();
    $first_day_of_month = new DateTime('first day of this month');
    $first_day_of_month_str = $first_day_of_month->format('Y-m-d'); // Save it in a variable

    if ($current_date->format('j') == 1) {
        $resetQuery = "UPDATE customer 
                       SET paid = 'N' 
                       WHERE paid = 'Y'
                       AND a_id = :admin_id";

        $stmt = $pdo->prepare($resetQuery);
        $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Query to fetch customers who are unpaid (paid = 'N')
    $query = "SELECT *, 
              CASE
                  WHEN paid = 'N' THEN FLOOR(DATE_PART('day', NOW() - last_paid_date) / 30)
                  ELSE 0
              END AS unpaid_months 
              FROM customer 
              WHERE a_id = :admin_id 
              AND c_name ILIKE :search
              AND paid = 'N'";  // Only show customers who are unpaid

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $search_term = '%' . $search . '%';
    $stmt->bindParam(':search', $search_term, PDO::PARAM_STR);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Recalculate total number of unpaid customers
    $total_customers_unpaid = count($customers);

    // Query to count total paid customers and sum their bundle prices (considering payments after the 1st of the current month)
    $paidQuery = "SELECT COUNT(*) as total_paid_customers, SUM(bundle_price) as total_paid_amount 
                  FROM customer 
                  WHERE a_id = :admin_id 
                  AND paid = 'Y'
                  AND last_paid_date >= :first_day_of_month";

    $stmt = $pdo->prepare($paidQuery);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
    $stmt->bindParam(':first_day_of_month', $first_day_of_month_str, PDO::PARAM_STR); // Use the variable here
    $stmt->execute();
    $paidData = $stmt->fetch(PDO::FETCH_ASSOC);

    $total_customers_paid = $paidData['total_paid_customers'] ?? 0;
    $total_amount_paid = $paidData['total_paid_amount'] ?? 0;

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
    <title>Customer Management</title>
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

        .paid-yes {
            color: green;
            font-weight: bold;
        }

        .paid-no {
            color: red;
            font-weight: bold;
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

        .form-inline .form-control {
            margin-right: 10px;
        }

        .form-inline button {
            margin-top: 10px;
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

        .totals-container {
            background-color: #007bff;
            padding: 20px;
            border-radius: 8px;
            color: white;
            display: flex;
            justify-content: space-around;
            align-items: center;
            margin-bottom: 20px;
        }

        .total-customers, .total-amount {
            text-align: center;
        }

        .total-customers h5, .total-amount h5 {
            font-size: 18px;
        }

        .total-customers p, .total-amount p {
            font-size: 24px;
            font-weight: bold;
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
            <h2>Customer Management</h2>
            <p>Manage your customers efficiently and effectively</p>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <form method="get" action="index.php" class="form-inline">
                <input class="form-control" type="search" placeholder="Search by name" name="search" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
            <a href="add_customer.php" class="btn btn-success"><i class="fas fa-plus"></i> Add Customer</a>
        </div>
        
        <div class="table-responsive mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Bundle</th>
                        <th>Bundle Price</th>
                        <th>Paid</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($customer['c_id']); ?></td>
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
                                <a href="edit_customer.php?id=<?php echo $customer['c_id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                <form action="delete_customer.php" method="post" style="display:inline;">
                                    <input type="hidden" name="customer_id" value="<?php echo $customer['c_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="footer mt-4">
            <div class="totals-container">
                <div class="total-customers">
                    <h5>Total Paid Customers</h5>
                    <p><?php echo $total_customers_paid; ?></p>
                </div>
                <div class="total-customers">
                    <h5>Total Unpaid Customers</h5>
                    <p><?php echo $total_customers_unpaid; ?></p>
                </div>
                <div class="total-amount">
                    <h5>Total Amount Paid</h5>
                    <p>$<?php echo $total_amount_paid; ?></p>
                </div>
            </div>
        </div>

    </div>

    <div class="footer mt-4">
        <p>&copy; 2024 CoDelta Technologies. All rights reserved.</p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

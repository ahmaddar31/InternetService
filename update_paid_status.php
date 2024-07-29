<?php
session_start();
include('./db_config/connect.php');

if (!isset($_SESSION['userlog_info'])) {
    header('Location: login.php');
    exit();
}

// Function to get the Arabic name of the current month
function getArabicMonth($monthNumber) {
    $arabicMonths = [
        1 => 'كانون الثاني',
        2 => 'شباط',
        3 => 'آذار',
        4 => 'نيسان',
        5 => 'أيار',
        6 => 'حزيران',
        7 => 'تموز',
        8 => 'آب',
        9 => 'أيلول',
        10 => 'تشرين الأول',
        11 => 'تشرين الثاني',
        12 => 'كانون الأول'
    ];
    return $arabicMonths[$monthNumber];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $paid_status = $_POST['paid'];

    try {
        // Update the paid status in the database
        $query = "UPDATE customer SET paid = :paid WHERE c_id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':paid', $paid_status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the customer's phone number and name if the paid status is set to "Y"
        if ($paid_status == 'Y') {
            $query = "SELECT phone, c_name FROM customer WHERE c_id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $customer_id, PDO::PARAM_INT);
            $stmt->execute();
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($customer) {
                $customer_phone = str_replace(' ', '', $customer['phone']); // Remove spaces
                $customer_name = $customer['c_name'];

                // Ensure the phone number is in the correct international format
                if (strpos($customer_phone, '0') === 0) {
                    $customer_phone = '+961' . substr($customer_phone, 1);
                } else if (strpos($customer_phone, '+961') !== 0) {
                    $customer_phone = '+961' . $customer_phone;
                }

                // Get the current month in Arabic
                $currentMonthArabic = getArabicMonth(date('n'));

                // Generate the WhatsApp URL with the Arabic message
                $message = urlencode("مرحبا $customer_name, تم تسديد رسوم شهر $currentMonthArabic. شكراً!");
                $whatsappUrl = "https://wa.me/$customer_phone?text=$message";

                // Redirect to the WhatsApp URL
                header("Location: $whatsappUrl");
                exit();
            } else {
                // Customer not found
                header('Location: index.php?error=CustomerNotFound');
                exit();
            }
        } else {
            header('Location: index.php');
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header('Location: index.php');
    exit();
}
?>

<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Driver.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'driver') {
    header("Location: ../../public/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$driver = new Driver($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    if ($driver->updateOrderStatus($order_id, $status)) {
        $success = "Order status updated successfully!";
    } else {
        $error = "Failed to update order status. Please check if the order ID is correct.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa; /* Light background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }
        .btn-custom {
            background-color: #2C3E50;
            color: #fff;
            border: none;
        }
        .btn-custom:hover {
            background-color: #1A242F;
        }
        .btn-back {
            margin-top: 15px;
        }
    </style>
    <title>Update Order Status - Rindra Delivery Service</title>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Update Order Status</h2>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="order_id">Order ID:</label>
                <input type="number" name="order_id" id="order_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="picked up">Picked Up</option>
                    <option value="delivered">Delivered</option>
                </select>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Update Status</button>
        </form>
        <div class="btn-back text-center">
            <a href="dashboard.php" class="btn btn-custom mt-3">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>

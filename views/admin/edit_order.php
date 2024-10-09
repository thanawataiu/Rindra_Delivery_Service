<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../public/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$admin = new Admin($conn);

$order_id = $_GET['id']; 
$order = $admin->getOrderById($order_id);

// Handle form submission to update the order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST['client_id'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $driver_id = $_POST['driver_id'];

    if ($admin->updateOrder($order_id, $client_id, $address, $status, $driver_id)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Failed to update order.";
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
            background: linear-gradient(135deg, #34495E, #2C3E50); 
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .edit-order-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2), 0 0 10px #FDD36A;
            width: 100%;
            max-width: 600px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .edit-order-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 12px #FDD36A;
        }
        h2 {
            text-align: center;
            color: #2C3E50;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .btn-custom {
            background-color: #2C3E50;
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px 20px; 
            border-radius: 25px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 0 5px #FDD36A;
        }
        .btn-custom:hover {
            background-color: #FFC20E;
            color: #2C3E50;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3), 0 0 7px #FDD36A;
        }
        .form-group label {
            font-weight: bold;
            color: #2C3E50;
        }
        .form-control {
            border-radius: 25px;
        }
        .alert {
            margin-top: 20px;
        }
        .navbar {
            background-color: #2C3E50;
            padding: 15px 20px;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .navbar-brand {
            font-weight: bold;
            color: #FDD36A;
        }
        .btn-logout, .btn-back-dashboard {
            color: #FFC20E;
            font-weight: bold;
            border: none;
            background: none;
            text-decoration: none;
        }
        .btn-logout:hover, .btn-back-dashboard:hover {
            color: #FDD36A;
        }
        .navbar-nav {
            margin-left: auto;
        }
        .navbar-nav a {
            margin-left: 20px;
        }
    </style>
    <title>Edit Order - Rindra Delivery Service</title>
</head>
<body>
    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">Rindra Delivery Service - Admin Panel</a>
        <div class="navbar-nav ml-auto">
            <a href="http://localhost/rindra_delivery_service/views/admin/dashboard.php" class="btn-back-dashboard">Back</a>
            <a href="../../public/logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="edit-order-box mt-5">
        <h2>Edit Order</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="client_id">Client ID</label>
                <input type="text" class="form-control" id="client_id" name="client_id" value="<?= $order['client_id']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?= $order['address']; ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending" <?= ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="picked up" <?= ($order['status'] == 'picked up') ? 'selected' : ''; ?>>Picked Up</option>
                    <option value="delivered" <?= ($order['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                    <option value="canceled" <?= ($order['status'] == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
                </select>
            </div>
            <div class="form-group">
                <label for="driver_id">Driver ID</label>
                <input type="text" class="form-control" id="driver_id" name="driver_id" value="<?= $order['driver_id']; ?>" required>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Update Order</button>
        </form>
    </div>
</body>
</html>

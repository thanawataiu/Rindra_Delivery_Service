<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Client.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'client') {
    header("Location: ../../public/login.php");
    exit();
}

if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die("Order ID is missing.");
}

$order_id = $_GET['order_id'];

$db = new Database();
$conn = $db->getConnection();
$client = new Client($conn);

// Fetch the order details using the client ID and order ID
$order = $client->getOrderDetails($order_id, $_SESSION['user_id']);

if (!$order) {
    die("Invalid order or unauthorized access.");
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
            background: #F5F7FA;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 50px;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #2C3E50;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .info-group {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: bold;
            color: #2C3E50;
        }
    </style>
    <title>Track Order - Rindra Delivery Service</title>
</head>
<body>
    <div class="container">
        <h2>Order Tracking</h2>
        
        <div class="info-group">
            <span class="info-label">Order ID:</span> <?= $order['id']; ?>
        </div>
        
        <div class="info-group">
            <span class="info-label">Client Address:</span> <?= $order['address']; ?>
        </div>
        
        <div class="info-group">
            <span class="info-label">Order Status:</span> <?= ucfirst($order['status']); ?>
        </div>
        
        <div class="info-group">
            <span class="info-label">Driver:</span> <?= $order['driver_name'] ?: 'Not Assigned'; ?>
        </div>
        
        <div class="info-group">
            <span class="info-label">Estimated Delivery Time:</span> 
            <?= ($order['status'] == 'picked up' || $order['status'] == 'in transit') ? '2-3 hours' : 'N/A'; ?>
        </div>
        
        <a href="dashboard.php" class="btn btn-primary">Back to Orders</a>
    </div>
</body>
</html>

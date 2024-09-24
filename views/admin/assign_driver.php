<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Admin.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../public/login.php");
    exit();
}

// Database connection
$db = new Database();
$conn = $db->getConnection();
$admin = new Admin($conn);

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $driver_id = $_POST['driver_id'];

    // Assign the driver to the order
    if ($admin->assignDriver($order_id, $driver_id)) {
        $success = "Driver assigned successfully!";
    } else {
        $error = "Failed to assign driver.";
    }
}

// Fetch available orders and drivers for the form
$orderQuery = "SELECT id FROM orders WHERE driver_id IS NULL";
$driverQuery = "SELECT id, name FROM users WHERE role = 'driver'";

// Fetch orders
$orderStmt = $conn->prepare($orderQuery);
$orderStmt->execute();
$availableOrders = $orderStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch drivers
$driverStmt = $conn->prepare($driverQuery);
$driverStmt->execute();
$availableDrivers = $driverStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #34495E, #2C3E50); /* Cool gradient background */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            color: white;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2), 0 0 10px #FFC20E; /* Thin yellow shadow */
            width: 100%;
            max-width: 500px; /* Slightly wider */
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .container:hover {
            transform: translateY(-5px); /* Subtle lift effect */
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 12px #FFC20E;
        }
        h2 {
            margin-bottom: 20px;
            color: #2C3E50; /* Dark color for heading */
            text-align: center;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #2C3E50; /* Button color */
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px 20px; 
            border-radius: 25px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 0 5px #FFC20E; /* Thin yellow shadow */
        }
        .btn-custom:hover {
            background-color: #1A242F; /* Darker shade on hover */
            transform: translateY(-2px); /* Slight lift effect */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3), 0 0 7px #FFC20E;
        }
        .btn-back {
            margin-top: 15px;
            display: block;
            text-align: center;
        }
        .form-group label {
            color: #333;
            font-weight: 600; /* Slightly bolder labels */
        }
        .alert {
            text-align: center;
            border-radius: 5px;
        }
    </style>
    <title>Assign Driver - Rindra Delivery Service</title>
</head>
<body>
    <div class="container">
        <h2>Assign Driver to Order</h2>
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="order_id">Order ID:</label>
                <select name="order_id" id="order_id" class="form-control" required>
                    <option value="">Select Order</option>
                    <?php foreach ($availableOrders as $order): ?>
                        <option value="<?= $order['id']; ?>"><?= $order['id']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="driver_id">Driver ID:</label>
                <select name="driver_id" id="driver_id" class="form-control" required>
                    <option value="">Select Driver</option>
                    <?php foreach ($availableDrivers as $driver): ?>
                        <option value="<?= $driver['id']; ?>"><?= $driver['name']; ?> (ID: <?= $driver['id']; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Assign Driver</button>
        </form>
        <!-- Back to Home Button -->
        <div class="btn-back">
            <a href="http://localhost/rindra_delivery_service/public/index.php" class="btn btn-custom">Back to Home</a>
        </div>
    </div>
</body>
</html>

<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Client.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'client') {
    header("Location: ../../public/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$client = new Client($conn);

$orderDetails = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $orderDetails = $client->viewOrderStatus($order_id);
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
            max-width: 600px; /* Slightly wider */
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .container:hover {
            transform: translateY(-5px); /* Subtle lift effect */
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 12px #FFC20E; 
        }
        h2, h3 {
            color: #2C3E50; /* Dark color for headings */
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
        .form-group label {
            color: #333;
            font-weight: bold;
        }
        .alert {
            text-align: center;
            border-radius: 5px;
        }
    </style>
    <title>View Order Status - Rindra Delivery Service</title>
</head>
<body>
    <div class="container">
        <h2>View Order Status</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="order_id">Order ID:</label>
                <input type="number" name="order_id" id="order_id" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-custom btn-block">View Status</button>
        </form>
        
        <?php if ($orderDetails): ?>
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> <?= $orderDetails['id']; ?></p>
            <p><strong>Address:</strong> <?= $orderDetails['address']; ?></p>
            <p><strong>Status:</strong> 
                <?php
                    $status = $orderDetails['status'];
                    $badgeClass = '';
                    switch ($status) {
                        case 'pending':
                            $badgeClass = 'badge-warning';
                            break;
                        case 'picked up':
                            $badgeClass = 'badge-info';
                            break;
                        case 'delivered':
                            $badgeClass = 'badge-success';
                            break;
                        default:
                            $badgeClass = 'badge-secondary';
                            break;
                    }
                ?>
                <span class="badge <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>

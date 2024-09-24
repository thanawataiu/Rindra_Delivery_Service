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
$orderHistory = $client->getOrderHistory();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #F8F9FA; /* Light background */
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #34495E; /* Matching dark blue-gray color */
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            color: #fff;
            font-weight: bold;
        }
        .nav-link {
            color: #fff !important;
        }
        .container {
            margin-top: 40px;
            padding: 20px;
            background-color: #FFFFFF;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .table {
            border-radius: 5px;
            overflow: hidden;
            margin-top: 20px;
        }
        .table thead {
            background-color: #DC8449; /* Orange header color */
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5; /* Slight hover effect */
        }
        .table td, .table th {
            text-align: center;
            vertical-align: middle;
        }
        .welcome-text {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }
        .badge {
            padding: 5px 10px;
            font-size: 14px;
        }
        .logout-btn {
            background-color: #34495E;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: #fff;
        }
        .logout-btn:hover {
            background-color: #1A242F;
            color: white;
        }
    </style>
    <title>Client Dashboard - Rindra Delivery Service</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">Rindra Delivery Service</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="../../public/logout.php" class="btn logout-btn">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h2 class="welcome-text">Welcome, <?= $_SESSION['user_id'] ?>! Here is your order history:</h2>
        
        <?php if (empty($orderHistory)) : ?>
            <div class="alert alert-warning text-center">No orders found in your history.</div>
        <?php else : ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderHistory as $order): ?>
                        <tr>
                            <td><?= $order['id']; ?></td>
                            <td><?= $order['address']; ?></td>
                            <td>
                                <?php
                                    $status = $order['status'];
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

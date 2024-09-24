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
$assignedOrders = $driver->getAssignedOrders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 100%;
        }
        .navbar {
            background-color: #2C3E50;
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            color: #fff;
        }
        .navbar a {
            color: #fff !important;
        }
        .table thead {
            background-color: #DC8449; /* Matching orange color */
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
        .btn-custom {
            background-color: #2C3E50;
            color: #fff;
            border: none;
            margin: 10px 0;
        }
        .btn-custom:hover {
            background-color: #1A242F;
        }
    </style>
    <title>Assigned Orders - Rindra Delivery Service</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">Rindra Delivery Service - Driver Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="../../public/logout.php" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <h1 class="text-center">Assigned Orders</h1>
        
        <?php if (empty($assignedOrders)): ?>
            <div class="alert alert-warning text-center">You have no assigned orders at the moment.</div>
        <?php else: ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Client ID</th>
                        <th>Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignedOrders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']); ?></td>
                            <td><?= htmlspecialchars($order['client_id']); ?></td>
                            <td><?= htmlspecialchars($order['address']); ?></td>
                            <td>
                                <?php
                                $status = htmlspecialchars($order['status']);
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

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="btn btn-custom btn-block">Back to Dashboard</a>
    </div>
</body>
</html>

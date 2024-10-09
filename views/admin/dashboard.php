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
$orders = $admin->getOrders();
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
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: white;
        }
        .navbar {
            background-color: #2C3E50;
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        .navbar-brand {
            color: #FFC20E;
            font-weight: bold;
        }
        .nav-link, .logout-btn {
            color: #FFC20E !important;
        }
        .logout-btn:hover {
            background-color: #1A242F;
            color: white;
        }
        .container {
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2), 0 0 10px #FFC20E;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 12px #FFC20E;
        }
        .welcome-text {
            color: #2C3E50;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .table {
            border-radius: 10px;
            overflow: hidden;
            margin-top: 20px;
            width: 100%;
        }
        .table thead {
            background-color: #DC8449;
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
            color: #2C3E50;
        }
        .btn-custom {
            background-color: #2C3E50;
            border: none;
            color: white;
            margin-right: 10px;
            font-weight: bold;
            border-radius: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 0 5px #FFC20E;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s, color 0.3s;
        }

        .btn-custom:hover {
            background-color: #FFC20E;
            color: #2C3E50;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3), 0 0 7px #FFC20E;
        }

        .badge {
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
    <title>Admin Dashboard - Rindra Delivery Service</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">Rindra Delivery Service - Admin Panel</a>
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
        <h2 class="welcome-text">Welcome, Admin! Manage the orders below:</h2>
        
        <div class="mb-3 text-right">
            <a href="create_order.php" class="btn btn-custom">Create Order</a>
            <a href="assign_driver.php" class="btn btn-custom">Assign Driver</a>
            <a href="view_order_history.php" class="btn btn-custom">View Order History</a>
        </div>

        <?php if (empty($orders)) : ?>
            <div class="alert alert-warning text-center">No orders found.</div>
        <?php else : ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Order ID</th>
                        <th>Client ID</th>
                        <th>Address</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Driver ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1; // For numbering the rows
                    foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $counter++; ?></td> <!-- Order Number -->
                            <td><?= $order['id']; ?></td>
                            <td><?= $order['client_id']; ?></td>
                            <td><?= $order['address']; ?></td>
                            <td><?= date('Y-m-d', strtotime($order['created_at'])); ?></td> <!-- Display order date -->
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
                            <td><?= $order['driver_id'] ?: 'Not Assigned'; ?></td>
                            <td>
                                <a href="edit_order.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-primary">Modify</a> <!-- Modify Button -->
                                <a href="delete_order.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a> <!-- Delete Button -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>

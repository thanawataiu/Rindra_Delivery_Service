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

// Pagination setup
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Get the current page or default to 1
$offset = ($page - 1) * $limit;  // Calculate offset

// Fetch the client's order history with pagination
$orderHistory = $client->getOrderHistory($_SESSION['user_id'], $limit, $offset);

// Fetch the total number of orders to calculate total pages
$totalOrders = count($client->getOrderHistory($_SESSION['user_id']));
$totalPages = ceil($totalOrders / $limit);  // Calculate total pages
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
        .btn-custom {
            background-color: #34495E; /* Button color */
            border: none;
            color: white;
            padding: 10px 20px;
            font-weight: bold;
            border-radius: 25px;
            transition: background-color 0.3s, color 0.3s, transform 0.3s;
        }
        .btn-custom:hover {
            background-color: #FFC20E; /* Change to yellow on hover */
            color: #34495E; /* Dark blue-gray text color */
            transform: translateY(-2px); /* Slight lift effect */
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
        <h2 class="welcome-text">Welcome, <?= $_SESSION['user_id'] ?>!</h2>

        <!-- Button to View Full Order History -->
        <div class="mb-3 text-right">
            <a href="view_order_history.php" class="btn btn-custom">View Full Order History</a>
        </div>

        <h3 class="welcome-text">Here is your most recent order history:</h3>
        
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

            <!-- Pagination Controls -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <!-- Previous button -->
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Page numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next button -->
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</body>
</html>

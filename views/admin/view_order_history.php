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

// Fetch available drivers for filter dropdown
$drivers = $admin->getAllDrivers();

// Pagination setup
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search and filter setup
$searchClient = isset($_GET['search_client']) ? $_GET['search_client'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filterDriver = isset($_GET['driver']) ? $_GET['driver'] : '';

// Fetch filtered orders with pagination
$orderHistory = $admin->getFilteredOrders($searchClient, $filterStatus, $filterDriver, $limit, $offset);

// Fetch total filtered orders to calculate pagination
$totalOrders = count($admin->getFilteredOrders($searchClient, $filterStatus, $filterDriver)); 
$totalPages = ceil($totalOrders / $limit);
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
        .navbar {
            background-color: #2C3E50; /* Dark blue-gray color */
            padding: 15px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }
        .navbar-brand {
            color: #FFC20E; /* Yellow color */
            font-weight: bold;
        }
        .navbar-nav {
            margin-left: auto;
            display: flex;
            align-items: center;
        }
        .btn-back-dashboard, .logout-btn {
            color: #FFC20E !important; /* Yellow for links */
            font-weight: bold;
            margin-left: 15px;
        }
        .btn-back-dashboard:hover, .logout-btn:hover {
            background-color: #1A242F;
            color: white;
            text-decoration: none;
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
        .form-inline {
            justify-content: center;
            margin-bottom: 30px;
        }
        .form-control {
            border-radius: 20px;
        }
        .btn-primary {
            background-color: #3498DB;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background-color: #2980B9;
        }
        .table {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
        }
        .table thead {
            background-color: #2980B9;
            color: #fff;
        }
        .table-hover tbody tr:hover {
            background-color: #F2F2F2;
        }
        .badge {
            padding: 10px;
            border-radius: 15px;
        }
        .badge-success {
            background-color: #27AE60;
            color: white;
        }
        .badge-danger {
            background-color: #E74C3C;
            color: white;
        }
        .pagination .page-link {
            color: #3498DB;
        }
        .pagination .page-item.active .page-link {
            background-color: #3498DB;
            border-color: #3498DB;
        }
    </style>
    <title>Admin Order History - Rindra Delivery Service</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">Rindra Delivery Service - Admin Panel</a>
        <div class="navbar-nav">
            <a href="http://localhost/rindra_delivery_service/views/admin/dashboard.php" class="btn-back-dashboard">Back to Dashboard</a>
            <a href="../../public/logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>Global Order History</h2>

        <!-- Search and Filter Form -->
        <form method="GET" action="view_order_history.php" class="form-inline">
            <input type="text" name="search_client" class="form-control mr-2" placeholder="Search by Client Name" value="<?= $searchClient ?>">
            
            <select name="status" class="form-control mr-2">
                <option value="">All Statuses</option>
                <option value="pending" <?= ($filterStatus == 'pending') ? 'selected' : '' ?>>Pending</option>
                <option value="delivered" <?= ($filterStatus == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                <option value="canceled" <?= ($filterStatus == 'canceled') ? 'selected' : '' ?>>Canceled</option>
            </select>
            
            <select name="driver" class="form-control mr-2">
                <option value="">All Drivers</option>
                <?php foreach ($drivers as $driver): ?>
                    <option value="<?= $driver['id'] ?>" <?= ($filterDriver == $driver['id']) ? 'selected' : '' ?>>
                        <?= $driver['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <?php if (empty($orderHistory)) : ?>
            <div class="alert alert-warning text-center">No orders found.</div>
        <?php else : ?>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order ID</th>
                        <th>Client Name</th> <!-- Added Client Name -->
                        <th>Driver ID</th>
                        <th>Address</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = ($page - 1) * $limit + 1;  // For numbering rows
                    foreach ($orderHistory as $order): ?>
                        <tr>
                            <td><?= $counter++; ?></td> <!-- Increment row number -->
                            <td><?= $order['id']; ?></td>
                            <td><?= $order['client_name']; ?></td>
                            <td><?= $order['driver_id'] ?: 'Not Assigned'; ?></td>
                            <td><?= $order['address']; ?></td>
                            <td><?= date('Y-m-d', strtotime($order['created_at'])); ?></td> <!-- Display order date -->
                            <td>
                                <?php $status = $order['status']; ?>
                                <span class="badge <?= ($status == 'delivered') ? 'badge-success' : 'badge-danger'; ?>">
                                    <?= ucfirst($status) ?>
                                </span>
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
                            <a class="page-link" href="?page=<?= $page - 1 ?>&search_client=<?= $searchClient ?>&status=<?= $filterStatus ?>&driver=<?= $filterDriver ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Page numbers -->
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search_client=<?= $searchClient ?>&status=<?= $filterStatus ?>&driver=<?= $filterDriver ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next button -->
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&search_client=<?= $searchClient ?>&status=<?= $filterStatus ?>&driver=<?= $filterDriver ?>" aria-label="Next">
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

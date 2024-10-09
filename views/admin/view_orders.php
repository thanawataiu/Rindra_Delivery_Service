<?php
session_start();
require_once '../../config/database.php';
require_once '../../classes/Admin.php';
require_once '../../classes/Order.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../public/login.php");
    exit();
}

$db = new Database();
$conn = $db->getConnection();
$order = new Order($conn);

// Pagination setup
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search and filter setup
$searchClient = isset($_GET['search_client']) ? $_GET['search_client'] : '';
$filterStatus = isset($_GET['status']) ? $_GET['status'] : '';
$filterDriver = isset($_GET['driver']) ? $_GET['driver'] : '';

// Fetch filtered orders with pagination
$orders = $order->getFilteredOrders($searchClient, $filterStatus, $filterDriver, $limit, $offset);

// Fetch total filtered orders to calculate pagination
$totalOrders = $order->getTotalFilteredOrders($searchClient, $filterStatus, $filterDriver); 
$totalPages = ceil($totalOrders / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styling */
    </style>
    <title>View Orders - Rindra Delivery Service</title>
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
        <h2 class="text-center">All Orders</h2>

        <!-- Search and Filter Form -->
        <form method="GET" action="view_order.php" class="form-inline">
            <input type="text" name="search_client" class="form-control mr-2" placeholder="Search by Client ID" value="<?= $searchClient ?>">
            
            <select name="status" class="form-control mr-2">
                <option value="">All Statuses</option>
                <option value="pending" <?= ($filterStatus == 'pending') ? 'selected' : '' ?>>Pending</option>
                <option value="delivered" <?= ($filterStatus == 'delivered') ? 'selected' : '' ?>>Delivered</option>
                <option value="canceled" <?= ($filterStatus == 'canceled') ? 'selected' : '' ?>>Canceled</option>
            </select>
            
            <select name="driver" class="form-control mr-2">
                <option value="">All Drivers</option>
                <!-- Optionally, fetch drivers dynamically if needed -->
                <option value="1" <?= ($filterDriver == '1') ? 'selected' : '' ?>>Driver 1</option>
                <option value="2" <?= ($filterDriver == '2') ? 'selected' : '' ?>>Driver 2</option>
            </select>
            
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <?php if (empty($orders)) : ?>
            <div class="alert alert-warning text-center">No orders found.</div>
        <?php else : ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client ID</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Driver ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['id']; ?></td>
                            <td><?= $order['client_id']; ?></td>
                            <td><?= $order['address']; ?></td>
                            <td><span class="badge badge-<?= $order['status'] == 'delivered' ? 'success' : 'warning' ?>"><?= ucfirst($order['status']) ?></span></td>
                            <td><?= $order['driver_id'] ?: 'Not Assigned'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search_client=<?= $searchClient ?>&status=<?= $filterStatus ?>&driver=<?= $filterDriver ?>">&laquo;</a></li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search_client=<?= $searchClient ?>&status=<?= $filterStatus ?>&driver=<?= $filterDriver ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search_client=<?= $searchClient ?>&status=<?= $filterStatus ?>&driver=<?= $filterDriver ?>">&raquo;</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</body>
</html>

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

// Pagination setup
$limit = 10;  // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Get current page from URL or default to 1
$offset = ($page - 1) * $limit;  // Calculate offset

// Fetch total number of completed orders to calculate total pages
$totalOrders = $driver->getTotalCompletedOrders(); // Separate method to get total count of completed orders
$totalPages = ceil($totalOrders / $limit);  // Total number of pages

// Fetch completed orders with pagination
$completedOrders = $driver->getCompletedOrders($limit, $offset); // Use limit and offset to paginate

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Driver Delivery History - Rindra Delivery Service</title>
</head>
<body>
    <div class="container">
        <h2>Your Delivery History</h2>
        <?php if (empty($completedOrders)) : ?>
            <div class="alert alert-warning">No completed deliveries.</div>
        <?php else : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Client ID</th>
                        <th>Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($completedOrders as $order): ?>
                        <tr>
                            <td><?= $order['id']; ?></td>
                            <td><?= $order['client_id']; ?></td>
                            <td><?= $order['address']; ?></td>
                            <td><span class="badge badge-success">Delivered</span></td>
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

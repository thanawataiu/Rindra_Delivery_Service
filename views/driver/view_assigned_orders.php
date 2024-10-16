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
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Get the current page or default to 1
$offset = ($page - 1) * $limit;  // Calculate offset

// Fetch assigned orders with pagination
$assignedOrders = $driver->getAssignedOrders($limit, $offset);

// Fetch total assigned orders for pagination calculation
$totalAssignedOrders = count($driver->getAssignedOrders());
$totalPages = ceil($totalAssignedOrders / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Your existing styles */
    </style>
    <title>Assigned Orders - Rindra Delivery Service</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <!-- Navbar content -->
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

        <!-- Back to Dashboard Button -->
        <a href="dashboard.php" class="btn btn-custom btn-block">Back to Dashboard</a>
    </div>
</body>
</html>

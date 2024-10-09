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

// Get the order ID from the URL
$order_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$order_id) {
    die("Invalid order ID.");
}

// Delete the order
if ($admin->deleteOrder($order_id)) {
    $_SESSION['success'] = "Order deleted successfully!";
    header("Location: dashboard.php");
    exit();
} else {
    die("Failed to delete the order.");
}

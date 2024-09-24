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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = trim($_POST['client_id']);
    $address = trim($_POST['address']);
    
    if ($admin->createOrder($client_id, $address)) {
        $success = "Order created successfully!";
    } else {
        $error = "Failed to create order. Please try again.";
    }
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
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: white;
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
        .nav-link, .logout-btn {
            color: #FFC20E !important; /* Yellow for links */
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
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2), 0 0 10px #FFC20E; /* Yellow shadow */
            transition: transform 0.3s, box-shadow 0.3s;
            max-width: 600px;
        }
        .container:hover {
            transform: translateY(-5px); /* Subtle lift effect */
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 12px #FFC20E; /* Enhanced yellow shadow */
        }
        h2 {
            color: #2C3E50;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .btn-custom {
            background-color: #2C3E50; /* Matching button color */
            border: none;
            color: white;
            margin-right: 10px;
            font-weight: bold;
            border-radius: 25px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 0 5px #FFC20E; /* Yellow shadow */
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
        }
        .btn-custom:hover {
            background-color: #1A242F; /* Darker shade on hover */
            transform: translateY(-2px); /* Slight lift effect */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3), 0 0 7px #FFC20E;
        }
        .form-group label {
            color: #2C3E50;
            font-weight: bold;
        }
        .alert {
            text-align: center;
            border-radius: 5px;
        }
    </style>
    <title>Create Order - Rindra Delivery Service</title>
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
        <h2>Create New Order</h2>
        
        <?php if (isset($success)) : ?>
            <div class="alert alert-success"><?= $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger"><?= $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="client_id">Client ID:</label>
                <input type="text" class="form-control" id="client_id" name="client_id" required>
            </div>
            <div class="form-group">
                <label for="address">Delivery Address:</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Create Order</button>
        </form>
    </div>
</body>
</html>

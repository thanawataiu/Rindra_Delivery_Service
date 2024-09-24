<?php
session_start();
require_once '../config/database.php';
require_once '../classes/User.php';

$db = new Database();
$conn = $db->getConnection();
$userClass = new User($conn);

$error = ''; // Initialize the error variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Check if the user exists and password matches
    $user = $userClass->login($email, $password);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        
        // Redirect to the respective dashboard
        if ($user['role'] == 'admin') {
            header("Location: ../views/admin/dashboard.php");
        } elseif ($user['role'] == 'driver') {
            header("Location: ../views/driver/dashboard.php");
        } elseif ($user['role'] == 'client') {
            header("Location: ../views/client/dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid credentials! User not found!";
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
            background: linear-gradient(to right, #0B3D91, #FDD36A); /* Blue to yellow gradient */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .login-box {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            border: 3px solid #FDD36A; /* Yellow border */
            position: relative;
        }
        .login-box h2 {
            margin-bottom: 20px;
            color: #0B3D91; /* Blue color for the heading */
            text-align: center;
        }
        .btn-custom {
            background-color: #0B3D91; /* Blue button */
            border: none;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #08417A; /* Darker blue on hover */
        }
        .form-group label {
            color: #333;
        }
        a {
            color: #0B3D91; /* Blue link color */
        }
        a:hover {
            color: #08417A; /* Darker blue on hover */
        }
        /* Styling for the error message container */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            text-align: center;
        }
    </style>
    <title>Rindra Delivery Service - Login</title>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Login</button>
        </form>
        <div class="text-center mt-3">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>

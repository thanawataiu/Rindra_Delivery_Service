<?php
session_start();
require_once '../config/database.php';
require_once '../classes/User.php';

$db = new Database();
$conn = $db->getConnection();

$userClass = new User($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if ($userClass->register($name, $email, $password)) {
        $_SESSION['success'] = "Registration successful! You can log in now.";
        header("Location: login.php");
        exit();
    } else {
        $error = "Registration failed. The email may already be in use.";
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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .register-box {
            background-color: #fff;
            padding: 40px; 
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2), 0 0 10px #FDD36A;
            width: 100%;
            max-width: 450px;
            transition: transform 0.3s, box-shadow 0.3s; 
            border-top: 5px solid #2C3E50; 
        }
        .register-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 12px #FDD36A; 
        }
        .register-box h2 {
            margin-bottom: 20px;
            color: #2C3E50; /* Darker color for the heading */
            text-align: center;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #2C3E50; /* Button color */
            border: none;
            color: white;
            font-weight: bold;
            padding: 10px 20px; 
            border-radius: 25px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 0 5px #FDD36A; /* Thin yellow shadow */
        }
        .btn-custom:hover {
            background-color: #1A242F; /* Darker shade on hover */
            transform: translateY(-2px); /* Slight lift effect on hover */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3), 0 0 7px #FDD36A; 
        }
        .form-group label {
            color: #333;
            font-weight: 600;
        }
        a {
            color: #2C3E50; /* Link color */
        }
        a:hover {
            color: #1A242F; /* Link hover effect */
        }
    </style>
    <title>Rindra Delivery Service - Register</title>
</head>
<body>
    <div class="register-box">
        <h2>Create Your Account</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-custom btn-block">Register</button>
        </form>
        <div class="text-center mt-3">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>

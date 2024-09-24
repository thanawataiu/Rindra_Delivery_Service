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
            color: white;
        }
        .login-box {
            background-color: #022C5D; /* Slightly darker blue */
            padding: 40px; /* Increased padding */
            border-radius: 15px; /* Rounded corners */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2), 0 0 10px #FFC20E; /* Thin yellow shadow */
            width: 100%;
            max-width: 450px; /* Slightly wider */
            border-top: 5px solid #FFC20E; /* Yellow top border */
            transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition effects */
            position: relative;
        }
        .login-box:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3), 0 0 12px #FFC20E; /* Enhanced yellow shadow */
        }
        .login-box h2 {
            margin-bottom: 20px;
            color: #FFC20E; /* Yellow heading */
            text-align: center;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #FFC20E; /* Yellow button */
            border: none;
            color: #022C5D;
            font-weight: bold;
            padding: 10px 20px; /* Adjusted padding */
            border-radius: 25px; /* Rounded button */
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 0 5px #FFC20E; /* Thin yellow shadow */
        }
        .btn-custom:hover {
            background-color: #FFB000; /* Darker yellow on hover */
            transform: translateY(-2px); /* Slight lift effect */
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3), 0 0 7px #FFC20E; /* Enhanced yellow shadow */
        }
        .form-group label {
            color: #FFC20E;
            font-weight: 600; /* Slightly bolder labels */
        }
        a {
            color: #FFC20E;
            text-decoration: none;
        }
        a:hover {
            color: #FFB000;
            text-decoration: underline;
        }
        .triangle {
            position: absolute;
            top: -30px;
            right: -20px;
            width: 0;
            height: 0;
            border-left: 50px solid transparent;
            border-right: 50px solid transparent;
            border-bottom: 50px solid #FFC20E; /* Yellow triangle */
        }
    </style>
    <title>Rindra Delivery Service - Login</title>
</head>
<body>
    <div class="login-box">
        <div class="triangle"></div>
        <h2>Rindra Delivery Service</h2>
        <form action="login.php" method="POST">
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

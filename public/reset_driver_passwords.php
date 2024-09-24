<?php
require_once '../config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Define drivers and new password
$drivers = [
    ['email' => 'driver1@gmail.com', 'password' => 'driver123'],
    ['email' => 'driver2@gmail.com', 'password' => 'driver123'],
    ['email' => 'driver3@gmail.com', 'password' => 'driver123']
];

foreach ($drivers as $driver) {
    // Create a hashed password using password_hash
    $newPasswordHash = password_hash($driver['password'], PASSWORD_DEFAULT);

    // driver's password in the database
    $query = "UPDATE users SET password = :password WHERE email = :email AND role = 'driver'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':password', $newPasswordHash);
    $stmt->bindParam(':email', $driver['email']);

    if ($stmt->execute()) {
        echo "Password for " . $driver['email'] . " has been reset successfully. New hash: " . $newPasswordHash . "<br>";
    } else {
        echo "Failed to reset password for " . $driver['email'] . "<br>";
    }
}
?>

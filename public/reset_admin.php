<?php
require_once '../config/database.php';
$db = new Database();
$conn = $db->getConnection();

// Delete the existing admin entry if it exists
$deleteQuery = "DELETE FROM users WHERE email = 'admin@gmail.com'";
$conn->prepare($deleteQuery)->execute();

// Now, insert the new admin entry
$hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
$query = "INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@gmail.com', :password, 'admin')";
$stmt = $conn->prepare($query);
$stmt->bindParam(':password', $hashedPassword);
$stmt->execute();

echo "Admin user re-created with a hashed password.";
?>

<?php
$adminPassword = "admin123";
$adminHash = password_hash($adminPassword, PASSWORD_DEFAULT);
echo "Admin password hash: " . $adminHash . "<br>";

$driverPassword = "driver123";
$driverHash = password_hash($driverPassword, PASSWORD_DEFAULT);
echo "Driver password hash: " . $driverHash;
?>

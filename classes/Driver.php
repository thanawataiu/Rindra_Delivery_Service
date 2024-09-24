<?php
require_once 'User.php'; 

class Driver extends User {
    // Constructor to initialize the database connection from the parent User class
    public function __construct($db) {
        parent::__construct($db); // Inherit the database connection from User class
    }

    // Method to fetch all orders assigned to the logged-in driver
    public function getAssignedOrders() {
        try {
            $query = "SELECT * FROM orders WHERE driver_id = :driver_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':driver_id', $_SESSION['user_id']); 
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching assigned orders: " . $e->getMessage();
            return [];
        }
    }

    // Method to update the status of an order assigned to the driver
    public function updateOrderStatus($order_id, $status) {
        try {
            $query = "UPDATE orders SET status = :status WHERE id = :order_id AND driver_id = :driver_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':driver_id', $_SESSION['user_id']); 
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error updating order status: " . $e->getMessage();
            return false;
        }
    }
}
?>

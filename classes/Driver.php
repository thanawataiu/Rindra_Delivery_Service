<?php
require_once 'User.php'; 

class Driver extends User {
    // Constructor to initialize the database connection from the parent User class
    public function __construct($db) {
        parent::__construct($db); // Inherit the database connection from User class
    }

    // Method to fetch all orders assigned to the logged-in driver (with pagination)
    public function getAssignedOrders($limit = 10, $offset = 0) {
        try {
            $query = "SELECT * FROM orders WHERE driver_id = :driver_id LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':driver_id', $_SESSION['user_id']);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);  // Add pagination limit
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT); // Add pagination offset
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching assigned orders: " . $e->getMessage();
            return [];
        }
    }

    // Method to fetch all completed orders (for driver's delivery history)
    public function getCompletedOrders($limit = 10, $offset = 0) {
        try {
            $query = "SELECT * FROM orders WHERE driver_id = :driver_id AND status = 'delivered' LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':driver_id', $_SESSION['user_id']);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);  // Add pagination limit
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT); // Add pagination offset
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching completed orders: " . $e->getMessage();
            return [];
        }
    }

    // Method to get the total number of completed orders for the driver
    public function getTotalCompletedOrders() {
        try {
            $query = "SELECT COUNT(*) as total FROM orders WHERE driver_id = :driver_id AND status = 'delivered'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':driver_id', $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            echo "Error fetching total completed orders: " . $e->getMessage();
            return 0;
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

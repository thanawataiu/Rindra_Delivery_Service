<?php
require_once 'User.php';

class Client extends User {

    // View Order Details by Order ID (for tracking a specific order)
    public function getOrderDetails($order_id, $client_id) {
        try {
            // Fetching order details and the driver's name (if assigned)
            $query = "SELECT orders.*, users.name AS driver_name 
                      FROM orders 
                      LEFT JOIN users ON orders.driver_id = users.id 
                      WHERE orders.id = :order_id AND orders.client_id = :client_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching order details: " . $e->getMessage();
            return false;
        }
    }

    // Get Order History for the client
    public function getOrderHistory($client_id) {
        try {
            $query = "SELECT * FROM orders WHERE client_id = :client_id ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching order history: " . $e->getMessage();
            return [];
        }
    }

    // Track Order by Tracking Number (optional function if tracking is by number)
    public function trackOrderByTrackingNumber($tracking_number, $client_id) {
        try {
            $query = "SELECT * FROM orders WHERE tracking_number = :tracking_number AND client_id = :client_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tracking_number', $tracking_number);
            $stmt->bindParam(':client_id', $client_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error tracking order: " . $e->getMessage();
            return false;
        }
    }
}

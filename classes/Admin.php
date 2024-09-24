<?php
class Admin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Order
    public function createOrder($client_id, $address) {
        try {
            $query = "INSERT INTO orders (client_id, address) VALUES (:client_id, :address)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":client_id", $client_id);
            $stmt->bindParam(":address", $address);

            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $exception) {
            echo "Error creating order: " . $exception->getMessage();
        }
        return false;
    }

    // Get All Orders
    public function getOrders() {
        try {
            $query = "SELECT * FROM orders";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo "Error fetching orders: " . $exception->getMessage();
        }
        return [];
    }

    // Assign Driver to Order
    public function assignDriver($order_id, $driver_id) {
        try {
            $query = "UPDATE orders SET driver_id = :driver_id, status = 'picked up' WHERE id = :order_id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":order_id", $order_id);
            $stmt->bindParam(":driver_id", $driver_id);

            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $exception) {
            echo "Error assigning driver: " . $exception->getMessage();
        }
        return false;
    }

    // Update Order Status
    public function updateOrderStatus($order_id, $status) {
        try {
            $query = "UPDATE orders SET status = :status WHERE id = :order_id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":order_id", $order_id);
            $stmt->bindParam(":status", $status);

            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $exception) {
            echo "Error updating order status: " . $exception->getMessage();
        }
        return false;
    }
}
?>

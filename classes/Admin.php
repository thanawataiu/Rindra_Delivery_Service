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

            return $stmt->execute();
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

            return $stmt->execute();
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

            return $stmt->execute();
        } catch (PDOException $exception) {
            echo "Error updating order status: " . $exception->getMessage();
        }
        return false;
    }

    // Get Filtered Orders (search and filter with pagination)
    public function getFilteredOrders($clientName = '', $status = '', $driverId = '', $limit = 10, $offset = 0) {
        try {
            $query = "SELECT o.*, u.name AS client_name FROM orders o 
                      JOIN users u ON o.client_id = u.id WHERE 1=1";
            
            // Apply filters
            if (!empty($clientName)) {
                $query .= " AND u.name LIKE :client_name";
            }
            if (!empty($status)) {
                $query .= " AND o.status = :status";
            }
            if (!empty($driverId)) {
                $query .= " AND o.driver_id = :driver_id";
            }

            // Add pagination
            $query .= " LIMIT :limit OFFSET :offset";

            $stmt = $this->conn->prepare($query);

            // Bind values
            if (!empty($clientName)) {
                $stmt->bindValue(':client_name', '%' . $clientName . '%');
            }
            if (!empty($status)) {
                $stmt->bindValue(':status', $status);
            }
            if (!empty($driverId)) {
                $stmt->bindValue(':driver_id', $driverId);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo "Error fetching filtered orders: " . $exception->getMessage();
        }
        return [];
    }

    // Get Total Filtered Orders Count (for pagination)
    public function getTotalFilteredOrders($clientName = '', $status = '', $driverId = '') {
        try {
            $query = "SELECT COUNT(*) as total FROM orders o 
                      JOIN users u ON o.client_id = u.id WHERE 1=1";
            
            // Apply filters
            if (!empty($clientName)) {
                $query .= " AND u.name LIKE :client_name";
            }
            if (!empty($status)) {
                $query .= " AND o.status = :status";
            }
            if (!empty($driverId)) {
                $query .= " AND o.driver_id = :driver_id";
            }

            $stmt = $this->conn->prepare($query);

            // Bind values
            if (!empty($clientName)) {
                $stmt->bindValue(':client_name', '%' . $clientName . '%');
            }
            if (!empty($status)) {
                $stmt->bindValue(':status', $status);
            }
            if (!empty($driverId)) {
                $stmt->bindValue(':driver_id', $driverId);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $exception) {
            echo "Error fetching total filtered orders: " . $exception->getMessage();
        }
        return 0;
    }

    // Get Pending Orders (for assigning drivers)
    public function getPendingOrders() {
        try {
            $query = "SELECT o.*, u.name AS client_name FROM orders o 
                      JOIN users u ON o.client_id = u.id 
                      WHERE o.driver_id IS NULL AND o.status = 'pending'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo "Error fetching pending orders: " . $exception->getMessage();
        }
        return [];
    }

    // Get Total Orders Count (useful for pagination)
    public function getTotalOrdersCount() {
        try {
            $query = "SELECT COUNT(*) as total FROM orders";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $exception) {
            echo "Error fetching total orders count: " . $exception->getMessage();
        }
        return 0;
    }

    // Get All Drivers (for dropdown in filter)
    public function getAllDrivers() {
        try {
            $query = "SELECT id, name FROM users WHERE role = 'driver'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo "Error fetching drivers: " . $exception->getMessage();
        }
        return [];
    }

    // Get Order by ID (for editing)
    public function getOrderById($order_id) {
        try {
            $query = "SELECT * FROM orders WHERE id = :order_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":order_id", $order_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $exception) {
            echo "Error fetching order by ID: " . $exception->getMessage();
        }
        return null;
    }

    // Update Order (for editing)
    public function updateOrder($order_id, $client_id, $address, $status, $driver_id) {
        try {
            $query = "UPDATE orders SET client_id = :client_id, address = :address, status = :status, driver_id = :driver_id WHERE id = :order_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":order_id", $order_id);
            $stmt->bindParam(":client_id", $client_id);
            $stmt->bindParam(":address", $address);
            $stmt->bindParam(":status", $status);
            $stmt->bindParam(":driver_id", $driver_id);

            return $stmt->execute();
        } catch (PDOException $exception) {
            echo "Error updating order: " . $exception->getMessage();
        }
        return false;
    }

    // Delete Order
    public function deleteOrder($order_id) {
        try {
            $query = "DELETE FROM orders WHERE id = :order_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":order_id", $order_id);

            return $stmt->execute();
        } catch (PDOException $exception) {
            echo "Error deleting order: " . $exception->getMessage();
        }
        return false;
    }
}

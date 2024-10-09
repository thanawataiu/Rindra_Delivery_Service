<?php
class Order {
    private $conn;
    private $table = "orders";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get filtered orders with pagination
    public function getFilteredOrders($clientID = '', $status = '', $driverID = '', $limit = 10, $offset = 0) {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1"; // Base query

        // Apply filters
        if (!empty($clientID)) {
            $query .= " AND client_id LIKE :client_id";
        }
        if (!empty($status)) {
            $query .= " AND status = :status";
        }
        if (!empty($driverID)) {
            $query .= " AND driver_id = :driver_id";
        }

        // Add pagination
        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        // Bind values
        if (!empty($clientID)) {
            $stmt->bindValue(':client_id', '%' . $clientID . '%');
        }
        if (!empty($status)) {
            $stmt->bindValue(':status', $status);
        }
        if (!empty($driverID)) {
            $stmt->bindValue(':driver_id', $driverID);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get total number of filtered orders
    public function getTotalFilteredOrders($clientID = '', $status = '', $driverID = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE 1=1";

        // Apply filters
        if (!empty($clientID)) {
            $query .= " AND client_id LIKE :client_id";
        }
        if (!empty($status)) {
            $query .= " AND status = :status";
        }
        if (!empty($driverID)) {
            $query .= " AND driver_id = :driver_id";
        }

        $stmt = $this->conn->prepare($query);

        // Bind values
        if (!empty($clientID)) {
            $stmt->bindValue(':client_id', '%' . $clientID . '%');
        }
        if (!empty($status)) {
            $stmt->bindValue(':status', $status);
        }
        if (!empty($driverID)) {
            $stmt->bindValue(':driver_id', $driverID);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}

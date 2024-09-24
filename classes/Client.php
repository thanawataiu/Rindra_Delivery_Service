<?php
require_once 'User.php';

class Client extends User {
    public function viewOrderStatus($order_id) {
        $query = "SELECT * FROM orders WHERE id = :order_id AND client_id = :client_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':client_id', $_SESSION['user_id']);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getOrderHistory() {
        $query = "SELECT * FROM orders WHERE client_id = :client_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':client_id', $_SESSION['user_id']);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

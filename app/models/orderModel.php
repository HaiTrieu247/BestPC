<?php

class OrderModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Additional methods for order-related actions can be added here
    public function getAllCarts($userId){
        $stmt = $this->pdo->prepare("
            SELECT product_id, quantity
            FROM orders
            WHERE user_id = :user_id AND status = 'in_cart'
        ");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

       $result = [];
        foreach($rows as $row) {
            $result = array_merge($result, array_fill(0, $row['quantity'], $row['product_id']));
        }
       return $result;
    }

    public function showCart($userId){
        $stmt = $this->pdo->prepare('SELECT product_id, Pimage, Pname, products.price, quantity from products JOIN orders ON products.Pid = orders.product_id WHERE orders.user_id = :user_id AND orders.status = "in_cart"');
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCartQuantity($userId, $productId, $quantity) {
        $stmt1 = $this->pdo->prepare("SELECT price FROM products WHERE Pid = :product_id");
        $stmt1->bindParam(':product_id', $productId);
        $stmt1->execute();
        $price = $stmt1->fetchColumn();

        $stmt = $this->pdo->prepare("UPDATE orders SET quantity = :quantity, price = :price * :quantity WHERE user_id = :user_id AND product_id = :product_id AND status = 'in_cart'");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
    }

    public function removeFromCart($userId, $productId) {
        $stmt = $this->pdo->prepare("DELETE FROM orders WHERE user_id = :user_id AND product_id = :product_id AND status = 'in_cart'");
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
    }

    public function getCartTotal($userId) {
        $stmt = $this->pdo->prepare("SELECT SUM(price) FROM orders WHERE user_id = :user_id AND status = 'in_cart'");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchColumn() ?? 0;
    }

    public function changeStatus($userId, $status, $orderId = null) {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = :status, order_date = NOW() WHERE user_id = :user_id" . ($orderId ? " AND order_id = :order_id" : " AND status = 'in_cart'"));
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':user_id', $userId);
        if ($orderId) {
            $stmt->bindParam(':order_id', $orderId);
        }
        $stmt->execute();
    }
    public function getOrders($userId=null, $filterApplied, $PriceRange, $DateRange, $SortBy, $exception, $searchById=null) {
        $placeholders = implode(',', array_fill(0, count($exception), '?'));
        $params = [];
        $sql = "SELECT order_id, orders.city AS city, orders.district AS district, orders.address AS address, orders.phone AS phone, user_id, product_id, Pname, Pimage, products.price AS product_price, quantity, order_date, orders.price AS subtotal, status 
                FROM orders 
                JOIN products ON orders.product_id = products.Pid ";
        if ($userId) {
            $sql .= "WHERE orders.user_id = ?";
            $params[] = $userId;
        } else {
            $sql .= "WHERE 1=1";
        }
        $sql .= " AND status NOT IN ($placeholders)";
        $params = array_merge($params, $exception);
        if(!empty($filterApplied['Status'])) {
            $placeholders = implode(',', array_fill(0, count($filterApplied['Status']), '?'));
            $sql .= " AND status IN ($placeholders)";
            $params = array_merge($params, $filterApplied['Status']);
        }
        if (!empty($PriceRange['min_price'])) {
            $sql .= " AND orders.price >= ?";
            $params[] = $PriceRange['min_price'];
        }
        if (!empty($PriceRange['max_price'])) {
            $sql .= " AND orders.price <= ?";
            $params[] = $PriceRange['max_price'];
        }

        if (!empty($DateRange['start_date'])) {
            $sql .= " AND order_date >= ?";
            $params[] = $DateRange['start_date'];
        }
        if (!empty($DateRange['end_date'])) {
            $sql .= " AND order_date <= ?";
            $params[] = $DateRange['end_date'];
        }

        if ($SortBy == 'price_asc') {
            $sql .= " ORDER BY orders.price ASC";
        } elseif ($SortBy == 'price_desc') {
            $sql .= " ORDER BY orders.price DESC";
        } elseif ($SortBy == 'date_asc') {
            $sql .= " ORDER BY order_date ASC";
        } elseif ($SortBy == 'date_desc') {
            $sql .= " ORDER BY order_date DESC";
        }

        if (isset($searchById)) {
            $sql .= " AND orders.order_id = ?";
            $params[] = $searchById;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateLocation($userId, $data) {
        $stmt = $this->pdo->prepare("UPDATE orders SET city = :city, district = :district, address = :address, phone = :phone WHERE user_id = :id");
        $stmt->bindParam(":city", $data['city'], PDO::PARAM_STR);
        $stmt->bindParam(":district", $data['district'], PDO::PARAM_STR);
        $stmt->bindParam(":address", $data['address'], PDO::PARAM_STR);
        $stmt->bindParam(":phone", $data['phone'], PDO::PARAM_STR);
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
<?php

class StoreModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getStoreByName($storeName) {
        $stmt = $this->pdo->prepare("SELECT * FROM stores WHERE Sname = :storeName");
        $stmt->bindParam(':storeName', $storeName, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllStores() {
        $stmt = $this->pdo->prepare("SELECT * FROM stores");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addProductToStores($productId) {
        $stores = $this->getAllStores();
        foreach ($stores as $store) {
            $storeId = $store['Sid'];
            $quantity = 0; // Default quantity
            $stmt = $this->pdo->prepare("INSERT INTO in_stock (product_id, store_id, quantity) VALUES (:productId, :storeId, :quantity)");
            $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    public function updateProductStock($productId, $storeName, $newStock) {
        $store = $this->getStoreByName($storeName);
        $storeId = $store['Sid'];
        $stmt = $this->pdo->prepare("UPDATE in_stock SET quantity = :quantity WHERE product_id = :productId AND store_id = :storeId");
        $stmt->bindParam(':quantity', $newStock, PDO::PARAM_INT);
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':storeId', $storeId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
<?php

class SearchModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function searchProducts($query, $SortBy, $page=1) {
        $limit = 8;
        $offset = ($page-1) * $limit;

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM products WHERE Pname LIKE :query OR Pdescription LIKE :query");
        $likeQuery = '%' . $query . '%';
        $stmt->bindParam(':query', $likeQuery, PDO::PARAM_STR);
        $stmt->execute();
        $total = $stmt->fetchColumn();

        $sql = "SELECT * FROM products WHERE Pname LIKE :query OR Pdescription LIKE :query";
        if ($SortBy == 'price_asc') {
            $sql .= " ORDER BY products.price ASC";
        } elseif ($SortBy == 'price_desc') {
            $sql .= " ORDER BY products.price DESC";
        }
        $stmt = $this->pdo->prepare($sql . " LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':query', $likeQuery, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'products' => $results, 
            'totalPages' => max(1, ceil($total / $limit)), 
            'currentPage' => (int)$page
        ];
    }

    public function searchLive($query) {
        $stmt = $this->pdo->prepare("
            SELECT Pid, Pname, Pimage 
            FROM products 
            WHERE Pname LIKE :q 
            LIMIT 10
        ");
        $like = '%' . $query . '%';
        $stmt->bindParam(':q', $like, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
<?php

class SearchModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function searchProducts($query, $SortBy, $page=1) {
        $limit = 8;
        $offset = ($page-1) * $limit;

        $sql = "SELECT COUNT(*) FROM products WHERE (Pname LIKE :query OR Pdescription LIKE :query)";
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $sql .= " AND is_hidden = 0";
        }
        $stmt = $this->pdo->prepare($sql);
        $likeQuery = '%' . $query . '%';
        $stmt->bindParam(':query', $likeQuery, PDO::PARAM_STR);
        $stmt->execute();
        $total = $stmt->fetchColumn();

        $sql = "SELECT * FROM products WHERE (Pname LIKE :query OR Pdescription LIKE :query)";
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $sql .= " AND is_hidden = 0";
        }
        if ($SortBy == 'price_asc') {
            $sql .= " ORDER BY price ASC";
        } elseif ($SortBy == 'price_desc') {
            $sql .= " ORDER BY price DESC";
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
        $sql = "
            SELECT Pid, Pname, Pimage 
            FROM products 
            WHERE Pname LIKE :q
        ";
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $sql .= " AND is_hidden = 0";
        }
        $sql .= " LIMIT 10";
        $stmt = $this->pdo->prepare($sql);
        $like = '%' . $query . '%';
        $stmt->bindParam(':q', $like, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
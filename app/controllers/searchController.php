<?php

include __DIR__ . '/../models/searchModel.php';
include __DIR__ . '/../models/orderModel.php';
include __DIR__ . '/../models/productModel.php';

class SearchController {
    private $searchModel;
    private $orderModel;
    private $productModel;

    public function __construct($pdo) {
        $this->searchModel = new SearchModel($pdo);
        $this->orderModel = new OrderModel($pdo);
        $this->productModel = new ProductModel($pdo);
    }

    public function searchProducts($query, $SortBy) {
        if (!$query) {
            header("Location: index.php");
            exit;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $result = $this->searchModel->searchProducts($query, $SortBy, $page);
        $products = $result['products'];
        $totalPages = $result['totalPages'];
        $currentPage = $result['currentPage'];

        if (isset($_SESSION['id'])) {
            $_SESSION['cart'] = $this->orderModel->getAllCarts($_SESSION['id']);
        }
        $categories = $this->productModel->getAllCategories();
        $types = $this->productModel->getType();
        $grouped = [];
        foreach ($categories as $cat) {
            $grouped[$cat['Cname']] = []; 
        }

        foreach ($types as $t) {
            foreach ($categories as $cat) {
                if ($t['category_id'] == $cat['Cid']) {
                    $grouped[$cat['Cname']][] = $t['Tname'];
                }
            }
        }
        include __DIR__ . '/../../resources/search.php';
    }
    public function searchAjax($query) {
        header('Content-Type: application/json');
        if (!$query) {
            echo json_encode([]);
            exit;
        }
        $results = $this->searchModel->searchLive($query);
        echo json_encode($results);
        exit;
    }
}
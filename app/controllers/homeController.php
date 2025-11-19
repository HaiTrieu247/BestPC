<?php

include __DIR__ . '/../models/productModel.php';
include __DIR__ . '/../models/orderModel.php';

class HomeController {
    private $productModel;
    private $orderModel;

    public function __construct($pdo) {
        $this->productModel = new ProductModel($pdo);
        $this->orderModel = new OrderModel($pdo);
    }

    public function show() {
        $products = $this->productModel->getAllProducts();

        $types = $this->productModel->getType();
        $feature = [];
        foreach ($types as $type) {
            $typeID = $type['Tid'];
            $typeName = $type['Tname'];
            $feature[$typeName] = array_slice(array_filter($products, fn($p) => intval($p['type_id']) === intval($typeID)), 0, 4);
        }

        $categories = $this->productModel->getAllCategories();
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
        if (isset($_SESSION['id'])) {
            $_SESSION['cart'] = $this->orderModel->getAllCarts($_SESSION['id']);
        }

        include __DIR__ . '/../../resources/home.php';
    }


}
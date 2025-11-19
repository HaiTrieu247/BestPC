<?php

include __DIR__ . '/../models/productModel.php';
include __DIR__ . '/../models/orderModel.php';
include __DIR__ . '/../models/storeModel.php';

class ProductController {
    private $productModel;
    private $orderModel;
    private $storeModel;

    public function __construct($pdo) {
        $this->productModel = new ProductModel($pdo);
        $this->orderModel = new OrderModel($pdo);
        $this->storeModel = new StoreModel($pdo);
    }

    public function getProductsByType($type, $filterApplied, $PriceRange, $SortBy) {
        $page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);

        $pagination = $this->productModel->getProductsByType($type, $filterApplied, $page, $PriceRange, $SortBy);
        $products = $pagination['products'];
        $totalPages = $pagination['totalPages'];
        $currentPage = $pagination['currentPage'];
        $title = $type;
        $categories = $this->productModel->getAllCategories();
        $types = $this->productModel->getType();

        $brands = $this->productModel->getBrandByType($type);
        $manufacturers = $this->productModel->getManufacturerByType($type);
        $series = $this->productModel->getSeriesByType($type);

        $filter = [];

        $filter['Brand'] = [];
        foreach ($brands as $brand) {
            $filter['Brand'][] = $brand['Bname'];
        }

        if($type === 'Graphic Card' || $type === 'CPU' || $type === 'Motherboard') {
            $filter['Manufacturer'] = [];
            foreach ($manufacturers as $manufacturer) {
                $filter['Manufacturer'][] = $manufacturer['Mname'];
            }
        }

        $filter['Series'] = [];
        foreach ($series as $serie) {
            $filter['Series'][] = $serie['Sname'];
        }
        
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

        include __DIR__ . '/../../resources/all-products.php';
    }

    public function getProductById($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header("HTTP/1.0 404 Not Found");
            echo "Product not found";
            exit;
        }
        if (isset($_SESSION['id']) && $_SESSION['role'] === 'store') {
            $stock = $this->productModel->getProductStockForStore($id, $_SESSION['name']);
            
        }
        $productId = $product['Pid'];
        $name = $product['Pname'];
        $type = $this->productModel->convertToType($product['type_id']);
        $categoryId = $product['category_id'];
        $image = $product['Pimage'];
        $price = $product['price'];
        $description = $product['Pdescription'];
        $stores = $this->productModel->getStoreAvailability($id);
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
        if (isset($_SESSION['id'])) {
            $_SESSION['cart'] = $this->orderModel->getAllCarts($_SESSION['id']);
        }
        include __DIR__ . '/../../resources/view-detail.php';
    }

    public function addToCart($id) {
        // Placeholder for adding product to cart logic
        // This function can be expanded to actually add the product to the user's cart
        $this->productModel->addProductToCart($id, $_SESSION['id']);
    }

    public function editProduct($id, $data) {
        // Placeholder for editing product details logic
        // This function can be expanded to actually update the product details in the database
        $this->productModel->updateProductDetails($id, $data);
    }

    public function changeProductStock($productId, $storeName, $newStock) {
        $this->storeModel->updateProductStock($productId, $storeName, $newStock);
    }
}

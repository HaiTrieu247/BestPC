<?php

include __DIR__ . '/../models/orderModel.php';
include __DIR__ . '/../models/productModel.php';
include __DIR__ . '/../models/userModel.php';
include __DIR__ . '/../models/storeModel.php';

class AdminController {
    private $orderModel;
    private $productModel;
    private $userModel;
    private $storeModel;

    public function __construct($pdo) {
        $this->orderModel = new OrderModel($pdo);
        $this->productModel = new ProductModel($pdo);
        $this->userModel = new UserModel($pdo);
        $this->storeModel = new StoreModel($pdo);
    }

    public function getIncomingOrders($sortby){
        // Placeholder for showing user orders
        $exception = ['in_cart', 'Shipped', 'Delivered', 'Canceled'];
        $orders = $this->orderModel->getOrders(null, [], [], [], $sortby, $exception);

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

        include __DIR__ .'/../../resources/admin-incoming-orders.php';
    }

    public function changeStatus($userId, $status, $orderId) {
        $this->orderModel->changeStatus($userId, $status, $orderId);
    }

    public function showUsers($searchByName, $searchById) {
        $users = $this->userModel->getAllUsers($searchByName, $searchById);
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
        include __DIR__ .'/../../resources/user-management.php';
    }

    public function showUserDetail($id, $filterApplied, $PriceRange, $DateRange, $SortBy, $exception, $searchById) {
        $user = $this->userModel->getUserById($id);
        $orders = $this->orderModel->getOrders($id, $filterApplied, $PriceRange, $DateRange, $SortBy, $exception, $searchById);

        $userId = $id;

        $filter = [];

        $filter['Status'] = ['Placed', 'Shipped', 'Delivered', 'Cancelled', 'Cancel Awaiting'];

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

        include __DIR__ .'/../../resources/user-detail.php';
    }
    public function getCategories($categoryId=null, $categoryName=null){
        $cats = $this->productModel->getAllCategories($categoryId, $categoryName);
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

        include __DIR__ .'/../../resources/product-category.php';
    }

    public function addCategory($categoryName){
        $this->productModel->addCategory($categoryName);
    }

    public function getTypesByCategory($categoryId, $typeName = null, $typeId = null){
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

        $types = $this->productModel->getTypesByCategory($categoryId, $typeName, $typeId);
        $categoryName = $this->productModel->getCategoryName($categoryId);

        include __DIR__ .'/../../resources/product-type.php';
    }

    public function addType($categoryId, $typeName){
        $this->productModel->addType($categoryId, $typeName);
    }

    public function getProductsByType($type, $filterApplied, $PriceRange, $SortBy){
        $page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);

        $pagination = $this->productModel->getProductsByType($type, $filterApplied, $page, $PriceRange, $SortBy);
        $products = $pagination['products'];
        $totalPages = $pagination['totalPages'];
        $currentPage = $pagination['currentPage'];
        $title = $type;
        $categories = $this->productModel->getAllCategories();
        $types = $this->productModel->getType();
        $categoryId = $_GET['category_id'] ?? null;
        $typeId = $this->productModel->convertToId_type($type);
        $categoryName = $this->productModel->convertToCategory($categoryId);

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

        include __DIR__ . '/../../resources/view-products-by-type.php';
    }

    public function addProduct($productData){
        $productId = $this->productModel->addProduct($productData);
        $this->storeModel->addProductToStores($productId);
    }

    public function getTypeName($typeId){
        return $this->productModel->convertToType($typeId);
    }
}